<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\BelumMenikah;
use App\Models\TidakMampu;
use App\Models\SuratSkck;
use App\Models\SuratKk;
use App\Models\SuratKematian;
use App\Models\SuratKelahiran;
use App\Services\VerificationStageService;
use App\Services\NotificationService;

class SuratController extends Controller
{
    // Minimal controller for admin routes

    public function index(Request $request)
    {
        // Get filter parameters
        $jenisFilter = $request->get('jenis_surat', '');
        $perPage = $request->get('per_page', 20); // Default 20 items per page
        $search = $request->get('search', '');
        
        // Validate per_page
        if (!in_array($perPage, [10, 20, 50])) {
            $perPage = 20;
        }
        
        // Ambil semua surat dari berbagai jenis
        $suratList = collect();
        
        // Helper function to add surat to list
        $addToList = function($items, $jenis, $namaField) use (&$suratList) {
            foreach ($items as $item) {
                $suratList->push((object)[
                    'id' => $item->id,
                    'nama_pemohon' => $item->$namaField ?? '-',
                    'nama_lengkap' => $item->nama_lengkap ?? $item->$namaField ?? '-',
                    'nama' => $item->nama ?? $item->$namaField ?? '-',
                    'jenis_surat' => $jenis,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'status' => $item->status_pengajuan ?? $item->status ?? 'menunggu',
                    'tahapan_verifikasi' => $item->tahapan_verifikasi ?? null,
                    'nik' => $item->nik ?? null,
                ]);
            }
        };
        
        // Surat Domisili
        if (empty($jenisFilter) || $jenisFilter === 'domisili') {
            $domisili = \App\Models\Domisili::all();
            $addToList($domisili, 'domisili', 'nama');
        }
        
        // Surat KTP
        if (empty($jenisFilter) || $jenisFilter === 'ktp') {
            $ktp = \App\Models\SuratKtp::all();
            $addToList($ktp, 'ktp', 'nama_lengkap');
        }

        // Surat Belum Menikah
        if (empty($jenisFilter) || $jenisFilter === 'belum_menikah') {
            $belumMenikah = \App\Models\BelumMenikah::all();
            $addToList($belumMenikah, 'belum_menikah', 'nama');
        }

        // Surat Tidak Mampu
        if (empty($jenisFilter) || $jenisFilter === 'tidak_mampu') {
            $tidakMampu = \App\Models\TidakMampu::all();
            $addToList($tidakMampu, 'tidak_mampu', 'nama');
        }

        // Surat SKCK
        if (empty($jenisFilter) || $jenisFilter === 'skck') {
            $skck = SuratSkck::all();
            $addToList($skck, 'skck', 'nama_lengkap');
        }

        // Surat KK
        if ((empty($jenisFilter) || $jenisFilter === 'kk') && class_exists('App\\Models\\SuratKk')) {
            $kk = SuratKk::all();
            $addToList($kk, 'kk', 'nama_lengkap');
        }

        // Surat Kematian
        if ((empty($jenisFilter) || $jenisFilter === 'kematian') && class_exists('App\\Models\\SuratKematian')) {
            $kematian = SuratKematian::all();
            $addToList($kematian, 'kematian', 'nama_pelapor');
        }

        // Surat Kelahiran
        if ((empty($jenisFilter) || $jenisFilter === 'kelahiran') && class_exists('App\\Models\\SuratKelahiran')) {
            $kelahiran = SuratKelahiran::all();
            $addToList($kelahiran, 'kelahiran', 'nama_pelapor');
        }

        // Surat Usaha
        if ((empty($jenisFilter) || $jenisFilter === 'usaha') && class_exists('App\\Models\\SuratUsaha')) {
            $usaha = \App\Models\SuratUsaha::all();
            $addToList($usaha, 'usaha', 'nama_lengkap');
        }

        // Surat Kehilangan
        if ((empty($jenisFilter) || $jenisFilter === 'kehilangan') && class_exists('App\\Models\\SuratKehilangan')) {
            $kehilangan = \App\Models\SuratKehilangan::all();
            $addToList($kehilangan, 'kehilangan', 'nama_lengkap');
        }
        
        // Surat lain (jika ada model Surat dan tabel surat)
        try {
            if ((empty($jenisFilter) || !in_array($jenisFilter, ['domisili', 'ktp', 'belum_menikah', 'tidak_mampu', 'skck', 'kk', 'kematian', 'kelahiran', 'usaha', 'kehilangan'])) && class_exists('App\\Models\\Surat')) {
                // Check if table exists before querying
                if (\Illuminate\Support\Facades\Schema::hasTable('surat')) {
                    $surat = \App\Models\Surat::all();
                    foreach ($surat as $item) {
                        if (empty($jenisFilter) || $jenisFilter === $item->jenis_surat) {
                            $suratList->push((object)[
                                'id' => $item->id,
                                'nama_pemohon' => $item->nama_pemohon ?? $item->nama ?? '-',
                                'jenis_surat' => $item->jenis_surat,
                                'created_at' => $item->created_at,
                                'updated_at' => $item->updated_at,
                                'status' => $item->status ?? '-',
                            ]);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Ignore error if table doesn't exist
        }
        
        // Apply search filter
        if (!empty($search)) {
            $searchTerm = trim($search);
            $suratList = $suratList->filter(function($item) use ($searchTerm) {
                // Search in nama fields
                $namaMatch = stripos($item->nama_pemohon ?? '', $searchTerm) !== false ||
                           stripos($item->nama_lengkap ?? '', $searchTerm) !== false ||
                           stripos($item->nama ?? '', $searchTerm) !== false;
                           
                // Search in other fields
                $jenisMatch = stripos($item->jenis_surat ?? '', $searchTerm) !== false;
                $statusMatch = stripos($item->status ?? '', $searchTerm) !== false;
                $nikMatch = stripos($item->nik ?? '', $searchTerm) !== false;
                
                return $namaMatch || $jenisMatch || $statusMatch || $nikMatch;
            });
        }
        
        // Sort by created_at desc
        $suratList = $suratList->sortByDesc('created_at');
        
        // Manual pagination
        $currentPage = max(1, (int)$request->get('page', 1));
        $total = $suratList->count();
        $lastPage = $total > 0 ? ceil($total / $perPage) : 1;
        
        // Ensure current page doesn't exceed last page
        if ($currentPage > $lastPage) {
            $currentPage = $lastPage;
        }
        
        $offset = ($currentPage - 1) * $perPage;
        $items = $suratList->slice($offset, $perPage)->values();
        
        $pagination = (object)[
            'current_page' => $currentPage,
            'per_page' => (int)$perPage,
            'total' => $total,
            'last_page' => $lastPage,
            'from' => $total > 0 ? $offset + 1 : 0,
            'to' => min($offset + $perPage, $total),
            'has_pages' => $total > $perPage,
            'first_page_url' => $request->url() . '?' . http_build_query(array_merge($request->except('page'), ['page' => 1])),
            'last_page_url' => $request->url() . '?' . http_build_query(array_merge($request->except('page'), ['page' => $lastPage])),
            'prev_page_url' => $currentPage > 1 ? $request->url() . '?' . http_build_query(array_merge($request->except('page'), ['page' => $currentPage - 1])) : null,
            'next_page_url' => $currentPage < $lastPage ? $request->url() . '?' . http_build_query(array_merge($request->except('page'), ['page' => $currentPage + 1])) : null,
            'path' => $request->url(),
        ];
        
        return view('admin.surat.index', compact('items', 'pagination', 'jenisFilter', 'perPage', 'search'));
    }

    public function create()
    {
        return view('admin.surat.create');
    }

    public function store(Request $request)
    {
        $jenis = $request->input('jenis_surat');
        
        switch($jenis) {
            case 'domisili':
                $validated = $request->validate([
                    'nama' => 'required|string|max:255',
                    'nik' => 'required|string|max:16',
                    'tempat_lahir' => 'required|string|max:255',
                    'tanggal_lahir' => 'required|date',
                    'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                    'agama' => 'required|string|max:255',
                    'status_perkawinan' => 'required|string|max:255',
                    'pekerjaan' => 'required|string|max:255',
                    'alamat' => 'required|string',
                    'keperluan' => 'required|string',
                ]);
                
                $validated['status_pengajuan'] = 'Menunggu Verifikasi';
                $validated['user_id'] = auth()->id();
                \App\Models\Domisili::create($validated);
                break;
                
            case 'belum_menikah':
                $validated = $request->validate([
                    'nama' => 'required|string|max:255',
                    'nik' => 'required|string|max:16',
                    'tempat_lahir' => 'required|string|max:255',
                    'tanggal_lahir' => 'required|date',
                    'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                    'agama' => 'required|string|max:255',
                    'pekerjaan' => 'required|string|max:255',
                    'kewarganegaraan' => 'required|string|max:255',
                    'alamat' => 'required|string',
                    'keperluan' => 'required|string',
                ]);
                
                $validated['status'] = 'pending';
                $validated['user_id'] = auth()->id();
                BelumMenikah::create($validated);
                break;
                
            case 'tidak_mampu':
                $validated = $request->validate([
                    'nama' => 'required|string|max:255',
                    'nik' => 'required|string|max:16',
                    'tempat_lahir' => 'required|string|max:255',
                    'tanggal_lahir' => 'required|date',
                    'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                    'agama' => 'required|string|max:255',
                    'pekerjaan' => 'required|string|max:255',
                    'status' => 'required|string|max:255',
                    'alamat' => 'required|string',
                    'keperluan' => 'required|string',
                ]);
                
                $validated['user_id'] = auth()->id();
                TidakMampu::create($validated);
                break;
                
            case 'ktp':
            case 'kk':
            case 'skck':
            case 'kematian':
            case 'kelahiran':
                // For these types, we'll store in a general Surat model or specific models if they exist
                $validated = $request->validate([
                    'nama' => 'required|string|max:255',
                    'nik' => 'required|string|max:16',
                    'tempat_lahir' => 'required|string|max:255',
                    'tanggal_lahir' => 'required|date',
                    'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                    'agama' => 'required|string|max:255',
                    'pekerjaan' => 'required|string|max:255',
                    'status' => 'nullable|string|max:255',
                    'alamat' => 'required|string',
                    'keperluan' => 'required|string',
                ]);
                
                // Try to use specific model first, then fallback to general Surat model
                if ($jenis === 'ktp' && class_exists('App\\Models\\SuratKtp')) {
                    $validated['nama_lengkap'] = $validated['nama'];
                    $validated['status_perkawinan'] = $validated['status'] ?? 'Belum Kawin';
                    unset($validated['nama'], $validated['status']);
                    $validated['status'] = 'Menunggu Verifikasi';
                    \App\Models\SuratKtp::create($validated);
                } else {
                    // Use general Surat model if it exists and table exists
                    try {
                        if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                            $validated['jenis_surat'] = $jenis;
                            $validated['nama_pemohon'] = $validated['nama'];
                            $validated['status'] = 'Menunggu Verifikasi';
                            $validated['user_id'] = auth()->id();
                            \App\Models\Surat::create($validated);
                        }
                    } catch (\Exception $e) {
                        // Handle error if table doesn't exist
                        return redirect()->back()->with('error', 'Tabel surat tidak ditemukan. Hubungi administrator.');
                    }
                }
                break;
                
            default:
                return redirect()->back()->with('error', 'Jenis surat tidak valid.');
        }
        
        return redirect()->route('surat.index')->with('success', 'Surat ' . ucwords(str_replace('_', ' ', $jenis)) . ' berhasil ditambahkan.');
    }

    public function show($id, Request $request)
    {
        // Find and show specific surat
        $surat = null;
        $jenis = $request->query('jenis'); // Get jenis from query parameter
        
        // If jenis is provided, search in specific model first
        if ($jenis) {
            switch ($jenis) {
                case 'domisili':
                    $surat = \App\Models\Domisili::find($id);
                    if ($surat) $jenis = 'domisili';
                    break;
                case 'ktp':
                    if (class_exists('App\\Models\\SuratKtp')) {
                        $surat = \App\Models\SuratKtp::find($id);
                        if ($surat) $jenis = 'ktp';
                    }
                    break;
                case 'skck':
                    $surat = SuratSkck::find($id);
                    if ($surat) $jenis = 'skck';
                    break;
                case 'kk':
                    if (class_exists('App\\Models\\SuratKk')) {
                        $surat = SuratKk::find($id);
                        if ($surat) $jenis = 'kk';
                    }
                    break;
                case 'kematian':
                    if (class_exists('App\\Models\\SuratKematian')) {
                        $surat = SuratKematian::find($id);
                        if ($surat) $jenis = 'kematian';
                    }
                    break;
                case 'kelahiran':
                    if (class_exists('App\\Models\\SuratKelahiran')) {
                        $surat = SuratKelahiran::find($id);
                        if ($surat) $jenis = 'kelahiran';
                    }
                    break;
                case 'belum_menikah':
                    $surat = \App\Models\BelumMenikah::find($id);
                    if ($surat) $jenis = 'belum_menikah';
                    break;
                case 'tidak_mampu':
                    $surat = \App\Models\TidakMampu::find($id);
                    if ($surat) $jenis = 'tidak_mampu';
                    break;
                case 'usaha':
                    if (class_exists('App\\Models\\SuratUsaha')) {
                        $surat = \App\Models\SuratUsaha::find($id);
                        if ($surat) $jenis = 'usaha';
                    }
                    break;
                case 'kehilangan':
                    if (class_exists('App\\Models\\SuratKehilangan')) {
                        $surat = \App\Models\SuratKehilangan::find($id);
                        if ($surat) $jenis = 'kehilangan';
                    }
                    break;
            }
        }
        
        // Fallback: Try to find in all models if not found or jenis not provided
        if (!$surat) {
            // Try to find in Domisili
            $domisili = \App\Models\Domisili::find($id);
            if ($domisili) {
                $surat = $domisili;
                $jenis = 'domisili';
            }

            // Try to find in SuratKtp
            if (!$surat && class_exists('App\\Models\\SuratKtp')) {
                $ktp = \App\Models\SuratKtp::find($id);
                if ($ktp) {
                    $surat = $ktp;
                    $jenis = 'ktp';
                }
            }

            // Try to find in SuratSkck
            if (!$surat) {
                $skck = SuratSkck::find($id);
                if ($skck) {
                    $surat = $skck;
                    $jenis = 'skck';
                }
            }

            // Try to find in SuratKk
            if (!$surat && class_exists('App\\Models\\SuratKk')) {
                $kk = SuratKk::find($id);
                if ($kk) {
                    $surat = $kk;
                    $jenis = 'kk';
                }
            }

            // Try to find in SuratKematian
            if (!$surat && class_exists('App\\Models\\SuratKematian')) {
                $kematian = SuratKematian::find($id);
                if ($kematian) {
                    $surat = $kematian;
                    $jenis = 'kematian';
                }
            }

            // Try to find in SuratKelahiran
            if (!$surat && class_exists('App\\Models\\SuratKelahiran')) {
                $kelahiran = SuratKelahiran::find($id);
                if ($kelahiran) {
                    $surat = $kelahiran;
                    $jenis = 'kelahiran';
                }
            }

            // Try to find in BelumMenikah
            if (!$surat) {
                $belumMenikah = \App\Models\BelumMenikah::find($id);
                if ($belumMenikah) {
                    $surat = $belumMenikah;
                    $jenis = 'belum_menikah';
                }
            }

            // Try to find in TidakMampu
            if (!$surat) {
                $tidakMampu = \App\Models\TidakMampu::find($id);
                if ($tidakMampu) {
                    $surat = $tidakMampu;
                    $jenis = 'tidak_mampu';
                }
            }

            // Try to find in SuratUsaha
            if (!$surat && class_exists('App\\Models\\SuratUsaha')) {
                $usaha = \App\Models\SuratUsaha::find($id);
                if ($usaha) {
                    $surat = $usaha;
                    $jenis = 'usaha';
                }
            }

            // Try to find in SuratKehilangan
            if (!$surat && class_exists('App\\Models\\SuratKehilangan')) {
                $kehilangan = \App\Models\SuratKehilangan::find($id);
                if ($kehilangan) {
                    $surat = $kehilangan;
                    $jenis = 'kehilangan';
                }
            }

            // Try to find in Surat (general) - only if table exists
            if (!$surat) {
                try {
                    if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                        $suratGeneral = \App\Models\Surat::find($id);
                        if ($suratGeneral) {
                            $surat = $suratGeneral;
                            $jenis = $suratGeneral->jenis_surat;
                        }
                    }
                } catch (\Exception $e) {
                    // Ignore error if table doesn't exist
                }
            }
        }

        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan.');
        }

        return view('admin.surat.show', compact('surat', 'jenis'));
    }

    // Handle KTP submission
    public function ktpSubmit(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'status_perkawinan' => 'required|string',
            'nik' => 'required|string|size:16',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'pekerjaan' => 'required|string',
            'alamat' => 'required|string',
            'keperluan' => 'required|string',
        ]);

        $validated['user_id'] = auth()->id();

        \App\Models\SuratKtp::create($validated + [
            'status' => 'menunggu'
        ]);

        return redirect()->back()->with('success', 'Pengajuan surat KTP berhasil disimpan.');
    }

    // Handle KK submission
    public function kkSubmit(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'status_perkawinan' => 'required|string',
            'nik' => 'required|string|size:16',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'kewarganegaraan' => 'required|string',
            'pekerjaan' => 'required|string',
            'alamat' => 'required|string',
            'keperluan' => 'required|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'menunggu';
        
        \App\Models\SuratKk::create($validated);

        return redirect()->back()->with('success', 'Pengajuan surat KK berhasil disimpan.');
    }

    // Handle SKCK submission
    public function skckSubmit(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'status_perkawinan' => 'required|in:Kawin,Belum Kawin,Cerai,Mati',
            'kewarganegaraan' => 'required|in:WNI,WNA',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'keperluan' => 'required|string|max:255',
        ]);

        try {
            // Cek apakah user sudah login
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengajukan permohonan surat.');
            }

            // Cek apakah sudah ada permohonan yang masih pending
            $existingRequest = SuratSkck::where('user_id', auth()->id())
                ->where('status', 'menunggu')
                ->first();

            if ($existingRequest) {
                return redirect()->back()->with('error', 'Anda masih memiliki permohonan SKCK yang sedang diproses. Silakan tunggu hingga selesai sebelum mengajukan permohonan baru.');
            }

            $validated['user_id'] = auth()->id();
            $validated['status'] = 'menunggu';
            $validated['tahapan_verifikasi'] = [
                'rt' => ['status' => 'menunggu', 'tanggal' => null, 'catatan' => null],
                'rw' => ['status' => 'menunggu', 'tanggal' => null, 'catatan' => null],
                'kelurahan' => ['status' => 'menunggu', 'tanggal' => null, 'catatan' => null]
            ];
            
            SuratSkck::create($validated);

            return redirect()->back()->with('success', 'Permohonan Surat Pengantar SKCK berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    // Handle Kematian submission
    public function kematianSubmit(Request $request)
    {
        $validated = $request->validate([
            'nama_almarhum' => 'required|string|max:255',
            'nik_almarhum' => 'required|string|size:16',
            'tempat_lahir_almarhum' => 'required|string',
            'tanggal_lahir_almarhum' => 'required|date',
            'tanggal_kematian' => 'required|date',
            'tempat_kematian' => 'required|string',
            'sebab_kematian' => 'required|string',
            'nama_pelapor' => 'required|string',
            'nik_pelapor' => 'required|string|size:16',
            'hubungan_pelapor' => 'required|string',
            'alamat_pelapor' => 'required|string',
            'keperluan' => 'required|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'menunggu';
        
        \App\Models\SuratKematian::create($validated);

        return redirect()->back()->with('success', 'Pengajuan surat kematian berhasil disimpan.');
    }

    // Handle Kelahiran submission
    public function kelahiranSubmit(Request $request)
    {
        $validated = $request->validate([
            'nama_bayi' => 'required|string|max:255',
            'jenis_kelamin_bayi' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'waktu_lahir' => 'required',
            'nama_ayah' => 'required|string',
            'nik_ayah' => 'required|string|size:16',
            'nama_ibu' => 'required|string',
            'nik_ibu' => 'required|string|size:16',
            'alamat_orangtua' => 'required|string',
            'nama_pelapor' => 'required|string',
            'nik_pelapor' => 'required|string|size:16',
            'hubungan_pelapor' => 'required|string',
            'keperluan' => 'required|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'menunggu';
        
        \App\Models\SuratKelahiran::create($validated);

        return redirect()->back()->with('success', 'Pengajuan surat kelahiran berhasil disimpan.');
    }

    // Handle Kehilangan submission (if needed)
    public function kehilanganSubmit(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'pekerjaan' => 'required|string',
            'keperluan' => 'required|string',
            'barang_hilang' => 'required|string',
            'tempat_kehilangan' => 'required|string',
            'tanggal_kehilangan' => 'required|date',
        ]);

        // Use general Surat model for kehilangan - only if table exists
        try {
            if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                $surat = new \App\Models\Surat();
                $surat->nik = $validated['nik'];
                $surat->nama = $validated['nama_lengkap'];
                $surat->alamat = $validated['alamat'];
                $surat->jenis_surat = 'kehilangan';
                $surat->status = 'menunggu';
                $surat->catatan = json_encode($validated);
                $surat->save();
                
                return redirect()->back()->with('success', 'Pengajuan surat kehilangan berhasil disimpan.');
            } else {
                return redirect()->back()->with('error', 'Fitur surat kehilangan belum tersedia. Hubungi administrator.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function dataSurat(Request $request)
    {
        $jenis = $request->query('jenis');
        
        if ($jenis === 'domisili') {
            $data = \App\Models\Domisili::orderBy('created_at', 'desc')->get();
            $result = $data->map(function($item) {
                return [
                    'id' => $item->id,
                    'nik' => $item->nik,
                    'nama' => $item->nama,
                    'nama_pemohon' => $item->nama,
                    'tempat_lahir' => $item->tempat_lahir,
                    'tanggal_lahir' => $item->tanggal_lahir,
                    'jenis_kelamin' => $item->jenis_kelamin,
                    'kewarganegaraan' => $item->kewarganegaraan,
                    'agama' => $item->agama,
                    'status' => $item->status_pengajuan ?? $item->status ?? 'Menunggu Verifikasi',
                    'status_pengajuan' => $item->status_pengajuan ?? 'Menunggu Verifikasi',
                    'pekerjaan' => $item->pekerjaan,
                    'alamat' => $item->alamat,
                    'keperluan' => $item->keperluan,
                    'jenis_surat' => 'domisili',
                    'created_at' => $item->created_at,
                    'tanggal_pengajuan' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                    'tahapan_verifikasi' => $item->tahapan_verifikasi,
                ];
            });
            return response()->json($result, 200);
        } elseif ($jenis === 'ktp') {
            // Handle KTP data if exists
            if (class_exists('App\\Models\\SuratKtp')) {
                $data = \App\Models\SuratKtp::orderBy('created_at', 'desc')->get();
                $result = $data->map(function($item) {
                    return [
                        'id' => $item->id,
                        'nama_lengkap' => $item->nama_lengkap,
                        'nama_pemohon' => $item->nama_lengkap,
                        'nama' => $item->nama_lengkap,
                        'nik' => $item->nik,
                        'tempat_lahir' => $item->tempat_lahir,
                        'tanggal_lahir' => $item->tanggal_lahir,
                        'jenis_kelamin' => $item->jenis_kelamin,
                        'agama' => $item->agama,
                        'status_perkawinan' => $item->status_perkawinan,
                        'pekerjaan' => $item->pekerjaan,
                        'alamat' => $item->alamat,
                        'keperluan' => $item->keperluan,
                        'status' => $item->status ?? 'Menunggu Verifikasi',
                        'status_pengajuan' => $item->status ?? 'Menunggu Verifikasi',
                        'jenis_surat' => 'ktp',
                        'created_at' => $item->created_at,
                        'tanggal_pengajuan' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                        'tahapan_verifikasi' => $item->tahapan_verifikasi,
                    ];
                });
                return response()->json($result, 200);
            }
        } elseif ($jenis === 'kk') {
            // Handle KK data from database
            if (class_exists('App\\Models\\SuratKk')) {
                $data = SuratKk::orderBy('created_at', 'desc')->get();
                $result = $data->map(function($item) {
                    return [
                        'id' => $item->id,
                        'nama_pemohon' => $item->nama_lengkap,
                        'nama' => $item->nama_lengkap,
                        'nik' => $item->nik,
                        'tempat_lahir' => $item->tempat_lahir,
                        'tanggal_lahir' => $item->tanggal_lahir,
                        'jenis_kelamin' => $item->jenis_kelamin,
                        'agama' => $item->agama,
                        'status_perkawinan' => $item->status_perkawinan,
                        'kewarganegaraan' => $item->kewarganegaraan,
                        'pekerjaan' => $item->pekerjaan,
                        'alamat' => $item->alamat,
                        'keperluan' => $item->keperluan,
                        'status' => $item->status ?? 'menunggu',
                        'status_pengajuan' => $item->status ?? 'menunggu',
                        'jenis_surat' => 'kk',
                        'created_at' => $item->created_at,
                        'tanggal_pengajuan' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                        'tahapan_verifikasi' => $item->tahapan_verifikasi,
                    ];
                });
                return response()->json($result, 200);
            } else {
                // Fallback if model doesn't exist
                return response()->json([], 200);
            }
        } elseif ($jenis === 'skck') {
            // Handle SKCK data from database
            $data = SuratSkck::orderBy('created_at', 'desc')->get();
            $result = $data->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama_pemohon' => $item->nama_lengkap,
                    'nama' => $item->nama_lengkap,
                    'nik' => $item->nik,
                    'tempat_lahir' => $item->tempat_lahir,
                    'tanggal_lahir' => $item->tanggal_lahir,
                    'jenis_kelamin' => $item->jenis_kelamin,
                    'agama' => $item->agama,
                    'status_perkawinan' => $item->status_perkawinan,
                    'kewarganegaraan' => $item->kewarganegaraan,
                    'pekerjaan' => $item->pekerjaan,
                    'alamat' => $item->alamat,
                    'keperluan' => $item->keperluan,
                    'status' => $item->status ?? 'menunggu',
                    'status_pengajuan' => $item->status ?? 'menunggu',
                    'jenis_surat' => 'skck',
                    'created_at' => $item->created_at,
                    'tanggal_pengajuan' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                    'tahapan_verifikasi' => $item->tahapan_verifikasi,
                ];
            });
            return response()->json($result, 200);
        } elseif ($jenis === 'kematian') {
            // Handle Kematian data from database
            if (class_exists('App\\Models\\SuratKematian')) {
                $data = SuratKematian::orderBy('created_at', 'desc')->get();
                $result = $data->map(function($item) {
                    return [
                        'id' => $item->id,
                        'nama_pemohon' => $item->nama_pelapor,
                        'nama' => $item->nama_pelapor,
                        'nama_almarhum' => $item->nama_almarhum,
                        'nik' => $item->nik_pelapor,
                        'nik_almarhum' => $item->nik_almarhum,
                        'tempat_kematian' => $item->tempat_kematian,
                        'tanggal_kematian' => $item->tanggal_kematian,
                        'sebab_kematian' => $item->sebab_kematian,
                        'hubungan_pelapor' => $item->hubungan_pelapor,
                        'alamat_pelapor' => $item->alamat_pelapor,
                        'keperluan' => $item->keperluan,
                        'status' => $item->status ?? 'menunggu',
                        'status_pengajuan' => $item->status ?? 'menunggu',
                        'jenis_surat' => 'kematian',
                        'created_at' => $item->created_at,
                        'tanggal_pengajuan' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                        'tahapan_verifikasi' => $item->tahapan_verifikasi,
                    ];
                });
                return response()->json($result, 200);
            } else {
                // Fallback if model doesn't exist
                return response()->json([], 200);
            }
        } elseif ($jenis === 'belum_menikah') {
            // Handle Belum Menikah data
            $data = \App\Models\BelumMenikah::orderBy('created_at', 'desc')->get();
            $result = $data->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama_pemohon' => $item->nama,
                    'nama' => $item->nama,
                    'nik' => $item->nik,
                    'tempat_lahir' => $item->tempat_lahir,
                    'tanggal_lahir' => $item->tanggal_lahir,
                    'jenis_kelamin' => $item->jenis_kelamin,
                    'agama' => $item->agama,
                    'pekerjaan' => $item->pekerjaan,
                    'alamat' => $item->alamat,
                    'keperluan' => $item->keperluan,
                    'status' => $item->status ?? 'pending',
                    'status_pengajuan' => $item->status ?? 'pending',
                    'jenis_surat' => 'belum_menikah',
                    'created_at' => $item->created_at,
                    'tanggal_pengajuan' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                    'tahapan_verifikasi' => $item->tahapan_verifikasi,
                ];
            });
            return response()->json($result, 200);
        } elseif ($jenis === 'tidak_mampu') {
            // Handle Tidak Mampu data
            $data = \App\Models\TidakMampu::orderBy('created_at', 'desc')->get();
            $result = $data->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama_pemohon' => $item->nama,
                    'nama' => $item->nama,
                    'nik' => $item->nik,
                    'tempat_lahir' => $item->tempat_lahir,
                    'tanggal_lahir' => $item->tanggal_lahir,
                    'jenis_kelamin' => $item->jenis_kelamin,
                    'agama' => $item->agama,
                    'pekerjaan' => $item->pekerjaan,
                    'penghasilan' => $item->penghasilan,
                    'alamat' => $item->alamat,
                    'keperluan' => $item->keperluan,
                    'status' => $item->status ?? 'pending',
                    'status_pengajuan' => $item->status ?? 'pending',
                    'jenis_surat' => 'tidak_mampu',
                    'created_at' => $item->created_at,
                    'tanggal_pengajuan' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                    'tahapan_verifikasi' => $item->tahapan_verifikasi,
                ];
            });
            return response()->json($result, 200);
        } elseif ($jenis === 'kelahiran') {
            // Handle Kelahiran data from database
            if (class_exists('App\\Models\\SuratKelahiran')) {
                $data = SuratKelahiran::orderBy('created_at', 'desc')->get();
                $result = $data->map(function($item) {
                    return [
                        'id' => $item->id,
                        'nama_pemohon' => $item->nama_pelapor,
                        'nama' => $item->nama_pelapor,
                        'nama_bayi' => $item->nama_bayi,
                        'jenis_kelamin_bayi' => $item->jenis_kelamin_bayi,
                        'tempat_lahir' => $item->tempat_lahir,
                        'tanggal_lahir' => $item->tanggal_lahir,
                        'waktu_lahir' => $item->waktu_lahir,
                        'nama_ayah' => $item->nama_ayah,
                        'nik_ayah' => $item->nik_ayah,
                        'nama_ibu' => $item->nama_ibu,
                        'nik_ibu' => $item->nik_ibu,
                        'alamat_orangtua' => $item->alamat_orangtua,
                        'nik' => $item->nik_pelapor,
                        'hubungan_pelapor' => $item->hubungan_pelapor,
                        'keperluan' => $item->keperluan,
                        'status' => $item->status ?? 'menunggu',
                        'status_pengajuan' => $item->status ?? 'menunggu',
                        'jenis_surat' => 'kelahiran',
                        'created_at' => $item->created_at,
                        'tanggal_pengajuan' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                        'tahapan_verifikasi' => $item->tahapan_verifikasi,
                    ];
                });
                return response()->json($result, 200);
            } else {
                // Fallback if model doesn't exist
                return response()->json([], 200);
            }
        } elseif ($jenis === 'usaha') {
            // Handle Usaha data from database
            if (class_exists('App\\Models\\SuratUsaha')) {
                $data = \App\Models\SuratUsaha::orderBy('created_at', 'desc')->get();
                $result = $data->map(function($item) {
                    return [
                        'id' => $item->id,
                        'nama_pemohon' => $item->nama_lengkap,
                        'nama' => $item->nama_lengkap,
                        'nik' => $item->nik,
                        'tempat_lahir' => $item->tempat_lahir,
                        'tanggal_lahir' => $item->tanggal_lahir,
                        'jenis_kelamin' => $item->jenis_kelamin,
                        'agama' => $item->agama,
                        'pekerjaan' => $item->pekerjaan,
                        'alamat' => $item->alamat,
                        'nama_usaha' => $item->nama_usaha,
                        'jenis_usaha' => $item->jenis_usaha,
                        'alamat_usaha' => $item->alamat_usaha,
                        'tanggal_mulai_usaha' => $item->tanggal_mulai_usaha,
                        'modal_usaha' => $item->modal_usaha,
                        'deskripsi_usaha' => $item->deskripsi_usaha,
                        'keperluan' => $item->keperluan,
                        'status' => $item->status ?? 'menunggu',
                        'status_pengajuan' => $item->status ?? 'menunggu',
                        'jenis_surat' => 'usaha',
                        'created_at' => $item->created_at,
                        'tanggal_pengajuan' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                        'tahapan_verifikasi' => $item->tahapan_verifikasi,
                    ];
                });
                return response()->json($result, 200);
            } else {
                // Fallback if model doesn't exist
                return response()->json([], 200);
            }
        } elseif ($jenis === 'kehilangan') {
            // Handle Kehilangan data from database
            if (class_exists('App\\Models\\SuratKehilangan')) {
                $data = \App\Models\SuratKehilangan::orderBy('created_at', 'desc')->get();
                $result = $data->map(function($item) {
                    return [
                        'id' => $item->id,
                        'nama_pemohon' => $item->nama_lengkap,
                        'nama' => $item->nama_lengkap,
                        'nik' => $item->nik,
                        'tempat_lahir' => $item->tempat_lahir,
                        'tanggal_lahir' => $item->tanggal_lahir,
                        'jenis_kelamin' => $item->jenis_kelamin,
                        'agama' => $item->agama,
                        'pekerjaan' => $item->pekerjaan,
                        'alamat' => $item->alamat,
                        'barang_hilang' => $item->barang_hilang,
                        'deskripsi_barang' => $item->deskripsi_barang,
                        'tempat_kehilangan' => $item->tempat_kehilangan,
                        'tanggal_kehilangan' => $item->tanggal_kehilangan,
                        'waktu_kehilangan' => $item->waktu_kehilangan,
                        'kronologi_kehilangan' => $item->kronologi_kehilangan,
                        'no_laporan_polisi' => $item->no_laporan_polisi,
                        'tanggal_laporan_polisi' => $item->tanggal_laporan_polisi,
                        'keperluan' => $item->keperluan,
                        'status' => $item->status ?? 'menunggu',
                        'status_pengajuan' => $item->status ?? 'menunggu',
                        'jenis_surat' => 'kehilangan',
                        'created_at' => $item->created_at,
                        'tanggal_pengajuan' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                        'tahapan_verifikasi' => $item->tahapan_verifikasi,
                    ];
                });
                return response()->json($result, 200);
            } else {
                // Fallback if model doesn't exist
                return response()->json([], 200);
            }
        } elseif ($jenis && class_exists('App\\Models\\Surat')) {
            // Handle other surat types using general Surat model - only if table exists
            try {
                if (Schema::hasTable('surat')) {
                    $data = \App\Models\Surat::where('jenis_surat', $jenis)->orderBy('created_at', 'desc')->get();
                    $result = $data->map(function($item) {
                        return [
                            'id' => $item->id,
                            'nama_pemohon' => $item->nama_pemohon ?? $item->nama ?? '-',
                            'jenis_surat' => $item->jenis_surat,
                            'tanggal_pengajuan' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                            'status' => $item->status ?? '-',
                        ];
                    });
                    return response()->json($result, 200);
                }
            } catch (\Exception $e) {
                // Ignore error if table doesn't exist
                return response()->json([], 200);
            }
        }
        
        // Return empty array if no valid jenis or no data found
        return response()->json([], 200);
    }

    public function edit($id)
    {
        // For now, we'll handle different types based on what exists
        
        // Try to find in Domisili
        $domisili = \App\Models\Domisili::find($id);
        if ($domisili) {
            return view('admin.surat.edit', [
                'surat' => $domisili,
                'jenis' => 'domisili'
            ]);
        }

        // Try to find in SuratKtp
        if (class_exists('App\\Models\\SuratKtp')) {
            $ktp = \App\Models\SuratKtp::find($id);
            if ($ktp) {
                return view('admin.surat.edit', [
                    'surat' => $ktp,
                    'jenis' => 'ktp'
                ]);
            }
        }

        // Try to find in SuratSkck
        $skck = SuratSkck::find($id);
        if ($skck) {
            return view('admin.surat.edit', [
                'surat' => $skck,
                'jenis' => 'skck'
            ]);
        }

        // Try to find in SuratKk
        if (class_exists('App\\Models\\SuratKk')) {
            $kk = SuratKk::find($id);
            if ($kk) {
                return view('admin.surat.edit', [
                    'surat' => $kk,
                    'jenis' => 'kk'
                ]);
            }
        }

        // Try to find in SuratUsaha
        if (class_exists('App\\Models\\SuratUsaha')) {
            $usaha = \App\Models\SuratUsaha::find($id);
            if ($usaha) {
                return view('admin.surat.edit', [
                    'surat' => $usaha,
                    'jenis' => 'usaha'
                ]);
            }
        }

        // Try to find in SuratKematian
        if (class_exists('App\\Models\\SuratKematian')) {
            $kematian = SuratKematian::find($id);
            if ($kematian) {
                return view('admin.surat.edit', [
                    'surat' => $kematian,
                    'jenis' => 'kematian'
                ]);
            }
        }

        // Try to find in SuratKelahiran
        if (class_exists('App\\Models\\SuratKelahiran')) {
            $kelahiran = SuratKelahiran::find($id);
            if ($kelahiran) {
                return view('admin.surat.edit', [
                    'surat' => $kelahiran,
                    'jenis' => 'kelahiran'
                ]);
            }
        }

        // Try to find in BelumMenikah
        $belumMenikah = \App\Models\BelumMenikah::find($id);
        if ($belumMenikah) {
            return view('admin.surat.edit', [
                'surat' => $belumMenikah,
                'jenis' => 'belum_menikah'
            ]);
        }

        // Try to find in TidakMampu
        $tidakMampu = \App\Models\TidakMampu::find($id);
        if ($tidakMampu) {
            return view('admin.surat.edit', [
                'surat' => $tidakMampu,
                'jenis' => 'tidak_mampu'
            ]);
        }

        // Try to find in Surat (general) - only if table exists
        try {
            if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                $surat = \App\Models\Surat::find($id);
                if ($surat) {
                    return view('admin.surat.edit', [
                        'surat' => $surat,
                        'jenis' => $surat->jenis_surat
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Ignore error if table doesn't exist
        }

        return redirect()->back()->with('error', 'Surat tidak ditemukan.');
    }

    public function update(Request $request, $id)
    {
        // Determine which model to update based on the data
        $jenis = $request->input('jenis_surat');
        
        if ($jenis === 'domisili') {
            // Validate domisili fields
            $request->validate([
                'nama' => 'required|string|max:255',
                'nik' => 'required|string|max:16',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'agama' => 'required|string|max:50',
                'kewarganegaraan' => 'required|in:WNI,WNA',
                'pekerjaan' => 'required|string|max:255',
                'alamat' => 'required|string',
                'keperluan' => 'required|string',
                // File validation
                'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'file_pengantar_rt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'file_dokumen_tambahan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
            
            $surat = \App\Models\Domisili::findOrFail($id);
            
            // Prepare data for update
            $updateData = $request->except(['jenis_surat', '_token', '_method', 'file_ktp', 'file_kk', 'file_pengantar_rt', 'file_dokumen_tambahan']);
            
            // Handle file uploads
            if ($request->hasFile('file_ktp')) {
                // Delete old file if exists
                if ($surat->file_ktp && Storage::disk('local')->exists($surat->file_ktp)) {
                    Storage::disk('local')->delete($surat->file_ktp);
                }
                $filePath = $request->file('file_ktp')->store('domisili/ktp');
                $updateData['file_ktp'] = $filePath;
            }
            
            if ($request->hasFile('file_kk')) {
                // Delete old file if exists
                if ($surat->file_kk && Storage::disk('local')->exists($surat->file_kk)) {
                    Storage::disk('local')->delete($surat->file_kk);
                }
                $filePath = $request->file('file_kk')->store('domisili/kk');
                $updateData['file_kk'] = $filePath;
            }
            
            if ($request->hasFile('file_pengantar_rt')) {
                // Delete old file if exists
                if ($surat->file_pengantar_rt && Storage::disk('local')->exists($surat->file_pengantar_rt)) {
                    Storage::disk('local')->delete($surat->file_pengantar_rt);
                }
                $filePath = $request->file('file_pengantar_rt')->store('domisili/pengantar_rt');
                $updateData['file_pengantar_rt'] = $filePath;
            }
            
            if ($request->hasFile('file_dokumen_tambahan')) {
                // Delete old file if exists
                if ($surat->file_dokumen_tambahan && Storage::disk('local')->exists($surat->file_dokumen_tambahan)) {
                    Storage::disk('local')->delete($surat->file_dokumen_tambahan);
                }
                $filePath = $request->file('file_dokumen_tambahan')->store('domisili/dokumen_tambahan');
                $updateData['file_dokumen_tambahan'] = $filePath;
            }
            
            $surat->update($updateData);
        } elseif ($jenis === 'ktp' && class_exists('App\\Models\\SuratKtp')) {
            // Validate KTP fields
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'nik' => 'required|string|max:16',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'keperluan' => 'required|string',
            ]);
            
            $surat = \App\Models\SuratKtp::findOrFail($id);
            $surat->update($request->except(['jenis_surat', '_token', '_method']));
        } elseif ($jenis === 'skck') {
            $surat = SuratSkck::findOrFail($id);
            $surat->update($request->except(['jenis_surat', '_token', '_method']));
        } elseif ($jenis === 'kk' && class_exists('App\\Models\\SuratKk')) {
            $surat = SuratKk::findOrFail($id);
            $surat->update($request->except(['jenis_surat', '_token', '_method']));
        } elseif ($jenis === 'kematian' && class_exists('App\\Models\\SuratKematian')) {
            $surat = SuratKematian::findOrFail($id);
            $surat->update($request->except(['jenis_surat', '_token', '_method']));
        } elseif ($jenis === 'kelahiran' && class_exists('App\\Models\\SuratKelahiran')) {
            $surat = SuratKelahiran::findOrFail($id);
            $surat->update($request->except(['jenis_surat', '_token', '_method']));
        } elseif ($jenis === 'belum_menikah') {
            $surat = \App\Models\BelumMenikah::findOrFail($id);
            $surat->update($request->except(['jenis_surat', '_token', '_method']));
        } elseif ($jenis === 'tidak_mampu') {
            $surat = \App\Models\TidakMampu::findOrFail($id);
            $surat->update($request->except(['jenis_surat', '_token', '_method']));
        } elseif ($jenis === 'usaha' && class_exists('App\\Models\\SuratUsaha')) {
            // Validate Surat Usaha fields
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'nik' => 'required|string|max:16',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'agama' => 'required|string|max:50',
                'status_perkawinan' => 'required|string|max:50',
                'pekerjaan' => 'required|string|max:255',
                'alamat' => 'required|string',
                'nama_usaha' => 'required|string|max:255',
                'jenis_usaha' => 'required|string|max:100',
                'alamat_usaha' => 'required|string',
                'tanggal_mulai_usaha' => 'required|date',
                'modal_usaha' => 'required|numeric|min:0',
                'deskripsi_usaha' => 'required|string',
                'keperluan' => 'required|string',
            ]);
            
            $surat = \App\Models\SuratUsaha::findOrFail($id);
            $surat->update($request->except(['jenis_surat', '_token', '_method']));
        } else {
            // Try general Surat model only if table exists
            try {
                if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                    $surat = \App\Models\Surat::findOrFail($id);
                    $surat->update($request->except(['jenis_surat', '_token', '_method']));
                } else {
                    return redirect()->back()->with('error', 'Jenis surat tidak dikenali.');
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Jenis surat tidak dikenali.');
            }
        }

        return redirect()->route('surat.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Try to delete from all possible models
        $deleted = false;

        // Try Domisili
        $domisili = \App\Models\Domisili::find($id);
        if ($domisili) {
            $domisili->delete();
            $deleted = true;
        }

        // Try SuratKtp
        if (!$deleted && class_exists('App\\Models\\SuratKtp')) {
            $ktp = \App\Models\SuratKtp::find($id);
            if ($ktp) {
                $ktp->delete();
                $deleted = true;
            }
        }

        // Try SuratSkck
        if (!$deleted) {
            $skck = SuratSkck::find($id);
            if ($skck) {
                $skck->delete();
                $deleted = true;
            }
        }

        // Try SuratKk
        if (!$deleted && class_exists('App\\Models\\SuratKk')) {
            $kk = SuratKk::find($id);
            if ($kk) {
                $kk->delete();
                $deleted = true;
            }
        }

        // Try SuratKematian
        if (!$deleted && class_exists('App\\Models\\SuratKematian')) {
            $kematian = SuratKematian::find($id);
            if ($kematian) {
                $kematian->delete();
                $deleted = true;
            }
        }

        // Try SuratKelahiran
        if (!$deleted && class_exists('App\\Models\\SuratKelahiran')) {
            $kelahiran = SuratKelahiran::find($id);
            if ($kelahiran) {
                $kelahiran->delete();
                $deleted = true;
            }
        }

        // Try BelumMenikah
        if (!$deleted) {
            $belumMenikah = \App\Models\BelumMenikah::find($id);
            if ($belumMenikah) {
                $belumMenikah->delete();
                $deleted = true;
            }
        }

        // Try TidakMampu
        if (!$deleted) {
            $tidakMampu = \App\Models\TidakMampu::find($id);
            if ($tidakMampu) {
                $tidakMampu->delete();
                $deleted = true;
            }
        }

        // Try Surat (general) - only if table exists
        if (!$deleted) {
            try {
                if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                    $surat = \App\Models\Surat::find($id);
                    if ($surat) {
                        $surat->delete();
                        $deleted = true;
                    }
                }
            } catch (\Exception $e) {
                // Ignore error if table doesn't exist
            }
        }

        if ($deleted) {
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Surat berhasil dihapus.']);
            }
            return redirect()->route('surat.index')->with('success', 'Surat berhasil dihapus.');
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Surat tidak ditemukan.']);
        }
        return redirect()->back()->with('error', 'Surat tidak ditemukan.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'catatan' => 'nullable|string',
            'jenis_surat' => 'required|string'
        ]);

        $jenis = $request->input('jenis_surat');
        $status = $request->input('status');
        $catatan = $request->input('catatan');
        $updated = false;

        try {
            switch($jenis) {
                case 'domisili':
                    $surat = \App\Models\Domisili::find($id);
                    if ($surat) {
                        $surat->status_pengajuan = $status;
                        if ($catatan) {
                            $surat->catatan_verifikasi = $catatan;
                        }
                        $surat->save();
                        
                        // Kirim notifikasi berdasarkan status
                        $this->sendNotificationBasedOnStatus($surat, $jenis, $status, $catatan);
                        
                        $updated = true;
                    }
                    break;

                case 'belum_menikah':
                    $surat = BelumMenikah::find($id);
                    if ($surat) {
                        $surat->status = $status;
                        if ($catatan) {
                            $surat->catatan_verifikasi = $catatan;
                        }
                        $surat->save();
                        
                        // Kirim notifikasi berdasarkan status
                        $this->sendNotificationBasedOnStatus($surat, $jenis, $status, $catatan);
                        
                        $updated = true;
                    }
                    break;

                case 'tidak_mampu':
                    $surat = TidakMampu::find($id);
                    if ($surat) {
                        $surat->status = $status;
                        if ($catatan) {
                            $surat->catatan_verifikasi = $catatan;
                        }
                        $surat->save();
                        
                        // Kirim notifikasi berdasarkan status
                        $this->sendNotificationBasedOnStatus($surat, $jenis, $status, $catatan);
                        
                        $updated = true;
                    }
                    break;

                case 'skck':
                    $surat = SuratSkck::find($id);
                    if ($surat) {
                        $surat->status = $status;
                        if ($catatan) {
                            $surat->catatan_verifikasi = $catatan;
                        }
                        $surat->save();
                        
                        // Kirim notifikasi berdasarkan status
                        $this->sendNotificationBasedOnStatus($surat, $jenis, $status, $catatan);
                        
                        $updated = true;
                    }
                    break;

                case 'kk':
                    if (class_exists('App\\Models\\SuratKk')) {
                        $surat = SuratKk::find($id);
                        if ($surat) {
                            $surat->status = $status;
                            if ($catatan) {
                                $surat->catatan_verifikasi = $catatan;
                            }
                            $surat->save();
                            
                            // Kirim notifikasi berdasarkan status
                            $this->sendNotificationBasedOnStatus($surat, $jenis, $status, $catatan);
                            
                            $updated = true;
                        }
                    }
                    break;

                case 'kematian':
                    if (class_exists('App\\Models\\SuratKematian')) {
                        $surat = SuratKematian::find($id);
                        if ($surat) {
                            $surat->status = $status;
                            if ($catatan) {
                                $surat->catatan_verifikasi = $catatan;
                            }
                            $surat->save();
                            
                            // Kirim notifikasi berdasarkan status
                            $this->sendNotificationBasedOnStatus($surat, $jenis, $status, $catatan);
                            
                            $updated = true;
                        }
                    }
                    break;

                case 'kelahiran':
                    if (class_exists('App\\Models\\SuratKelahiran')) {
                        $surat = SuratKelahiran::find($id);
                        if ($surat) {
                            $surat->status = $status;
                            if ($catatan) {
                                $surat->catatan_verifikasi = $catatan;
                            }
                            $surat->save();
                            
                            // Kirim notifikasi berdasarkan status
                            $this->sendNotificationBasedOnStatus($surat, $jenis, $status, $catatan);
                            
                            $updated = true;
                        }
                    }
                    break;

                case 'ktp':
                    if (class_exists('App\\Models\\SuratKtp')) {
                        $surat = \App\Models\SuratKtp::find($id);
                        if ($surat) {
                            $surat->status = $status;
                            if ($catatan) {
                                $surat->catatan_verifikasi = $catatan;
                            }
                            $surat->save();
                            
                            // Kirim notifikasi berdasarkan status
                            $this->sendNotificationBasedOnStatus($surat, $jenis, $status, $catatan);
                            
                            $updated = true;
                        }
                    }
                    break;

                case 'usaha':
                    if (class_exists('App\\Models\\SuratUsaha')) {
                        $surat = \App\Models\SuratUsaha::find($id);
                        if ($surat) {
                            $surat->status = $status;
                            if ($catatan) {
                                $surat->catatan_verifikasi = $catatan;
                            }
                            $surat->save();
                            
                            // Kirim notifikasi berdasarkan status
                            $this->sendNotificationBasedOnStatus($surat, $jenis, $status, $catatan);
                            
                            $updated = true;
                        }
                    }
                    break;

                case 'kehilangan':
                    if (class_exists('App\\Models\\SuratKehilangan')) {
                        $surat = \App\Models\SuratKehilangan::find($id);
                        if ($surat) {
                            $surat->status = $status;
                            if ($catatan) {
                                $surat->catatan_verifikasi = $catatan;
                            }
                            $surat->save();
                            
                            // Kirim notifikasi berdasarkan status
                            $this->sendNotificationBasedOnStatus($surat, $jenis, $status, $catatan);
                            
                            $updated = true;
                        }
                    }
                    break;

                default:
                    // For other types, try general Surat model only if table exists
                    try {
                        if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                            $surat = \App\Models\Surat::find($id);
                            if ($surat) {
                                $surat->status = $status;
                                if ($catatan) {
                                    $surat->catatan_verifikasi = $catatan;
                                }
                                $surat->save();
                                $updated = true;
                            }
                        }
                    } catch (\Exception $e) {
                        // Ignore error if table doesn't exist
                    }
                    break;
            }

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status verifikasi berhasil diperbarui'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Surat tidak ditemukan'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Complete verification for a surat - specific method for the Complete button
     * This method changes status from "diproses" to "sudah diverifikasi"
     */
    public function completeVerification(Request $request, $id)
    {
        // Add detailed logging
        Log::info('Complete verification called', [
            'id' => $id,
            'request_data' => $request->all(),
            'user' => auth()->user() ? auth()->user()->id : 'not authenticated'
        ]);

        try {
            $request->validate([
                'jenis_surat' => 'required|string'
            ]);

            $jenis = $request->input('jenis_surat');
            $updated = false;
            $suratData = null;

            Log::info('Processing jenis surat: ' . $jenis);

            switch($jenis) {
                case 'domisili':
                    $surat = \App\Models\Domisili::find($id);
                    Log::info('Domisili found: ' . ($surat ? 'yes' : 'no'));
                    if ($surat) {
                        $currentStatus = $surat->status_pengajuan ?? $surat->status ?? 'menunggu';
                        Log::info('Domisili current status: ' . $currentStatus);
                        
                        // Allow complete for all statuses except "sudah diverifikasi" and "ditolak"
                        if ($currentStatus !== 'sudah diverifikasi' && $currentStatus !== 'ditolak') {
                            $surat->status_pengajuan = 'sudah diverifikasi';
                            $surat->catatan_verifikasi = 'Verifikasi diselesaikan oleh admin pada ' . now();
                            $surat->save();
                            
                            $suratData = $surat;
                            $updated = true;
                            Log::info('Domisili updated successfully from status: ' . $currentStatus);
                        } else {
                            Log::warning('Domisili cannot be completed from status: ' . $currentStatus);
                        }
                    }
                    break;

                case 'belum_menikah':
                    $surat = BelumMenikah::find($id);
                    Log::info('BelumMenikah found: ' . ($surat ? 'yes' : 'no'));
                    if ($surat) {
                        $currentStatus = $surat->status ?? 'menunggu';
                        Log::info('BelumMenikah current status: ' . $currentStatus);
                        
                        // Allow complete for all statuses except "sudah diverifikasi" and "ditolak"
                        if ($currentStatus !== 'sudah diverifikasi' && $currentStatus !== 'ditolak') {
                            $surat->status = 'sudah diverifikasi';
                            $surat->catatan_verifikasi = 'Verifikasi diselesaikan oleh admin pada ' . now();
                            $surat->save();
                            
                            $suratData = $surat;
                            $updated = true;
                            Log::info('BelumMenikah updated successfully from status: ' . $currentStatus);
                        } else {
                            Log::warning('BelumMenikah cannot be completed from status: ' . $currentStatus);
                        }
                    }
                    break;

                case 'tidak_mampu':
                    $surat = TidakMampu::find($id);
                    if ($surat) {
                        $currentStatus = $surat->status ?? 'menunggu';
                        Log::info('TidakMampu current status: ' . $currentStatus);
                        
                        // Allow complete for all statuses except "sudah diverifikasi" and "ditolak"
                        if ($currentStatus !== 'sudah diverifikasi' && $currentStatus !== 'ditolak') {
                            $surat->status = 'sudah diverifikasi';
                            $surat->catatan_verifikasi = 'Verifikasi diselesaikan oleh admin pada ' . now();
                            $surat->save();
                            
                            $suratData = $surat;
                            $updated = true;
                            Log::info('TidakMampu updated successfully from status: ' . $currentStatus);
                        }
                    }
                    break;

                case 'skck':
                    $surat = SuratSkck::find($id);
                    if ($surat) {
                        $currentStatus = $surat->status ?? 'menunggu';
                        Log::info('SKCK current status: ' . $currentStatus);
                        
                        // Allow complete for all statuses except "sudah diverifikasi" and "ditolak"
                        if ($currentStatus !== 'sudah diverifikasi' && $currentStatus !== 'ditolak') {
                            $surat->status = 'sudah diverifikasi';
                            $surat->catatan_verifikasi = 'Verifikasi diselesaikan oleh admin pada ' . now();
                            $surat->save();
                            
                            $suratData = $surat;
                            $updated = true;
                            Log::info('SKCK updated successfully from status: ' . $currentStatus);
                        }
                    }
                    break;

                case 'kk':
                    if (class_exists('App\\Models\\SuratKk')) {
                        $surat = SuratKk::find($id);
                        if ($surat) {
                            $currentStatus = $surat->status ?? 'menunggu';
                            Log::info('KK current status: ' . $currentStatus);
                            
                            // Allow complete for all statuses except "sudah diverifikasi" and "ditolak"
                            if ($currentStatus !== 'sudah diverifikasi' && $currentStatus !== 'ditolak') {
                                $surat->status = 'sudah diverifikasi';
                                $surat->catatan_verifikasi = 'Verifikasi diselesaikan oleh admin pada ' . now();
                                $surat->save();
                                
                                $suratData = $surat;
                                $updated = true;
                                Log::info('KK updated successfully from status: ' . $currentStatus);
                            }
                        }
                    }
                    break;

                case 'kematian':
                    if (class_exists('App\\Models\\SuratKematian')) {
                        $surat = SuratKematian::find($id);
                        if ($surat) {
                            $currentStatus = $surat->status ?? 'menunggu';
                            Log::info('Kematian current status: ' . $currentStatus);
                            
                            // Allow complete for all statuses except "sudah diverifikasi" and "ditolak"
                            if ($currentStatus !== 'sudah diverifikasi' && $currentStatus !== 'ditolak') {
                                $surat->status = 'sudah diverifikasi';
                                $surat->catatan_verifikasi = 'Verifikasi diselesaikan oleh admin pada ' . now();
                                $surat->save();
                                
                                $suratData = $surat;
                                $updated = true;
                                Log::info('Kematian updated successfully from status: ' . $currentStatus);
                            }
                        }
                    }
                    break;

                case 'kelahiran':
                    if (class_exists('App\\Models\\SuratKelahiran')) {
                        $surat = SuratKelahiran::find($id);
                        if ($surat) {
                            $currentStatus = $surat->status ?? 'menunggu';
                            Log::info('Kelahiran current status: ' . $currentStatus);
                            
                            // Allow complete for all statuses except "sudah diverifikasi" and "ditolak"
                            if ($currentStatus !== 'sudah diverifikasi' && $currentStatus !== 'ditolak') {
                                $surat->status = 'sudah diverifikasi';
                                $surat->catatan_verifikasi = 'Verifikasi diselesaikan oleh admin pada ' . now();
                                $surat->save();
                                
                                $suratData = $surat;
                                $updated = true;
                                Log::info('Kelahiran updated successfully from status: ' . $currentStatus);
                            }
                        }
                    }
                    break;

                case 'ktp':
                    if (class_exists('App\\Models\\SuratKtp')) {
                        $surat = \App\Models\SuratKtp::find($id);
                        if ($surat) {
                            $currentStatus = $surat->status ?? 'menunggu';
                            Log::info('KTP current status: ' . $currentStatus);
                            
                            // Allow complete for all statuses except "sudah diverifikasi" and "ditolak"
                            if ($currentStatus !== 'sudah diverifikasi' && $currentStatus !== 'ditolak') {
                                $surat->status = 'sudah diverifikasi';
                                $surat->catatan_verifikasi = 'Verifikasi diselesaikan oleh admin pada ' . now();
                                $surat->save();
                                
                                $suratData = $surat;
                                $updated = true;
                                Log::info('KTP updated successfully from status: ' . $currentStatus);
                            }
                        }
                    }
                    break;

                case 'usaha':
                    if (class_exists('App\\Models\\SuratUsaha')) {
                        $surat = \App\Models\SuratUsaha::find($id);
                        if ($surat) {
                            $currentStatus = $surat->status ?? 'menunggu';
                            Log::info('Usaha current status: ' . $currentStatus);
                            
                            // Allow complete for all statuses except "sudah diverifikasi" and "ditolak"
                            if ($currentStatus !== 'sudah diverifikasi' && $currentStatus !== 'ditolak') {
                                $surat->status = 'sudah diverifikasi';
                                $surat->catatan_verifikasi = 'Verifikasi diselesaikan oleh admin pada ' . now();
                                $surat->save();
                                
                                $suratData = $surat;
                                $updated = true;
                                Log::info('Usaha updated successfully from status: ' . $currentStatus);
                            }
                        }
                    }
                    break;

                case 'kehilangan':
                    if (class_exists('App\\Models\\SuratKehilangan')) {
                        $surat = \App\Models\SuratKehilangan::find($id);
                        if ($surat) {
                            $currentStatus = $surat->status ?? 'menunggu';
                            Log::info('Kehilangan current status: ' . $currentStatus);
                            
                            // Allow complete for all statuses except "sudah diverifikasi" and "ditolak"
                            if ($currentStatus !== 'sudah diverifikasi' && $currentStatus !== 'ditolak') {
                                $surat->status = 'sudah diverifikasi';
                                $surat->catatan_verifikasi = 'Verifikasi diselesaikan oleh admin pada ' . now();
                                $surat->save();
                                
                                $suratData = $surat;
                                $updated = true;
                                Log::info('Kehilangan updated successfully from status: ' . $currentStatus);
                            }
                        }
                    }
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Jenis surat tidak didukung'
                    ]);
            }

            if ($updated && $suratData) {
                // Send completion notification
                $this->sendCompletionNotification($suratData, $jenis);
                
                Log::info('Complete verification successful', [
                    'id' => $id,
                    'jenis' => $jenis,
                    'updated_status' => 'sudah diverifikasi'
                ]);
                
                // Return appropriate response based on request type
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Verifikasi surat berhasil diselesaikan',
                        'data' => [
                            'id' => $id,
                            'jenis_surat' => $jenis,
                            'status' => 'sudah diverifikasi',
                            'completed_at' => now()->format('Y-m-d H:i:s')
                        ]
                    ]);
                } else {
                    // Form submission - redirect back with success message
                    return redirect()->back()->with('success', 'Verifikasi surat berhasil diselesaikan!');
                }
            } else {
                Log::warning('Complete verification failed', [
                    'id' => $id,
                    'jenis' => $jenis,
                    'updated' => $updated,
                    'surat_found' => $suratData ? 'yes' : 'no'
                ]);
                
                $errorMessage = 'Surat tidak ditemukan atau status bukan "diproses"';
                
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ]);
                } else {
                    return redirect()->back()->with('error', $errorMessage);
                }
            }

        } catch (\Exception $e) {
            Log::error('Complete verification exception', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ]);
            } else {
                return redirect()->back()->with('error', $errorMessage);
            }
        }
    }

    private function sendCompletionNotification($surat, $jenis)
    {
        try {
            // Check if NotificationService exists
            if (class_exists('App\\Services\\NotificationService')) {
                $notificationService = new \App\Services\NotificationService();
                
                // Get user contact info
                $phoneNumber = $surat->no_hp ?? $surat->no_handphone ?? null;
                $email = $surat->email ?? null;
                $nama = $surat->nama ?? $surat->nama_lengkap ?? $surat->nama_pemohon ?? 'Pemohon';
                
                $message = "Halo {$nama}, verifikasi surat keterangan {$jenis} Anda telah selesai dan sudah diverifikasi. Silakan ambil surat Anda di kantor desa.";

                Log::info('Notification message prepared', ['message' => $message, 'phone' => $phoneNumber, 'email' => $email]);
            }
        } catch (\Exception $e) {
            // Log error but don't stop the main process
            Log::error('Failed to send completion notification: ' . $e->getMessage());
        }
    }

    public function usahaSubmit(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'nik' => 'required|string|max:16',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'agama' => 'required|string|max:50',
                'status_perkawinan' => 'required|string|max:50',
                'pekerjaan' => 'required|string|max:255',
                'alamat' => 'required|string',
                'nama_usaha' => 'required|string|max:255',
                'jenis_usaha' => 'required|string|max:100',
                'alamat_usaha' => 'required|string',
                'lama_usaha' => 'required|string|max:100',
                'modal_usaha' => 'required|string|max:100',
                'omzet_usaha' => 'required|string|max:100',
                'keperluan' => 'required|string',
                // File upload validations
                'file_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'file_foto_usaha' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'file_izin_usaha' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'file_pengantar' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            // Handle file uploads
            $fileKtp = null;
            $fileKk = null;
            $fileFotoUsaha = null;
            $fileIzinUsaha = null;
            $filePengantar = null;

            if ($request->hasFile('file_ktp')) {
                $fileKtp = $request->file('file_ktp')->store('uploads/usaha/ktp', 'public');
            }

            if ($request->hasFile('file_kk')) {
                $fileKk = $request->file('file_kk')->store('uploads/usaha/kk', 'public');
            }

            if ($request->hasFile('file_foto_usaha')) {
                $fileFotoUsaha = $request->file('file_foto_usaha')->store('uploads/usaha/foto', 'public');
            }

            if ($request->hasFile('file_izin_usaha')) {
                $fileIzinUsaha = $request->file('file_izin_usaha')->store('uploads/usaha/izin', 'public');
            }

            if ($request->hasFile('file_pengantar')) {
                $filePengantar = $request->file('file_pengantar')->store('uploads/usaha/pengantar', 'public');
            }

            // Create the surat usaha record
            \App\Models\SuratUsaha::create([
                'user_id' => auth()->id(),
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'agama' => $request->agama,
                'status_perkawinan' => $request->status_perkawinan,
                'kewarganegaraan' => 'WNI', // Default value
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
                'nama_usaha' => $request->nama_usaha,
                'jenis_usaha' => $request->jenis_usaha,
                'alamat_usaha' => $request->alamat_usaha,
                'lama_usaha' => $request->lama_usaha,
                'modal_usaha' => $request->modal_usaha,
                'omzet_usaha' => $request->omzet_usaha,
                'keperluan' => $request->keperluan,
                'status' => 'menunggu',
                'tahapan_verifikasi' => null,
                'catatan_verifikasi' => null,
                'catatan_verifikasi_detail' => null,
                'tanggal_verifikasi_terakhir' => null,
                // File paths
                'file_ktp' => $fileKtp,
                'file_kk' => $fileKk,
                'file_foto_usaha' => $fileFotoUsaha,
                'file_izin_usaha' => $fileIzinUsaha,
                'file_pengantar' => $filePengantar,
            ]);

            return redirect()->back()->with('success', 'Surat keterangan usaha berhasil diajukan! Silakan tunggu proses verifikasi dari admin.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengajukan surat: ' . $e->getMessage())->withInput();
        }
    }

    public function domisiliSubmit(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'nik' => 'required|string|max:16',
                'nama' => 'required|string|max:255',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'kewarganegaraan' => 'required|in:WNI,WNA',
                'agama' => 'required|string|max:50',
                'status' => 'required|string|max:50',
                'pekerjaan' => 'required|string|max:255',
                'alamat' => 'required|string',
                'keperluan' => 'required|string|max:255',
            ]);

            // Create the domisili record
            \App\Models\Domisili::create([
                'user_id' => auth()->id(),
                'nik' => $request->nik,
                'nama' => $request->nama,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'kewarganegaraan' => $request->kewarganegaraan,
                'agama' => $request->agama,
                'status' => $request->status,
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
                'keperluan' => $request->keperluan,
                'status_pengajuan' => 'Menunggu Verifikasi',
                'tahapan_verifikasi' => \App\Services\VerificationStageService::initializeStages('domisili'),
                'tanggal_verifikasi_terakhir' => now(),
            ]);

            return redirect('/surat/domisili')->with('success', 'Permohonan surat keterangan domisili berhasil diajukan!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Mengirim notifikasi berdasarkan status verifikasi surat
     */
    private function sendNotificationBasedOnStatus($surat, $jenisSurat, $status, $catatan = null)
    {
        // Pastikan surat memiliki user_id
        if (!isset($surat->user_id) || !$surat->user_id) {
            return false;
        }

        $userId = $surat->user_id;
        $suratId = $surat->id;
        
        // Ambil nama pemohon berdasarkan jenis surat
        $namaPemohon = $this->getNamaPemohonFromSurat($surat, $jenisSurat);

        // Kirim notifikasi berdasarkan status
        switch (strtolower($status)) {
            case 'diverifikasi':
            case 'verified':
            case 'selesai':
            case 'approved':
                NotificationService::createSuratApprovedNotification($userId, $suratId, $jenisSurat, $namaPemohon);
                break;
                
            case 'dalam proses':
            case 'diproses':
            case 'processing':
                NotificationService::createSuratDiprosesNotification($userId, $suratId, $jenisSurat, $namaPemohon);
                break;
                
            case 'ditolak':
            case 'rejected':
                NotificationService::createSuratDitolakNotification($userId, $suratId, $jenisSurat, $namaPemohon, $catatan);
                break;
                
            default:
                // Untuk status lainnya, buat notifikasi umum
                NotificationService::createSuratDiprosesNotification($userId, $suratId, $jenisSurat, $namaPemohon);
                break;
        }

        return true;
    }

    /**
     * Mendapatkan nama pemohon dari model surat berdasarkan jenis surat
     */
    private function getNamaPemohonFromSurat($surat, $jenisSurat)
    {
        switch($jenisSurat) {
            case 'domisili':
                return $surat->nama ?? 'Unknown';
            case 'ktp':
                return $surat->nama_lengkap ?? 'Unknown';
            case 'belum_menikah':
            case 'tidak_mampu':
            case 'skck':
            case 'kk':
            case 'kematian':
            case 'kelahiran':
                return $surat->nama_lengkap ?? $surat->nama ?? 'Unknown';
            default:
                return $surat->nama ?? $surat->nama_lengkap ?? 'Unknown';
        }
    }

    /**
     * Halaman test notifikasi
     */
    public function testNotificationPage()
    {
        return view('admin.test-notification');
    }

    /**
     * API untuk test notifikasi
     */
    public function testNotification(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required|string',
            'status' => 'required|string'
        ]);

        $jenisSurat = $request->input('jenis_surat');
        $status = $request->input('status');

        try {
            // Cari surat pertama dari jenis yang diminta untuk test
            $surat = null;
            
            switch($jenisSurat) {
                case 'domisili':
                    $surat = \App\Models\Domisili::whereNotNull('user_id')->first();
                    break;
                case 'ktp':
                    $surat = \App\Models\SuratKtp::whereNotNull('user_id')->first();
                    break;
                case 'belum_menikah':
                    $surat = \App\Models\BelumMenikah::whereNotNull('user_id')->first();
                    break;
                case 'skck':
                    $surat = \App\Models\SuratSkck::whereNotNull('user_id')->first();
                    break;
                case 'tidak_mampu':
                    $surat = \App\Models\TidakMampu::whereNotNull('user_id')->first();
                    break;
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Jenis surat tidak valid'
                    ]);
            }

            if (!$surat) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak ada data surat {$jenisSurat} dengan user_id valid untuk test"
                ]);
            }

            // Kirim notifikasi test
            $this->sendNotificationBasedOnStatus($surat, $jenisSurat, $status, "Test notifikasi dari admin");

            return response()->json([
                'success' => true,
                'message' => "Notifikasi {$status} untuk surat {$jenisSurat} berhasil dikirim ke user {$surat->user_id}"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
