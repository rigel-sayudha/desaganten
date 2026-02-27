<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Domisili;
use App\Models\TidakMampu;
use App\Models\BelumMenikah;
use App\Models\SuratKematian;
use App\Models\SuratUsaha;
use App\Models\SuratSkck;
use App\Models\SuratKk;
use App\Models\SuratKtp;
use App\Models\SuratKelahiran;
use App\Models\SuratKehilangan;
use App\Services\VerificationStageService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SuratUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Debug logging
    Log::info('User surat index accessed - NEW VERSION', [
            'user_id' => $user->id,
            'user_nik' => $user->nik,
            'user_name' => $user->name,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
        
        // Get all surat by user NIK (older models) and user_id - prioritize user_id first
        $suratDomisili = collect();
        $suratTidakMampu = collect();
        $suratBelumMenikah = collect();
        
        // Try to get by user_id first (newer approach)
        $suratDomisili = Domisili::where('user_id', $user->id)->get();
        $suratTidakMampu = TidakMampu::where('user_id', $user->id)->get();
        $suratBelumMenikah = BelumMenikah::where('user_id', $user->id)->get();
        
        // Fallback to NIK if user_id search returns empty AND user has NIK
        if ($user->nik) {
            if ($suratDomisili->isEmpty()) {
                $additionalDomisili = Domisili::where('nik', $user->nik)->get();
                $suratDomisili = $suratDomisili->merge($additionalDomisili);
            }
            if ($suratTidakMampu->isEmpty()) {
                $additionalTidakMampu = TidakMampu::where('nik', $user->nik)->get();
                $suratTidakMampu = $suratTidakMampu->merge($additionalTidakMampu);
            }
            if ($suratBelumMenikah->isEmpty()) {
                $additionalBelumMenikah = BelumMenikah::where('nik', $user->nik)->get();
                $suratBelumMenikah = $suratBelumMenikah->merge($additionalBelumMenikah);
            }
        }
        
        // Debug logging for domisili query specifically
    Log::info('Domisili query debug', [
            'user_id' => $user->id,
            'user_nik' => $user->nik,
            'found_by_user_id' => Domisili::where('user_id', $user->id)->count(),
            'found_by_nik' => $user->nik ? Domisili::where('nik', $user->nik)->count() : 0,
            'total_found' => $suratDomisili->count()
        ]);
        
        // Get all surat by user_id (newer models)
        $suratKematian = SuratKematian::where('user_id', $user->id)->get();
        $suratUsaha = SuratUsaha::where('user_id', $user->id)->get();
        $suratSkck = SuratSkck::where('user_id', $user->id)->get();
        $suratKelahiran = SuratKelahiran::where('user_id', $user->id)->get();
        $suratKehilangan = SuratKehilangan::where('user_id', $user->id)->get();
        
        // Get KTP and KK surat - prioritize user_id, fallback to NIK if available
        $suratKtp = SuratKtp::where('user_id', $user->id)->get();
        if ($suratKtp->isEmpty() && $user->nik) {
            $suratKtp = SuratKtp::where('nik', $user->nik)->get();
        }
        
        $suratKk = collect();
        if (class_exists('App\\Models\\SuratKk')) {
            $suratKk = SuratKk::where('user_id', $user->id)->get();
            if ($suratKk->isEmpty() && $user->nik) {
                $suratKk = SuratKk::where('nik', $user->nik)->get();
            }
        }
        
        // Debug logging for counts
    Log::info('Surat counts per type', [
            'domisili' => $suratDomisili->count(),
            'tidak_mampu' => $suratTidakMampu->count(),
            'belum_menikah' => $suratBelumMenikah->count(),
            'kematian' => $suratKematian->count(),
            'usaha' => $suratUsaha->count(),
            'skck' => $suratSkck->count(),
            'ktp' => $suratKtp->count(),
            'kk' => $suratKk->count(),
            'kelahiran' => $suratKelahiran->count(),
            'kehilangan' => $suratKehilangan->count()
        ]);
        
        // Additional debug logging for domisili specifically (simplified to avoid complex parsing)
        if ($suratDomisili->count() > 0) {
            Log::info('Domisili details found', [
                'count' => $suratDomisili->count(),
                'ids_sample' => $suratDomisili->pluck('id')->take(10)->toArray()
            ]);
        } else {
            Log::warning('No domisili found for user', [
                'user_id' => $user->id,
                'user_nik' => $user->nik,
                'total_domisili_in_db' => Domisili::count()
            ]);
        }
        
        // Combine all surat
        $allSurat = collect();
        
        // Process older models (NIK-based)
        foreach ($suratDomisili as $surat) {
            $surat->jenis_surat = 'domisili';
            $surat->nama_pemohon = $surat->nama;
            $allSurat->push($surat);
        }
        
        foreach ($suratTidakMampu as $surat) {
            $surat->jenis_surat = 'tidak_mampu';
            $surat->nama_pemohon = $surat->nama;
            $allSurat->push($surat);
        }
        
        foreach ($suratBelumMenikah as $surat) {
            $surat->jenis_surat = 'belum_menikah';
            $surat->nama_pemohon = $surat->nama;
            $allSurat->push($surat);
        }
        
        // Process newer models (user_id-based)
        foreach ($suratKematian as $surat) {
            $surat->jenis_surat = 'kematian';
            $surat->nama_pemohon = $surat->nama_pelapor;
            $allSurat->push($surat);
        }
        
        foreach ($suratUsaha as $surat) {
            $surat->jenis_surat = 'usaha';
            $surat->nama_pemohon = $surat->nama_lengkap;
            $allSurat->push($surat);
        }
        
        foreach ($suratSkck as $surat) {
            $surat->jenis_surat = 'skck';
            $surat->nama_pemohon = $surat->nama_lengkap;
            $allSurat->push($surat);
        }
        
        foreach ($suratKelahiran as $surat) {
            $surat->jenis_surat = 'kelahiran';
            $surat->nama_pemohon = $surat->nama_pelapor;
            $allSurat->push($surat);
        }
        
        foreach ($suratKehilangan as $surat) {
            $surat->jenis_surat = 'kehilangan';
            $surat->nama_pemohon = $surat->nama_lengkap;
            $allSurat->push($surat);
        }
        
        // Process NIK-only models
        foreach ($suratKtp as $surat) {
            $surat->jenis_surat = 'ktp';
            $surat->nama_pemohon = $surat->nama_lengkap;
            $allSurat->push($surat);
        }
        
        foreach ($suratKk as $surat) {
            $surat->jenis_surat = 'kk';
            $surat->nama_pemohon = $surat->nama_lengkap;
            $allSurat->push($surat);
        }

        $allSurat = $allSurat->sortByDesc('created_at');

    Log::info('Final surat collection', [
            'total_count' => $allSurat->count(),
            'user_id' => $user->id,
            'user_nik' => $user->nik ?? 'NULL',
            'surat_ids' => $allSurat->pluck('id')->toArray()
        ]);

        return response()
            ->view('user.surat.index', compact('allSurat'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
    
    public function printPdf($type, $id)
    {
        $surat = $this->getSuratByType($type, $id);
        $user = Auth::user();
        Log::info("PrintPdf - Type: $type, ID: $id, User ID: {$user->id}, User NIK: {$user->nik}");
        if (!$surat) { return redirect()->back()->with('error', 'Surat tidak ditemukan'); }
        
        Log::info("PrintPdf - Surat found", [
            'type' => $type,
            'id' => $id,
            'surat_user_id' => $surat->user_id ?? 'null',
            'surat_nik' => $surat->nik ?? 'null',
            'surat_nama' => $surat->nama ?? 'null',
            'surat_nama_lengkap' => $surat->nama_lengkap ?? 'null',
            'user_data' => [
                'id' => $user->id,
                'nik' => $user->nik,
                'name' => $user->name
            ]
        ]);
        
        // Comprehensive ownership verification
        $isOwner = false;
        $ownershipMethod = 'none';
        
        // Method 1: Check user_id (primary for newer models)
        if (isset($surat->user_id) && $surat->user_id == $user->id) {
            $isOwner = true;
            $ownershipMethod = 'user_id';
        }
        // Method 2: Check NIK (fallback for older models)
        elseif (isset($surat->nik) && $surat->nik === $user->nik) {
            $isOwner = true;
            $ownershipMethod = 'nik';
        }
        // Method 3: Check nama field match with user name
        elseif (isset($surat->nama) && trim(strtolower($surat->nama)) === trim(strtolower($user->name))) {
            $isOwner = true;
            $ownershipMethod = 'nama';
        }
        // Method 4: Check nama_lengkap field match with user name
        elseif (isset($surat->nama_lengkap) && trim(strtolower($surat->nama_lengkap)) === trim(strtolower($user->name))) {
            $isOwner = true;
            $ownershipMethod = 'nama_lengkap';
        }
        
        Log::info("PrintPdf - Ownership check result", [
            'isOwner' => $isOwner,
            'method' => $ownershipMethod,
            'checks' => [
                'user_id_match' => isset($surat->user_id) ? ($surat->user_id == $user->id) : 'field_not_set',
                'nik_match' => isset($surat->nik) ? ($surat->nik === $user->nik) : 'field_not_set',
                'nama_match' => isset($surat->nama) ? (trim(strtolower($surat->nama)) === trim(strtolower($user->name))) : 'field_not_set',
                'nama_lengkap_match' => isset($surat->nama_lengkap) ? (trim(strtolower($surat->nama_lengkap)) === trim(strtolower($user->name))) : 'field_not_set'
            ]
        ]);
        
        if (!$isOwner) {
            Log::warning("PrintPdf - Access denied: User does not own this surat", [
                'user_id' => $user->id,
                'user_nik' => $user->nik,
                'surat_type' => $type,
                'surat_id' => $id,
                'available_fields' => array_keys($surat->toArray())
            ]);
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke surat ini');
        }
        
        // Check if surat is verified or completed
        $allowedStatuses = ['sudah diverifikasi', 'selesai diproses', 'approved', 'selesai'];
        $statusLower = strtolower($surat->status ?? '');
        
        if (!in_array($statusLower, array_map('strtolower', $allowedStatuses))) {
            Log::warning("PrintPdf - Status not allowed for download", [
                'current_status' => $surat->status ?? 'null',
                'allowed_statuses' => $allowedStatuses,
                'user_id' => $user->id,
                'surat_type' => $type,
                'surat_id' => $id
            ]);
            return redirect()->back()->with('error', 'Surat belum selesai diverifikasi');
        }
        
        $progress = 0;
        if ($surat->tahapan_verifikasi) {
            $stages = is_string($surat->tahapan_verifikasi) ? json_decode($surat->tahapan_verifikasi, true) : $surat->tahapan_verifikasi;
            $progress = VerificationStageService::getProgressPercentage($stages);
        }
        
        // Allow download if progress is 100% OR status indicates completion
        $statusLower = strtolower($surat->status ?? '');
        $isStatusComplete = in_array($statusLower, ['sudah diverifikasi', 'selesai diproses', 'approved', 'selesai']);
        
        if ($progress < 100 && !$isStatusComplete) {
            Log::warning("PrintPdf - Progress incomplete and status not ready", [
                'progress' => $progress,
                'status' => $surat->status ?? 'null',
                'user_id' => $user->id,
                'surat_type' => $type,
                'surat_id' => $id
            ]);
            return redirect()->back()->with('error', 'Surat belum selesai diverifikasi');
        }
        
        $jenisSuratMap = [
            'domisili' => 'Surat Keterangan Domisili',
            'tidak_mampu' => 'Surat Keterangan Tidak Mampu',
            'belum_menikah' => 'Surat Keterangan Belum Menikah',
            'kematian' => 'Surat Keterangan Kematian',
            'usaha' => 'Surat Keterangan Usaha',
            'skck' => 'Surat Pengantar SKCK',
            'kk' => 'Surat Pengantar Kartu Keluarga',
            'ktp' => 'Surat Pengantar KTP',
            'kelahiran' => 'Surat Keterangan Kelahiran',
            'kehilangan' => 'Surat Keterangan Kehilangan'
        ];
        
        $jenisSurat = $jenisSuratMap[$type] ?? ucfirst(str_replace('_', ' ', $type));
        
        try {
            $data = [
                'surat' => $surat,
                'type' => $type,
                'jenisSurat' => $jenisSurat,
                'user' => $user,
                'tanggal_cetak' => now()->format('d F Y')
            ];
            
            Log::info('Starting PDF generation', [
                'type' => $type,
                'template' => 'user.surat.print.' . $type,
                'data_keys' => array_keys($data),
                'memory_before' => memory_get_usage(true) / 1024 / 1024 . 'MB',
                'memory_limit' => ini_get('memory_limit')
            ]);
            
            // Verify template exists
            if (!view()->exists('user.surat.print.' . $type)) {
                Log::error('PDF template not found', [
                    'template' => 'user.surat.print.' . $type,
                    'available_views' => array_keys(app('view')->getFinder()->getHints())
                ]);
                throw new \Exception("Template PDF tidak ditemukan: user.surat.print.{$type}");
            }

            // Log template content for debugging
            $viewContent = view('user.surat.print.' . $type, $data)->render();
            Log::debug('View content length: ' . strlen($viewContent));

            // Generate PDF with increased memory limit and DomPDF options
            ini_set('memory_limit', '512M');
            Log::info('Attempting to generate PDF', [
                'new_memory_limit' => ini_get('memory_limit'),
                'current_memory' => memory_get_usage(true) / 1024 / 1024 . 'MB'
            ]);

            $pdf = Pdf::loadView('user.surat.print.' . $type, $data)
                ->setOptions([
                    'isRemoteEnabled' => true,
                    'enable_remote' => true,
                    'defaultFont' => 'Arial',
                    'dpi' => 150,
                    'defaultPaperSize' => 'a4',
                    'isPhpEnabled' => false,
                    'isJavascriptEnabled' => false,
                    'debugPng' => false,
                    'debugKeepTemp' => false,
                    'debugCss' => false,
                    'logOutputFile' => null,
                    'allowedProtocols' => [
                        'data://' => ['rules' => []],
                        'file://' => ['rules' => []],
                        'http://' => ['rules' => []],
                        'https://' => ['rules' => []],
                    ],
                ]);
            
            // Get the appropriate name field based on the surat type
            $nama = $surat->nama_lengkap ?? $surat->nama ?? $surat->nama_pelapor ?? 'unknown';
            $filename = 'surat_' . $type . '_' . $nama . '.pdf';
            
            Log::info('PDF generated successfully', [
                'filename' => $filename,
                'memory_used' => memory_get_usage(true) / 1024 / 1024 . 'MB',
                'peak_memory' => memory_get_peak_usage(true) / 1024 / 1024 . 'MB'
            ]);
            
            return $pdf->download($filename);
            
        } catch (\Dompdf\Exception $e) {
            Log::error('DomPDF Error', [
                'message' => $e->getMessage(),
                'type' => $type,
                'id' => $id,
                'memory_used' => memory_get_usage(true) / 1024 / 1024 . 'MB',
                'peak_memory' => memory_get_peak_usage(true) / 1024 / 1024 . 'MB',
                'trace' => $e->getTraceAsString(),
                'php_version' => PHP_VERSION,
                'dompdf_version' => class_exists('\\Dompdf\\Dompdf') ? 'Available' : 'Not available',
                'template_exists' => view()->exists('user.surat.print.' . $type),
                'surat_data' => [
                    'id' => $surat->id ?? 'null',
                    'status' => $surat->status ?? 'null',
                    'has_required_fields' => [
                        'nama_lengkap' => isset($surat->nama_lengkap),
                        'nama' => isset($surat->nama),
                        'nik' => isset($surat->nik)
                    ]
                ]
            ]);
            
            // For production debugging, also log to a specific PDF error file
            \Illuminate\Support\Facades\Storage::append('pdf_errors.log', 
                '[' . now() . '] DomPDF Error: ' . $e->getMessage() . 
                ' | Type: ' . $type . 
                ' | ID: ' . $id . 
                ' | User: ' . ($user->id ?? 'unknown') . PHP_EOL
            );
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat PDF (DomPDF Error). Detail: ' . $e->getMessage());
            
        } catch (\Exception $e) {
            Log::error('PDF Generation Error', [
                'message' => $e->getMessage(),
                'type' => $type,
                'id' => $id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'memory_used' => memory_get_usage(true) / 1024 / 1024 . 'MB',
                'peak_memory' => memory_get_peak_usage(true) / 1024 / 1024 . 'MB',
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'template_path' => 'user.surat.print.' . $type,
                'template_exists' => view()->exists('user.surat.print.' . $type),
                'available_disk_space' => disk_free_space(storage_path()) / 1024 / 1024 . 'MB',
                'surat_data_sample' => [
                    'id' => $surat->id ?? 'null',
                    'type' => $type,
                    'status' => $surat->status ?? 'null',
                    'created_at' => $surat->created_at ?? 'null',
                    'data_fields' => array_keys($surat->toArray() ?? [])
                ]
            ]);
            
            // For production debugging
            \Illuminate\Support\Facades\Storage::append('pdf_errors.log', 
                '[' . now() . '] General PDF Error: ' . $e->getMessage() . 
                ' | File: ' . $e->getFile() . 
                ' | Line: ' . $e->getLine() . 
                ' | Type: ' . $type . 
                ' | ID: ' . $id . 
                ' | User: ' . ($user->id ?? 'unknown') . PHP_EOL
            );
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat PDF. Detail teknis: ' . $e->getMessage() . ' di ' . basename($e->getFile()) . ':' . $e->getLine());
        }
    }
    public function show($type, $id)
    {
        $surat = $this->getSuratByType($type, $id);
        $user = Auth::user();
        
    Log::info('User accessing surat detail', [
            'user_id' => $user->id,
            'user_nik' => $user->nik,
            'surat_type' => $type,
            'surat_id' => $id,
            'surat_found' => $surat ? 'yes' : 'no'
        ]);
        
        if (!$surat) {
            Log::warning('Surat not found', ['type' => $type, 'id' => $id]);
            return redirect()->back()->with('error', 'Surat tidak ditemukan');
        }
        
        // Check if user is the owner - improved logic
        $isOwner = false;
        
        // Check by user_id first (newer models)
        if (isset($surat->user_id) && $surat->user_id == $user->id) {
            $isOwner = true;
            Log::info('Ownership verified by user_id', ['surat_user_id' => $surat->user_id, 'current_user_id' => $user->id]);
        }
        // Check by NIK (older models) - only if user has NIK and surat has NIK
        elseif (isset($surat->nik) && $user->nik && $surat->nik == $user->nik) {
            $isOwner = true;
            Log::info('Ownership verified by NIK', ['surat_nik' => $surat->nik, 'current_user_nik' => $user->nik]);
        }
        // For some models, check nama field as fallback
        elseif (isset($surat->nama) && $surat->nama == $user->name) {
            $isOwner = true;
            Log::info('Ownership verified by name', ['surat_nama' => $surat->nama, 'current_user_name' => $user->name]);
        }
        // For models with nama_lengkap field
        elseif (isset($surat->nama_lengkap) && $surat->nama_lengkap == $user->name) {
            $isOwner = true;
            Log::info('Ownership verified by nama_lengkap', ['surat_nama_lengkap' => $surat->nama_lengkap, 'current_user_name' => $user->name]);
        }
        
        if (!$isOwner) {
            Log::warning('User does not own this surat', [
                'user_id' => $user->id,
                'user_nik' => $user->nik,
                'user_name' => $user->name,
                'surat_user_id' => $surat->user_id ?? 'null',
                'surat_nik' => $surat->nik ?? 'null',
                'surat_nama' => $surat->nama ?? 'null',
                'surat_nama_lengkap' => $surat->nama_lengkap ?? 'null'
            ]);
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke surat ini');
        }
        
        $stages = [];
        $progress = 0;
        $currentStage = 0;
        
        if ($surat->tahapan_verifikasi) {
            $stages = is_string($surat->tahapan_verifikasi) ? json_decode($surat->tahapan_verifikasi, true) : $surat->tahapan_verifikasi;
            $progress = VerificationStageService::getProgressPercentage($stages);
            $currentStage = VerificationStageService::getCurrentStage($stages);
        }
        
        $jenisSuratMap = [
            'domisili' => 'Surat Keterangan Domisili',
            'tidak_mampu' => 'Surat Keterangan Tidak Mampu',
            'belum_menikah' => 'Surat Keterangan Belum Menikah',
            'kematian' => 'Surat Keterangan Kematian',
            'usaha' => 'Surat Keterangan Usaha',
            'skck' => 'Surat Pengantar SKCK',
            'kk' => 'Surat Pengantar Kartu Keluarga',
            'ktp' => 'Surat Pengantar KTP',
            'kelahiran' => 'Surat Keterangan Kelahiran',
            'kehilangan' => 'Surat Keterangan Kehilangan'
        ];
        
        $jenisSurat = $jenisSuratMap[$type] ?? ucfirst(str_replace('_', ' ', $type));
        
        return view('user.surat.show', compact('surat', 'type', 'stages', 'progress', 'currentStage', 'jenisSurat'));
    }
    
    private function getSuratByType($type, $id)
    {
        switch ($type) {
            case 'domisili':
                return Domisili::find($id);
            case 'tidak_mampu':
                return TidakMampu::find($id);
            case 'belum_menikah':
                return BelumMenikah::find($id);
            case 'kematian':
                return SuratKematian::find($id);
            case 'usaha':
                return SuratUsaha::find($id);
            case 'skck':
                return SuratSkck::find($id);
            case 'kk':
                return SuratKk::find($id);
            case 'ktp':
                return SuratKtp::find($id);
            case 'kelahiran':
                return SuratKelahiran::find($id);
            case 'kehilangan':
                return SuratKehilangan::find($id);
            default:
                return null;
        }
    }

    public function completeSurat($type, $id)
    {
        try {
            $user = Auth::user();
            
            // Get the surat instance
            $surat = $this->getSuratByType($type, $id);
            
            if (!$surat) {
                return redirect()->back()->with('error', 'Surat tidak ditemukan.');
            }
            
            // Check if user owns this surat
            $userOwns = false;
            
            // Check by user_id first (newer models)
            if (isset($surat->user_id) && $surat->user_id == $user->id) {
                $userOwns = true;
            }
            // Check by NIK (older models) - only if user has NIK
            elseif (isset($surat->nik) && $user->nik && $surat->nik == $user->nik) {
                $userOwns = true;
            }
            
            if (!$userOwns) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke surat ini.');
            }
            
            // Get current status - check both status fields
            $currentStatus = '';
            if (isset($surat->status)) {
                $currentStatus = $surat->status;
            } elseif (isset($surat->status_pengajuan)) {
                $currentStatus = $surat->status_pengajuan;
            } else {
                $currentStatus = 'menunggu';
            }
            
            $statusLower = strtolower($currentStatus);
            
            if (str_contains($statusLower, 'sudah diverifikasi')) {
                return redirect()->back()->with('error', 'Surat sudah dalam status diverifikasi.');
            }
            
            if (str_contains($statusLower, 'ditolak')) {
                return redirect()->back()->with('error', 'Surat yang ditolak tidak dapat diubah statusnya.');
            }
            
            // Update status to verified - prioritize 'status' field over 'status_pengajuan'
            if (isset($surat->status)) {
                $surat->status = 'sudah diverifikasi';
            } elseif (isset($surat->status_pengajuan)) {
                $surat->status_pengajuan = 'sudah diverifikasi';
            }
            
            // Add verification note if field exists
            if (isset($surat->catatan_verifikasi)) {
                $surat->catatan_verifikasi = 'Surat ditandai sebagai sudah diverifikasi oleh user';
            }
            
            // Update verification stages to 100% if exists
            if (isset($surat->tahapan_verifikasi) && $surat->tahapan_verifikasi) {
                $stages = is_string($surat->tahapan_verifikasi) ? json_decode($surat->tahapan_verifikasi, true) : $surat->tahapan_verifikasi;
                
                if (is_array($stages)) {
                    foreach ($stages as $key => $stage) {
                        $stages[$key]['status'] = 'completed';
                        $stages[$key]['completed_at'] = now()->toDateTimeString();
                    }
                    $surat->tahapan_verifikasi = json_encode($stages);
                }
            }
            
            $surat->save();
            
            Log::info('User completed surat manually', [
                'user_id' => $user->id,
                'user_nik' => $user->nik,
                'surat_type' => $type,
                'surat_id' => $id,
                'old_status' => $currentStatus,
                'new_status' => 'sudah diverifikasi'
            ]);
            
            return redirect()->back()->with('success', 'Status surat berhasil diubah menjadi "Sudah Diverifikasi". Anda sekarang dapat mendownload PDF surat.');
            
        } catch (\Exception $e) {
            Log::error('Error completing surat manually: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'surat_type' => $type,
                'surat_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah status surat.');
        }
    }
}
