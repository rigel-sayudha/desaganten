<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\BelumMenikah;
use App\Models\TidakMampu;
use App\Models\SuratSkck;
use App\Models\SuratKehilangan;
use App\Models\SuratKematian;
use App\Models\SuratKelahiran;
use App\Models\SuratKtp;
use App\Models\Domisili;
use App\Models\SuratUsaha;
use App\Services\VerificationStageService;

class SuratController extends Controller
{
    public function index()
    {
        $surat = collect(); // Initialize empty collection
        
        // Try to get data from Surat model only if table exists
        try {
            if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                $surat = \App\Models\Surat::orderBy('created_at', 'desc')->get();
            }
        } catch (\Exception $e) {
            // Ignore error if table doesn't exist and use empty collection
        }
        
        return view('admin.surat.index', compact('surat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16',
            'nama' => 'required',
            'alamat' => 'required',
            'jenis_surat' => 'required',
            'berkas' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        $filePath = null;
        if ($request->hasFile('berkas')) {
            try {
                $filePath = $request->file('berkas')->store('surat_berkas','public');
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['berkas' => 'Gagal upload berkas. Pastikan file sesuai dan folder storage bisa ditulis.']);
            }
        } else {
            return back()->withInput()->withErrors(['berkas' => 'Berkas wajib diunggah.']);
        }
        $data = [
            'nik' => $request->nik,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'jenis_surat' => $request->jenis_surat,
            'file_berkas' => $filePath,
            'status' => 'Menunggu Verifikasi',
            'catatan' => '',
        ];
        
        // Try to create record in Surat model only if table exists
        try {
            if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                \App\Models\Surat::create($data);
                return redirect('/surat/form')->with('success','Surat berhasil diajukan.');
            } else {
                return back()->withInput()->withErrors(['error' => 'Fitur surat tidak tersedia saat ini. Hubungi administrator.']);
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                $surat = \App\Models\Surat::findOrFail($id);
                if ($surat->file_berkas) {
                    \Storage::disk('public')->delete($surat->file_berkas);
                }
                $surat->delete();
                return back()->with('success','Surat berhasil dihapus.');
            } else {
                return back()->with('error', 'Fitur tidak tersedia saat ini.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                $surat = \App\Models\Surat::findOrFail($id);
                $surat->status = $request->status;
                $surat->catatan = $request->catatan;
                $surat->save();
                return back()->with('success','Status surat diperbarui.');
            } else {
                return back()->with('error', 'Fitur tidak tersedia saat ini.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function kehilanganSubmit(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|size:16',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'jenis_barang' => 'required|string|max:255',
            'waktu_tempat' => 'required|string',
        ]);

        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu untuk mengajukan surat.');
            }

            // Cek apakah sudah ada permohonan yang masih pending
            $existingRequest = \App\Models\SuratKehilangan::where('user_id', auth()->id())
                ->where('status', 'diproses')
                ->first();

            if ($existingRequest) {
                return redirect()->back()->with('error', 'Anda masih memiliki permohonan surat kehilangan yang sedang diproses. Silakan tunggu hingga selesai sebelum mengajukan permohonan baru.');
            }

            // Parse the waktu_tempat to extract meaningful data
            $waktuTempat = $validated['waktu_tempat'];
            
            // Initialize verification stages using VerificationStageService
            $stages = null;
            try {
                $verificationService = new \App\Services\VerificationStageService();
                $stages = $verificationService->initializeStages('kehilangan');
            } catch (\Exception $e) {
                \Log::warning('VerificationStageService failed, using default stages: ' . $e->getMessage());
            }

            // If verification service fails, use default stages
            if (!$stages) {
                $stages = [
                    1 => ['name' => 'Verifikasi Dokumen', 'status' => 'in_progress', 'notes' => '', 'updated_at' => null, 'required_documents' => ['KTP', 'KK', 'Surat permohonan'], 'description' => 'Verifikasi kelengkapan dokumen pemohon', 'duration' => '1-2 hari'],
                    2 => ['name' => 'Konfirmasi Kepolisian', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Laporan kehilangan'], 'description' => 'Konfirmasi laporan kehilangan ke pihak kepolisian', 'duration' => '2-3 hari'],
                    3 => ['name' => 'Verifikasi Lokasi Kehilangan', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Kronologi kejadian'], 'description' => 'Konfirmasi lokasi dan waktu kehilangan', 'duration' => '1 hari'],
                    4 => ['name' => 'Review dan Validasi', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Semua dokumen lengkap'], 'description' => 'Review menyeluruh semua dokumen dan data', 'duration' => '1 hari'],
                    5 => ['name' => 'Penerbitan Surat', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Surat kehilangan'], 'description' => 'Pembuatan dan penandatanganan surat keterangan kehilangan', 'duration' => '1 hari']
                ];
            }
            
            // Create the kehilangan record using mass assignment
            $kehilangan = \App\Models\SuratKehilangan::create([
                'nama_lengkap' => $validated['nama'],
                'nik' => $validated['nik'],
                'jenis_kelamin' => 'Tidak Diisi', // Default value
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'agama' => 'Tidak Diisi', // Default value
                'status_perkawinan' => 'Tidak Diisi', // Default value
                'kewarganegaraan' => 'WNI', // Default value
                'pekerjaan' => 'Tidak Diisi', // Default value
                'alamat' => $validated['alamat'],
                'barang_hilang' => $validated['jenis_barang'],
                'deskripsi_barang' => $validated['jenis_barang'], // Use same as barang_hilang
                'tempat_kehilangan' => 'Diisi dalam kronologi', // Will be in kronologi
                'tanggal_kehilangan' => now()->toDateString(), // Use current date as default
                'waktu_kehilangan' => 'Sesuai kronologi',
                'kronologi_kehilangan' => $waktuTempat,
                'keperluan' => 'Pembuatan surat keterangan kehilangan',
                'status' => 'diproses',
                'user_id' => auth()->id(),
                'tahapan_verifikasi' => $stages
            ]);

            return redirect()->back()->with('success', 'Permohonan surat keterangan kehilangan berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');
            
        } catch (\Exception $e) {
            \Log::error('Error saving kehilangan form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }

    public function ktpSubmit(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'status_perkawinan' => 'required|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'nik' => 'required|digits:16',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'keperluan' => 'required|string|max:255',
        ]);

        try {
            // Check if user is logged in
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengajukan permohonan surat.');
            }

            // Check for existing pending request
            $existingRequest = SuratKtp::where('user_id', auth()->id())
                ->whereIn('status', ['menunggu', 'diproses'])
                ->first();

            if ($existingRequest) {
                return redirect()->back()->with('error', 'Anda masih memiliki permohonan surat keterangan KTP yang sedang diproses. Silakan tunggu hingga selesai sebelum mengajukan permohonan baru.');
            }

            // Handle file uploads
            $files = [];
            $uploadPath = 'surat_ktp_files';
            
            // Upload KTP Lama file
            if ($request->hasFile('ktp_lama_file')) {
                $files['ktp_lama_file'] = $request->file('ktp_lama_file')->store($uploadPath, 'public');
            }
            
            // Upload KK file
            if ($request->hasFile('kk_file')) {
                $files['kk_file'] = $request->file('kk_file')->store($uploadPath, 'public');
            }
            
            // Upload Akta file
            if ($request->hasFile('akta_file')) {
                $files['akta_file'] = $request->file('akta_file')->store($uploadPath, 'public');
            }

            // Create KTP request record
            $suratKtp = SuratKtp::create([
                'user_id' => auth()->id(),
                'nama_lengkap' => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'agama' => $validated['agama'],
                'status_perkawinan' => $validated['status_perkawinan'],
                'nik' => $validated['nik'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'pekerjaan' => $validated['pekerjaan'],
                'alamat' => $validated['alamat'],
                'keperluan' => $validated['keperluan'],
                'status' => 'menunggu',
                'file_uploads' => !empty($files) ? json_encode($files) : null,
            ]);

            // Initialize verification stages if the service exists
            if (class_exists('App\\Services\\VerificationStageService')) {
                try {
                    $verificationService = new \App\Services\VerificationStageService();
                    $verificationService->initializeStages($suratKtp, 'ktp');
                } catch (\Exception $e) {
                    // Log the error but don't fail the request
                    \Log::warning('Failed to initialize verification stages for KTP: ' . $e->getMessage());
                }
            }

            return redirect()->back()->with('success', 'Permohonan surat keterangan KTP berhasil dikirim. Silakan cek status permohonan Anda secara berkala.');

        } catch (\Exception $e) {
            \Log::error('Error creating KTP request: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses permohonan. Silakan coba lagi.');
        }
    }

    public function kematianSubmit(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kewarganegaraan' => 'required|in:WNI,WNA',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'status_perkawinan' => 'required|in:Kawin,Belum Kawin,Cerai,Mati',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'rt_rw' => 'required|string|max:20',
            'hari' => 'required|string',
            'tanggal_meninggal' => 'required|date',
            'tempat_kematian' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'sebab_kematian' => 'required|string|max:255',
        ]);

        try {
            // Cek apakah user sudah login
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengajukan permohonan surat.');
            }

            // Cek apakah sudah ada permohonan yang masih pending
            $existingRequest = \App\Models\SuratKematian::where('user_id', auth()->id())
                ->where('status', 'diproses')
                ->first();

            if ($existingRequest) {
                return redirect()->back()->with('error', 'Anda masih memiliki permohonan surat keterangan kematian yang sedang diproses. Silakan tunggu hingga selesai sebelum mengajukan permohonan baru.');
            }

            // Simpan data ke database
            \App\Models\SuratKematian::create([
                'nama_almarhum' => $validated['nama'],
                'nik_almarhum' => '0000000000000000', // Placeholder - akan diisi oleh admin saat verifikasi
                'tempat_lahir_almarhum' => $validated['tempat_lahir'],
                'tanggal_lahir_almarhum' => $validated['tanggal_lahir'],
                'tanggal_kematian' => $validated['tanggal_meninggal'],
                'tempat_kematian' => $validated['tempat_kematian'] . ', ' . $validated['kecamatan'] . ', ' . $validated['kabupaten'] . ', ' . $validated['provinsi'],
                'sebab_kematian' => $validated['sebab_kematian'],
                'nama_pelapor' => auth()->user()->name,
                'nik_pelapor' => auth()->user()->nik ?? '0000000000000000',
                'hubungan_pelapor' => 'Keluarga', // Default value, bisa diubah nanti
                'alamat_pelapor' => $validated['alamat'] . ', RT/RW ' . $validated['rt_rw'],
                'keperluan' => 'Keperluan administrasi kependudukan', // Default value
                'user_id' => auth()->id(),
                'status' => 'diproses',
                'tahapan_verifikasi' => [
                    1 => ['nama' => 'Verifikasi Dokumen', 'status' => 'in_progress', 'notes' => '', 'updated_at' => null, 'required_documents' => ['KTP Pelapor', 'KK Almarhum', 'Surat Keterangan Dokter/RS']],
                    2 => ['nama' => 'Verifikasi Data Almarhum', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Cek data kependudukan almarhum', 'Verifikasi identitas']],
                    3 => ['nama' => 'Verifikasi Data Pelapor', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Verifikasi hubungan keluarga', 'Validasi data pelapor']],
                    4 => ['nama' => 'Pemeriksaan Berkas', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Kelengkapan dokumen', 'Kesesuaian data']],
                    5 => ['nama' => 'Validasi Tempat & Waktu', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Verifikasi tempat kematian', 'Validasi tanggal kematian']],
                    6 => ['nama' => 'Review Sebab Kematian', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Verifikasi sebab kematian', 'Konsultasi medis jika diperlukan']],
                    7 => ['nama' => 'Approval Kepala Desa', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Persetujuan kepala desa']],
                    8 => ['nama' => 'Penerbitan Surat', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Surat keterangan kematian selesai']]
                ]
            ]);

            return redirect()->back()->with('success', 'Permohonan surat keterangan kematian berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');

        } catch (\Exception $e) {
            \Log::error('Error saving kematian form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function kkSubmit(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'status_perkawinan' => 'required|in:Kawin,Belum Kawin,Cerai,Mati',
            'nik' => 'required|digits:16',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
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
            $existingRequest = \App\Models\SuratKk::where('user_id', auth()->id())
                ->where('status', 'diproses')
                ->first();

            if ($existingRequest) {
                return redirect()->back()->with('error', 'Anda masih memiliki permohonan surat KK yang sedang diproses. Silakan tunggu hingga selesai sebelum mengajukan permohonan baru.');
            }

            // Simpan data ke database
            \App\Models\SuratKk::create([
                'nama_lengkap' => $validated['nama_lengkap'],
                'nik' => $validated['nik'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'agama' => $validated['agama'],
                'status_perkawinan' => $validated['status_perkawinan'],
                'kewarganegaraan' => $validated['kewarganegaraan'],
                'pekerjaan' => $validated['pekerjaan'],
                'alamat' => $validated['alamat'],
                'keperluan' => $validated['keperluan'],
                'user_id' => auth()->id(),
                'status' => 'diproses',
                'tahapan_verifikasi' => [
                    1 => ['nama' => 'Verifikasi Dokumen', 'status' => 'in_progress', 'notes' => '', 'updated_at' => null, 'required_documents' => ['KTP asli', 'KK lama (jika ada)', 'Surat Nikah/Cerai (jika ada)']],
                    2 => ['nama' => 'Verifikasi Data Kependudukan', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Cek data di Dukcapil', 'Verifikasi NIK']],
                    3 => ['nama' => 'Pemeriksaan Berkas', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Kelengkapan dokumen', 'Kesesuaian data']],
                    4 => ['nama' => 'Approval Kepala Desa', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Persetujuan kepala desa']],
                    5 => ['nama' => 'Penerbitan Surat', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Surat pengantar KK selesai']]
                ]
            ]);

            return redirect()->back()->with('success', 'Permohonan surat keterangan KK berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');

        } catch (\Exception $e) {
            \Log::error('Error saving KK form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function kelahiranSubmit(Request $request)
    {
        $validated = $request->validate([
            'nama_anak' => 'required|string|max:255',
            'anak_ke' => 'required|integer|min:1',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir_anak' => 'required|string|max:255',
            'alamat_anak' => 'required|string',
            'penolong_kelahiran' => 'required|in:Bidan,Dokter,Dukun',
            'alamat_bidan' => 'required|string|max:255',
            'ibu_nik' => 'required|digits:16',
            'ibu_nama' => 'required|string|max:255',
            'ibu_tempat_lahir' => 'required|string|max:255',
            'ibu_tanggal_lahir' => 'required|date',
            'ibu_alamat' => 'required|string',
            'ayah_nik' => 'required|digits:16',
            'ayah_nama' => 'required|string|max:255',
            'ayah_tempat_lahir' => 'required|string|max:255',
            'ayah_tanggal_lahir' => 'required|date',
            'ayah_alamat' => 'required|string',
        ]);

        try {
            // Cek apakah user sudah login
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengajukan permohonan surat.');
            }

            // Cek apakah sudah ada permohonan yang masih pending
            $existingRequest = \App\Models\SuratKelahiran::where('user_id', auth()->id())
                ->where('status', 'diproses')
                ->first();

            if ($existingRequest) {
                return redirect()->back()->with('error', 'Anda masih memiliki permohonan surat keterangan kelahiran yang sedang diproses. Silakan tunggu hingga selesai sebelum mengajukan permohonan baru.');
            }

            // Simpan data ke database
            \App\Models\SuratKelahiran::create([
                'nama_bayi' => $validated['nama_anak'],
                'jenis_kelamin_bayi' => $validated['jenis_kelamin'],
                'tempat_lahir' => $validated['tempat_lahir_anak'],
                'tanggal_lahir' => '1900-01-01', // Placeholder - akan diisi nanti oleh admin berdasarkan dokumen
                'waktu_lahir' => '00:00:00', // Placeholder - akan diisi nanti oleh admin berdasarkan dokumen
                'nama_ayah' => $validated['ayah_nama'],
                'nik_ayah' => $validated['ayah_nik'],
                'nama_ibu' => $validated['ibu_nama'],
                'nik_ibu' => $validated['ibu_nik'],
                'alamat_orangtua' => $validated['alamat_anak'], // Menggunakan alamat anak sebagai alamat orang tua
                'nama_pelapor' => auth()->user()->name,
                'nik_pelapor' => auth()->user()->nik ?? '0000000000000000',
                'hubungan_pelapor' => 'Orang Tua', // Default value
                'keperluan' => 'Keperluan administrasi kependudukan', // Default value
                'user_id' => auth()->id(),
                'status' => 'diproses',
                'tahapan_verifikasi' => [
                    1 => ['nama' => 'Verifikasi Dokumen', 'status' => 'in_progress', 'notes' => '', 'updated_at' => null, 'required_documents' => ['KTP Ayah & Ibu', 'KK Orang Tua', 'Surat Keterangan Lahir dari Bidan/RS']],
                    2 => ['nama' => 'Verifikasi Data Anak', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Verifikasi data kelahiran', 'Validasi tempat lahir']],
                    3 => ['nama' => 'Verifikasi Data Orang Tua', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Verifikasi identitas ayah', 'Verifikasi identitas ibu']],
                    4 => ['nama' => 'Verifikasi Penolong Kelahiran', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Verifikasi bidan/dokter', 'Validasi tempat persalinan']],
                    5 => ['nama' => 'Pemeriksaan Berkas', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Kelengkapan dokumen', 'Kesesuaian data']],
                    6 => ['nama' => 'Validasi Data Kependudukan', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Cek database Dukcapil', 'Verifikasi hubungan keluarga']],
                    7 => ['nama' => 'Approval Kepala Desa', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Persetujuan kepala desa']],
                    8 => ['nama' => 'Penerbitan Surat', 'status' => 'waiting', 'notes' => '', 'updated_at' => null, 'required_documents' => ['Surat keterangan kelahiran selesai']]
                ]
            ]);

            return redirect()->back()->with('success', 'Permohonan surat keterangan kelahiran berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');

        } catch (\Exception $e) {
            \Log::error('Error saving kelahiran form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function skckSubmit(Request $request)
    {
        $request->validate([
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
                ->where('status', 'diproses')
                ->first();

            if ($existingRequest) {
                return redirect()->back()->with('error', 'Anda masih memiliki permohonan SKCK yang sedang diproses. Silakan tunggu hingga selesai sebelum mengajukan permohonan baru.');
            }

            // Simpan data ke database
            SuratSkck::create([
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'agama' => $request->agama,
                'status_perkawinan' => $request->status_perkawinan,
                'kewarganegaraan' => $request->kewarganegaraan,
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
                'keperluan' => $request->keperluan,
                'status' => 'diproses',
                'user_id' => auth()->id(),
                'tahapan_verifikasi' => [
                    'rt' => ['status' => 'in_progress', 'tanggal' => null, 'catatan' => null],
                    'rw' => ['status' => 'waiting', 'tanggal' => null, 'catatan' => null],
                    'kelurahan' => ['status' => 'waiting', 'tanggal' => null, 'catatan' => null]
                ]
            ]);

            return redirect()->back()->with('success', 'Permohonan Surat Pengantar SKCK berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');

        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Log::error('SKCK Submission Error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function belumMenikahSubmit(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|digits:16|unique:belum_menikah,nik',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'pekerjaan' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'required|string',
            'nama_orang_tua' => 'required|string|max:255',
            'pekerjaan_orang_tua' => 'required|string|max:255',
            'alamat_orang_tua' => 'required|string',
            'keperluan' => 'required|string',
            'pernyataan' => 'required|accepted',
        ]);

        BelumMenikah::create([
            'user_id' => auth()->id(),
            'nama' => $request->nama,
            'nik' => $request->nik,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'pekerjaan' => $request->pekerjaan,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'nama_orang_tua' => $request->nama_orang_tua,
            'pekerjaan_orang_tua' => $request->pekerjaan_orang_tua,
            'alamat_orang_tua' => $request->alamat_orang_tua,
            'keperluan' => $request->keperluan,
            'tahapan_verifikasi' => VerificationStageService::initializeStages('belum_menikah'),
        ]);

        return redirect()->back()->with('success', 'Permohonan surat keterangan belum menikah berhasil dikirim. Silakan tunggu proses verifikasi dari admin.');
    }

    public function tidakMampuSubmit(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|digits:16|unique:tidak_mampu,nik',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'pekerjaan' => 'required|string|max:255',
            'penghasilan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'jumlah_tanggungan' => 'required|integer|min:0',
            'status_rumah' => 'required|string|max:255',
            'keterangan_ekonomi' => 'required|string',
            'keperluan' => 'required|string',
        ]);

        TidakMampu::create([
            'user_id' => auth()->id(),
            'nama' => $request->nama,
            'nik' => $request->nik,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'pekerjaan' => $request->pekerjaan,
            'penghasilan' => $request->penghasilan,
            'alamat' => $request->alamat,
            'jumlah_tanggungan' => $request->jumlah_tanggungan,
            'status_rumah' => $request->status_rumah,
            'keterangan_ekonomi' => $request->keterangan_ekonomi,
            'keperluan' => $request->keperluan,
            'tahapan_verifikasi' => VerificationStageService::initializeStages('tidak_mampu'),
        ]);

        return redirect()->back()->with('success', 'Permohonan surat keterangan tidak mampu berhasil dikirim. Silakan tunggu proses verifikasi dari admin.');
    }

    public function domisiliSubmit(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'kewarganegaraan' => 'required|in:WNI,WNA',
            'status' => 'required|in:Kawin,Belum Kawin,Cerai,Mati',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'keperluan' => 'required|string',
        ]);

        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu untuk mengajukan surat.');
            }

            // Create domisili record
            \App\Models\Domisili::create([
                'user_id' => auth()->id(),
                'nama' => $request->nama,
                'nik' => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin === 'Laki-laki' ? 'L' : 'P',
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'agama' => $request->agama,
                'kewarganegaraan' => $request->kewarganegaraan,
                'status' => $request->status,
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
                'keperluan' => $request->keperluan,
                'status_pengajuan' => 'menunggu',
                'tahapan_verifikasi' => VerificationStageService::initializeStages('domisili'),
            ]);

            return redirect()->back()->with('success', 'Permohonan surat keterangan domisili berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');

        } catch (\Exception $e) {
            \Log::error('Error saving domisili form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function usahaSubmit(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'status_perkawinan' => 'required|in:Kawin,Belum Kawin,Cerai Hidup,Cerai Mati',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nama_usaha' => 'required|string|max:255',
            'jenis_usaha' => 'required|string|max:255',
            'alamat_usaha' => 'required|string',
            'lama_usaha' => 'required|string|max:255',
            'modal_usaha' => 'required|string|max:255',
            'omzet_usaha' => 'required|string|max:255',
            'keperluan' => 'required|string',
        ]);

        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu untuk mengajukan surat.');
            }

            // Create usaha record
            \App\Models\SuratUsaha::create([
                'user_id' => auth()->id(),
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'agama' => $request->agama,
                'status_perkawinan' => $request->status_perkawinan,
                'kewarganegaraan' => 'WNI', // Default to WNI
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
                'nama_usaha' => $request->nama_usaha,
                'jenis_usaha' => $request->jenis_usaha,
                'alamat_usaha' => $request->alamat_usaha,
                'lama_usaha' => $request->lama_usaha,
                'modal_usaha' => $request->modal_usaha,
                'omzet_usaha' => $request->omzet_usaha,
                'deskripsi_usaha' => $request->jenis_usaha, // Use jenis_usaha as default description
                'keperluan' => $request->keperluan,
                'status' => 'menunggu',
                'tahapan_verifikasi' => VerificationStageService::initializeStages('usaha'),
            ]);

            return redirect()->back()->with('success', 'Permohonan surat keterangan usaha berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');

        } catch (\Exception $e) {
            \Log::error('Error saving usaha form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }
}
