<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\BelumMenikah;
use App\Models\TidakMampu;
use App\Models\SuratSkck;
use App\Models\SuratKk;
use App\Models\SuratKematian;
use App\Models\SuratKelahiran;

class SuratController extends Controller
{
    // Minimal controller for admin routes

    public function index()
    {
        // Ambil semua surat dari berbagai jenis
        $suratList = collect();
        // Surat Domisili
        $domisili = \App\Models\Domisili::all();
        foreach ($domisili as $item) {
            $suratList->push((object)[
                'id' => $item->id,
                'nama_pemohon' => $item->nama,
                'jenis_surat' => 'domisili',
                'created_at' => $item->created_at,
                'status' => $item->status_pengajuan ?? $item->status,
            ]);
        }
        // Surat KTP
        $ktp = \App\Models\SuratKtp::all();
        foreach ($ktp as $item) {
            $suratList->push((object)[
                'id' => $item->id,
                'nama_pemohon' => $item->nama_lengkap,
                'jenis_surat' => 'ktp',
                'created_at' => $item->created_at,
                'status' => $item->status,
            ]);
        }

        // Surat Belum Menikah
        $belumMenikah = \App\Models\BelumMenikah::all();
        foreach ($belumMenikah as $item) {
            $suratList->push((object)[
                'id' => $item->id,
                'nama_pemohon' => $item->nama,
                'jenis_surat' => 'belum_menikah',
                'created_at' => $item->created_at,
                'status' => $item->status,
            ]);
        }

        // Surat Tidak Mampu
        $tidakMampu = \App\Models\TidakMampu::all();
        foreach ($tidakMampu as $item) {
            $suratList->push((object)[
                'id' => $item->id,
                'nama_pemohon' => $item->nama,
                'jenis_surat' => 'tidak_mampu',
                'created_at' => $item->created_at,
                'status' => $item->status,
            ]);
        }

        // Surat SKCK
        $skck = SuratSkck::all();
        foreach ($skck as $item) {
            $suratList->push((object)[
                'id' => $item->id,
                'nama_pemohon' => $item->nama_lengkap,
                'jenis_surat' => 'skck',
                'created_at' => $item->created_at,
                'status' => $item->status,
            ]);
        }

        // Surat KK
        if (class_exists('App\\Models\\SuratKk')) {
            $kk = SuratKk::all();
            foreach ($kk as $item) {
                $suratList->push((object)[
                    'id' => $item->id,
                    'nama_pemohon' => $item->nama_lengkap,
                    'jenis_surat' => 'kk',
                    'created_at' => $item->created_at,
                    'status' => $item->status,
                ]);
            }
        }

        // Surat Kematian
        if (class_exists('App\\Models\\SuratKematian')) {
            $kematian = SuratKematian::all();
            foreach ($kematian as $item) {
                $suratList->push((object)[
                    'id' => $item->id,
                    'nama_pemohon' => $item->nama_pelapor,
                    'jenis_surat' => 'kematian',
                    'created_at' => $item->created_at,
                    'status' => $item->status,
                ]);
            }
        }

        // Surat Kelahiran
        if (class_exists('App\\Models\\SuratKelahiran')) {
            $kelahiran = SuratKelahiran::all();
            foreach ($kelahiran as $item) {
                $suratList->push((object)[
                    'id' => $item->id,
                    'nama_pemohon' => $item->nama_pelapor,
                    'jenis_surat' => 'kelahiran',
                    'created_at' => $item->created_at,
                    'status' => $item->status,
                ]);
            }
        }
        // Surat lain (jika ada model Surat dan tabel surat)
        try {
            if (class_exists('App\\Models\\Surat')) {
                // Check if table exists before querying
                if (\Schema::hasTable('surat')) {
                    $surat = \App\Models\Surat::all();
                    foreach ($surat as $item) {
                        $suratList->push((object)[
                            'id' => $item->id,
                            'nama_pemohon' => $item->nama_pemohon ?? $item->nama ?? '-',
                            'jenis_surat' => $item->jenis_surat,
                            'created_at' => $item->created_at,
                            'status' => $item->status ?? '-',
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            // Ignore error if table doesn't exist
        }
        return view('admin.surat.index', compact('suratList'));
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
        
        return redirect()->route('admin.surat.index')->with('success', 'Surat ' . ucwords(str_replace('_', ' ', $jenis)) . ' berhasil ditambahkan.');
    }

    public function show($id)
    {
        // Find and show specific surat
        $surat = null;
        $jenis = '';
        
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
            $surat = \App\Models\Domisili::findOrFail($id);
            $surat->update($request->all());
        } elseif ($jenis === 'ktp' && class_exists('App\\Models\\SuratKtp')) {
            $surat = \App\Models\SuratKtp::findOrFail($id);
            $surat->update($request->all());
        } elseif ($jenis === 'skck') {
            $surat = SuratSkck::findOrFail($id);
            $surat->update($request->all());
        } elseif ($jenis === 'kk' && class_exists('App\\Models\\SuratKk')) {
            $surat = SuratKk::findOrFail($id);
            $surat->update($request->all());
        } elseif ($jenis === 'kematian' && class_exists('App\\Models\\SuratKematian')) {
            $surat = SuratKematian::findOrFail($id);
            $surat->update($request->all());
        } elseif ($jenis === 'kelahiran' && class_exists('App\\Models\\SuratKelahiran')) {
            $surat = SuratKelahiran::findOrFail($id);
            $surat->update($request->all());
        } elseif ($jenis === 'belum_menikah') {
            $surat = \App\Models\BelumMenikah::findOrFail($id);
            $surat->update($request->all());
        } elseif ($jenis === 'tidak_mampu') {
            $surat = \App\Models\TidakMampu::findOrFail($id);
            $surat->update($request->all());
        } else {
            // Try general Surat model only if table exists
            try {
                if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                    $surat = \App\Models\Surat::findOrFail($id);
                    $surat->update($request->all());
                } else {
                    return redirect()->back()->with('error', 'Jenis surat tidak dikenali.');
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Jenis surat tidak dikenali.');
            }
        }

        return redirect()->route('admin.surat.index')->with('success', 'Surat berhasil diperbarui.');
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
            return redirect()->route('admin.surat.index')->with('success', 'Surat berhasil dihapus.');
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
                'tahapan_verifikasi' => ['submitted' => now()],
                'tanggal_verifikasi_terakhir' => now(),
            ]);

            return redirect('/surat/domisili')->with('success', 'Permohonan surat keterangan domisili berhasil diajukan!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }
}
