<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VerificationStageService;
use App\Services\NotificationService;
use App\Models\Domisili;
use App\Models\TidakMampu;
use App\Models\BelumMenikah;
use App\Models\SuratKtp;
use App\Models\SuratKk;
use App\Models\SuratSkck;
use App\Models\SuratKematian;
use App\Models\SuratKelahiran;
use App\Models\SuratUsaha;
use App\Models\SuratKehilangan;

class VerificationController extends Controller
{
    public function index()
    {
        // Get all surat that need verification (diproses and selesai diproses status)
        $domisiliSurat = Domisili::whereIn('status', ['diproses', 'selesai diproses'])->get();
        $tidakMampuSurat = TidakMampu::whereIn('status', ['diproses', 'selesai diproses'])->get();
        $belumMenikahSurat = BelumMenikah::whereIn('status', ['diproses', 'selesai diproses'])->get();
        
        // Get KTP surat
        $ktpSurat = collect();
        if (class_exists('App\\Models\\SuratKtp')) {
            $ktpSurat = \App\Models\SuratKtp::whereIn('status', ['diproses', 'selesai diproses'])->get();
        }
        
        // Get KK surat
        $kkSurat = collect();
        if (class_exists('App\\Models\\SuratKk')) {
            $kkSurat = \App\Models\SuratKk::whereIn('status', ['diproses', 'selesai diproses'])->get();
        }
        
        // Get SKCK surat
        $skckSurat = collect();
        if (class_exists('App\\Models\\SuratSkck')) {
            $skckSurat = \App\Models\SuratSkck::whereIn('status', ['diproses', 'selesai diproses'])->get();
        }
        
        // Get Kematian surat
        $kematianSurat = collect();
        if (class_exists('App\\Models\\SuratKematian')) {
            $kematianSurat = \App\Models\SuratKematian::whereIn('status', ['diproses', 'selesai diproses'])->get();
        }
        
        // Get Kelahiran surat
        $kelahiranSurat = collect();
        if (class_exists('App\\Models\\SuratKelahiran')) {
            $kelahiranSurat = \App\Models\SuratKelahiran::whereNotIn('status', ['sudah diverifikasi', 'ditolak'])->get();
        }
        
        // Get Usaha surat
        $usahaSurat = collect();
        if (class_exists('App\\Models\\SuratUsaha')) {
            $usahaSurat = \App\Models\SuratUsaha::whereNotIn('status', ['sudah diverifikasi', 'ditolak'])->get();
        }
        
        // Get Kehilangan surat
        $kehilanganSurat = collect();
        if (class_exists('App\\Models\\SuratKehilangan')) {
            $kehilanganSurat = \App\Models\SuratKehilangan::whereNotIn('status', ['sudah diverifikasi', 'ditolak'])->get();
        }

        // Calculate statistics
        $totalPending = $domisiliSurat->count() + $tidakMampuSurat->count() + $belumMenikahSurat->count() + 
                       $ktpSurat->count() + $kkSurat->count() + $skckSurat->count() + 
                       $kematianSurat->count() + $kelahiranSurat->count() + $usahaSurat->count() + $kehilanganSurat->count();
        
        $totalDomisili = $domisiliSurat->count();
        $totalTidakMampu = $tidakMampuSurat->count();
        $totalBelumMenikah = $belumMenikahSurat->count();
        $totalKtp = $ktpSurat->count();
        $totalKk = $kkSurat->count();
        $totalSkck = $skckSurat->count();
        $totalKematian = $kematianSurat->count();
        $totalKelahiran = $kelahiranSurat->count();
        $totalUsaha = $usahaSurat->count();
        $totalKehilangan = $kehilanganSurat->count();

        return view('admin.verification.index', compact(
            'domisiliSurat',
            'tidakMampuSurat', 
            'belumMenikahSurat',
            'ktpSurat',
            'kkSurat',
            'skckSurat',
            'kematianSurat',
            'kelahiranSurat',
            'usahaSurat',
            'kehilanganSurat',
            'totalPending',
            'totalDomisili',
            'totalTidakMampu',
            'totalBelumMenikah',
            'totalKtp',
            'totalKk',
            'totalSkck',
            'totalKematian',
            'totalKelahiran',
            'totalUsaha',
            'totalKehilangan'
        ));
    }

    public function show($type, $id)
    {
        $surat = $this->getSuratByType($type, $id);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan');
        }

        // Initialize stages if not exists
        if (empty($surat->tahapan_verifikasi)) {
            $stages = VerificationStageService::initializeStages($type);
            $surat->tahapan_verifikasi = $stages;
            $surat->save();
        } else {
            $stages = $surat->tahapan_verifikasi;
            
            // Ensure stages is an array (handle JSON string from database)
            if (is_string($stages)) {
                $stages = json_decode($stages, true);
            }
            
            // If still not valid array, reinitialize
            if (!is_array($stages) || empty($stages)) {
                $stages = VerificationStageService::initializeStages($type);
                $surat->tahapan_verifikasi = $stages;
                $surat->save();
            }
        }

        $progress = VerificationStageService::getProgressPercentage($stages);
        $currentStage = VerificationStageService::getCurrentStage($stages);
        $estimatedCompletion = VerificationStageService::getEstimatedCompletion($stages) ?? now()->addDays(7);

        return view('admin.verification.show', compact(
            'surat', 'type', 'stages', 'progress', 'currentStage', 'estimatedCompletion'
        ));
    }

    public function showDomisili($id)
    {
        $surat = Domisili::find($id);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat Domisili tidak ditemukan');
        }

        // Initialize stages if not exists
        if (empty($surat->tahapan_verifikasi)) {
            $stages = VerificationStageService::initializeStages('domisili');
            $surat->tahapan_verifikasi = $stages;
            $surat->save();
        } else {
            $stages = $surat->tahapan_verifikasi;
            
            // Ensure stages is an array (handle JSON string from database)
            if (is_string($stages)) {
                $stages = json_decode($stages, true);
            }
            
            // If still not valid array, reinitialize
            if (!is_array($stages) || empty($stages)) {
                $stages = VerificationStageService::initializeStages('domisili');
                $surat->tahapan_verifikasi = $stages;
                $surat->save();
            }
        }

        // Calculate progress
        $totalStages = count($stages);
        $completedStages = collect($stages)->where('status', 'completed')->count();
        $inProgressStages = collect($stages)->where('status', 'in_progress')->count();
        $pendingStages = collect($stages)->where('status', 'pending')->count();
        $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;

        return view('admin.verification.domisili', compact(
            'surat', 
            'stages', 
            'progress', 
            'totalStages',
            'completedStages',
            'inProgressStages',
            'pendingStages'
        ));
    }

    public function showSkck($id)
    {
        $surat = \App\Models\SuratSkck::find($id);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat SKCK tidak ditemukan');
        }

        // Initialize stages if not exists
        if (empty($surat->tahapan_verifikasi)) {
            $stages = VerificationStageService::initializeStages('skck');
            $surat->tahapan_verifikasi = $stages;
            $surat->save();
        } else {
            $stages = $surat->tahapan_verifikasi;
            
            // Ensure stages is an array (handle JSON string from database)
            if (is_string($stages)) {
                $stages = json_decode($stages, true);
            }
            
            // If still not valid array, reinitialize
            if (!is_array($stages) || empty($stages)) {
                $stages = VerificationStageService::initializeStages('skck');
                $surat->tahapan_verifikasi = $stages;
                $surat->save();
            }
        }

        // Calculate progress
        $totalStages = count($stages);
        $completedStages = collect($stages)->where('status', 'completed')->count();
        $inProgressStages = collect($stages)->where('status', 'in_progress')->count();
        $pendingStages = collect($stages)->whereIn('status', ['pending', 'waiting'])->count();
        $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;

        return view('admin.verification.skck', compact(
            'surat', 
            'stages', 
            'progress', 
            'totalStages',
            'completedStages',
            'inProgressStages',
            'pendingStages'
        ));
    }

    public function showUsaha($id)
    {
        $surat = \App\Models\SuratUsaha::find($id);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat Usaha tidak ditemukan');
        }

        // Initialize stages if not exists
        if (empty($surat->tahapan_verifikasi)) {
            $stages = VerificationStageService::initializeStages('usaha');
            $surat->tahapan_verifikasi = $stages;
            $surat->save();
        } else {
            $stages = $surat->tahapan_verifikasi;
            
            // Ensure stages is an array (handle JSON string from database)
            if (is_string($stages)) {
                $stages = json_decode($stages, true);
            }
            
            // If still not valid array, reinitialize
            if (!is_array($stages) || empty($stages)) {
                $stages = VerificationStageService::initializeStages('usaha');
                $surat->tahapan_verifikasi = $stages;
                $surat->save();
            }
        }

        // Calculate progress
        $totalStages = count($stages);
        $completedStages = collect($stages)->where('status', 'completed')->count();
        $inProgressStages = collect($stages)->where('status', 'in_progress')->count();
        $pendingStages = collect($stages)->where('status', 'pending')->count();
        $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;

        return view('admin.verification.usaha', compact(
            'surat', 
            'stages', 
            'progress', 
            'totalStages',
            'completedStages',
            'inProgressStages',
            'pendingStages'
        ));
    }

    public function showKehilangan($id)
    {
        $surat = \App\Models\SuratKehilangan::find($id);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat Kehilangan tidak ditemukan');
        }

        // Initialize stages if not exists
        if (empty($surat->tahapan_verifikasi)) {
            $stages = VerificationStageService::initializeStages('kehilangan');
            $surat->tahapan_verifikasi = $stages;
            $surat->save();
        } else {
            $stages = $surat->tahapan_verifikasi;
            
            // Ensure stages is an array (handle JSON string from database)
            if (is_string($stages)) {
                $stages = json_decode($stages, true);
            }
            
            // If still not valid array, reinitialize
            if (!is_array($stages) || empty($stages)) {
                $stages = VerificationStageService::initializeStages('kehilangan');
                $surat->tahapan_verifikasi = $stages;
                $surat->save();
            }
        }

        // Calculate progress
        $totalStages = count($stages);
        $completedStages = collect($stages)->where('status', 'completed')->count();
        $inProgressStages = collect($stages)->where('status', 'in_progress')->count();
        $pendingStages = collect($stages)->where('status', 'pending')->count();
        $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;

        return view('admin.verification.kehilangan', compact(
            'surat', 
            'stages', 
            'progress', 
            'totalStages',
            'completedStages',
            'inProgressStages',
            'pendingStages'
        ));
    }

    public function showKematian($id)
    {
        $surat = \App\Models\SuratKematian::find($id);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat Kematian tidak ditemukan');
        }

        // Initialize stages if not exists
        if (empty($surat->tahapan_verifikasi)) {
            $stages = VerificationStageService::initializeStages('kematian');
            $surat->tahapan_verifikasi = $stages;
            $surat->save();
        } else {
            $stages = $surat->tahapan_verifikasi;
            
            // Ensure stages is an array (handle JSON string from database)
            if (is_string($stages)) {
                $stages = json_decode($stages, true);
            }
            
            // If still not valid array, reinitialize
            if (!is_array($stages) || empty($stages)) {
                $stages = VerificationStageService::initializeStages('kematian');
                $surat->tahapan_verifikasi = $stages;
                $surat->save();
            }
        }

        // Calculate progress
        $totalStages = count($stages);
        $completedStages = collect($stages)->where('status', 'completed')->count();
        $inProgressStages = collect($stages)->where('status', 'in_progress')->count();
        $pendingStages = collect($stages)->where('status', 'pending')->count();
        $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;

        return view('admin.verification.kematian', compact(
            'surat', 
            'stages', 
            'progress', 
            'totalStages',
            'completedStages',
            'inProgressStages',
            'pendingStages'
        ));
    }

    public function showKelahiran($id)
    {
        $surat = \App\Models\SuratKelahiran::find($id);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat Kelahiran tidak ditemukan');
        }

        // Initialize stages if not exists
        if (empty($surat->tahapan_verifikasi)) {
            $stages = VerificationStageService::initializeStages('kelahiran');
            $surat->tahapan_verifikasi = $stages;
            $surat->save();
        } else {
            $stages = $surat->tahapan_verifikasi;
            
            // Ensure stages is an array (handle JSON string from database)
            if (is_string($stages)) {
                $stages = json_decode($stages, true);
            }
            
            // If still not valid array, reinitialize
            if (!is_array($stages) || empty($stages)) {
                $stages = VerificationStageService::initializeStages('kelahiran');
                $surat->tahapan_verifikasi = $stages;
                $surat->save();
            }
        }

        // Calculate progress
        $totalStages = count($stages);
        $completedStages = collect($stages)->where('status', 'completed')->count();
        $inProgressStages = collect($stages)->where('status', 'in_progress')->count();
        $pendingStages = collect($stages)->where('status', 'pending')->count();
        $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;

        return view('admin.verification.kelahiran', compact(
            'surat', 
            'stages', 
            'progress', 
            'totalStages',
            'completedStages',
            'inProgressStages',
            'pendingStages'
        ));
    }

    public function showBelumMenikah($id)
    {
        $surat = BelumMenikah::find($id);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat Belum Menikah tidak ditemukan');
        }

        // Initialize stages if not exists
        if (empty($surat->tahapan_verifikasi)) {
            $stages = VerificationStageService::initializeStages('belum_menikah');
            $surat->tahapan_verifikasi = $stages;
            $surat->save();
        } else {
            $stages = $surat->tahapan_verifikasi;
            
            // Ensure stages is an array (handle JSON string from database)
            if (is_string($stages)) {
                $stages = json_decode($stages, true);
            }
            
            // If still not valid array, reinitialize
            if (!is_array($stages) || empty($stages)) {
                $stages = VerificationStageService::initializeStages('belum_menikah');
                $surat->tahapan_verifikasi = $stages;
                $surat->save();
            }
        }

        // Calculate progress
        $totalStages = count($stages);
        $completedStages = collect($stages)->where('status', 'completed')->count();
        $inProgressStages = collect($stages)->where('status', 'in_progress')->count();
        $pendingStages = collect($stages)->where('status', 'pending')->count();
        $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;

        return view('admin.verification.belum_menikah', compact(
            'surat', 
            'stages', 
            'progress', 
            'totalStages',
            'completedStages',
            'inProgressStages',
            'pendingStages'
        ));
    }

    public function showTidakMampu($id)
    {
        $surat = TidakMampu::find($id);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat Tidak Mampu tidak ditemukan');
        }

        // Initialize stages if not exists
        if (empty($surat->tahapan_verifikasi)) {
            $stages = VerificationStageService::initializeStages('tidak_mampu');
            $surat->tahapan_verifikasi = $stages;
            $surat->save();
        } else {
            $stages = $surat->tahapan_verifikasi;
            
            // Ensure stages is an array (handle JSON string from database)
            if (is_string($stages)) {
                $stages = json_decode($stages, true);
            }
            
            // If still not valid array, reinitialize
            if (!is_array($stages) || empty($stages)) {
                $stages = VerificationStageService::initializeStages('tidak_mampu');
                $surat->tahapan_verifikasi = $stages;
                $surat->save();
            }
        }

        // Calculate progress
        $totalStages = count($stages);
        $completedStages = collect($stages)->where('status', 'completed')->count();
        $inProgressStages = collect($stages)->where('status', 'in_progress')->count();
        $pendingStages = collect($stages)->where('status', 'pending')->count();
        $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;

        return view('admin.verification.tidak_mampu', compact(
            'surat', 
            'stages', 
            'progress', 
            'totalStages',
            'completedStages',
            'inProgressStages',
            'pendingStages'
        ));
    }

    public function updateStage(Request $request, $type, $id, $stageNumber)
    {
        // If called from specific route, override type parameter
        if (request()->routeIs('admin.verification.domisili.updateStage')) {
            $type = 'domisili';
        } elseif (request()->routeIs('admin.verification.belum_menikah.updateStage')) {
            $type = 'belum_menikah';
        } elseif (request()->routeIs('admin.verification.tidak_mampu.updateStage')) {
            $type = 'tidak_mampu';
        } elseif (request()->routeIs('admin.verification.skck.updateStage')) {
            $type = 'skck';
        } elseif (request()->routeIs('admin.verification.usaha.updateStage')) {
            $type = 'usaha';
        } elseif (request()->routeIs('admin.verification.kehilangan.updateStage')) {
            $type = 'kehilangan';
        } elseif (request()->routeIs('admin.verification.kematian.updateStage')) {
            $type = 'kematian';
        } elseif (request()->routeIs('admin.verification.kelahiran.updateStage')) {
            $type = 'kelahiran';
        }
        
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,rejected',
            'notes' => 'nullable|string|max:1000'
        ]);

        $surat = $this->getSuratByType($type, $id);
        
        if (!$surat) {
            return response()->json(['error' => 'Surat tidak ditemukan'], 404);
        }

        $stages = $surat->tahapan_verifikasi ?? [];
        
        // Ensure stages is an array (handle JSON string from database)
        if (is_string($stages)) {
            $stages = json_decode($stages, true);
        }
        
        if (!is_array($stages)) {
            $stages = [];
        }
        
        $stages = VerificationStageService::updateStageStatus(
            $stages, 
            (int)$stageNumber, 
            $request->status, 
            $request->notes,
            auth()->user()->name
        );

        $surat->tahapan_verifikasi = $stages;
        $surat->tanggal_verifikasi_terakhir = now();
        
        // Update overall status based on stages
        $previousStatus = $surat->status;
        if ($request->status === 'rejected') {
            $surat->status = 'ditolak';
        } elseif (VerificationStageService::getProgressPercentage($stages) >= 100) {
            $surat->status = 'selesai diproses';
        } else {
            $surat->status = 'diproses';
        }

        $surat->save();

        // Check if surat just got verified (100% complete)
        $currentProgress = VerificationStageService::getProgressPercentage($stages);
        $namaPemohon = $this->getNamaPemohon($surat, $type);
        
        // Create notifications based on status changes
        if ($request->status === 'rejected') {
            // Notifikasi surat ditolak - khusus untuk kematian
            if ($type === 'kematian') {
                $namaAlmarhum = $surat->nama_almarhum ?? 'Almarhum';
                NotificationService::createKematianDitolakNotification(
                    $surat->user_id,
                    $surat->id,
                    $namaPemohon,
                    $namaAlmarhum,
                    $request->notes
                );
            } else {
                NotificationService::createSuratDitolakNotification(
                    $surat->user_id,
                    $surat->id,
                    $type,
                    $namaPemohon,
                    $request->notes
                );
            }
        } elseif ($currentProgress >= 100 && $previousStatus !== 'selesai diproses') {
            // Notifikasi surat selesai diproses (semua tahapan selesai) - khusus untuk kematian
            if ($type === 'kematian') {
                $namaAlmarhum = $surat->nama_almarhum ?? 'Almarhum';
                NotificationService::createKematianApprovedNotification(
                    $surat->user_id,
                    $surat->id,
                    $namaPemohon,
                    $namaAlmarhum
                );
            } else {
                NotificationService::createSuratSelesaiNotification(
                    $surat->user_id,
                    $surat->id,
                    $type,
                    $namaPemohon
                );
            }
        } elseif ($surat->status === 'diproses') {
            // Notifikasi tahapan verifikasi - khusus untuk kematian
            if ($type === 'kematian') {
                $namaAlmarhum = $surat->nama_almarhum ?? 'Almarhum';
                NotificationService::createKematianVerifikasiNotification(
                    $surat->user_id,
                    $surat->id,
                    $namaPemohon,
                    $namaAlmarhum,
                    (int)$stageNumber
                );
            } elseif ($previousStatus !== 'diproses') {
                // Notifikasi surat sedang diproses (baru pertama kali masuk tahap proses)
                NotificationService::createSuratDiprosesNotification(
                    $surat->user_id,
                    $surat->id,
                    $type,
                    $namaPemohon
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Tahapan verifikasi berhasil diperbarui',
            'progress' => VerificationStageService::getProgressPercentage($stages),
            'currentStage' => VerificationStageService::getCurrentStage($stages)
        ]);
    }

    public function addNote(Request $request, $type, $id)
    {
        $request->validate([
            'note' => 'required|string|max:1000'
        ]);

        $surat = $this->getSuratByType($type, $id);
        
        if (!$surat) {
            return response()->json(['error' => 'Surat tidak ditemukan'], 404);
        }

        $existingNotes = $surat->catatan_verifikasi ?? '';
        $newNote = '[' . now()->format('d/m/Y H:i') . '] ' . auth()->user()->name . ': ' . $request->note;
        
        $surat->catatan_verifikasi = $existingNotes ? $existingNotes . "\n" . $newNote : $newNote;
        $surat->save();

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil ditambahkan',
            'note' => $newNote
        ]);
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
            case 'ktp':
                return \App\Models\SuratKtp::find($id);
            case 'kk':
                return \App\Models\SuratKk::find($id);
            case 'skck':
                return \App\Models\SuratSkck::find($id);
            case 'kematian':
                return \App\Models\SuratKematian::find($id);
            case 'kelahiran':
                return \App\Models\SuratKelahiran::find($id);
            case 'usaha':
                return \App\Models\SuratUsaha::find($id);
            case 'kehilangan':
                return \App\Models\SuratKehilangan::find($id);
            default:
                // Fallback: try to find in general Surat model
                return \App\Models\Surat::where('id', $id)
                                         ->where('jenis_surat', $type)
                                         ->first();
        }
    }

    /**
     * Get nama pemohon berdasarkan tipe surat
     */
    private function getNamaPemohon($surat, $type)
    {
        switch ($type) {
            case 'domisili':
            case 'tidak_mampu':
            case 'belum_menikah':
                return $surat->nama ?? 'Pemohon';
            case 'ktp':
            case 'kk':
            case 'skck':
            case 'usaha':
            case 'kehilangan':
                return $surat->nama_lengkap ?? 'Pemohon';
            case 'kematian':
                return $surat->nama_pelapor ?? 'Pelapor';
            case 'kelahiran':
                return $surat->nama_pelapor ?? 'Pelapor';
            default:
                return $surat->nama ?? $surat->nama_lengkap ?? $surat->nama_pemohon ?? 'Pemohon';
        }
    }
}
