<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Models\BelumMenikah;
use App\Models\TidakMampu;
use App\Models\SuratSkck;
use App\Models\SuratKehilangan;
use App\Models\SuratKematian;
use App\Models\SuratKelahiran;
use App\Models\SuratKtp;
use App\Models\SuratKk;
use App\Models\Domisili;
use App\Models\SuratUsaha;
use App\Models\User;
use App\Services\VerificationStageService;
use App\Services\FileUploadService;
use App\Traits\HandlesFileUploads;
use App\Helpers\FileUploadHelper;

class SuratController extends Controller
{
    use HandlesFileUploads;

    /**
     * @var FileUploadService
     */
    protected $fileUploadService;

    /**
     * Constructor
     */
    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
        
    /**
     * Handle file upload securely
     */
    protected function uploadFile($request, $field, $path)
    {
        if ($request->hasFile($field)) {
            try {
                $file = $request->file($field);
                $storedPath = $file->store($path);
                if ($storedPath) {
                    Log::info("File uploaded successfully", [
                        'field' => $field,
                        'path' => $storedPath
                    ]);
                    return $storedPath;
                }
            } catch (\Exception $e) {
                Log::error("File upload failed", [
                    'field' => $field,
                    'error' => $e->getMessage()
                ]);
            }
        }
        return null;
    }

    /**
     * Display a listing of the resource
     */
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

    public function show($id)
    {
        try {
            if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                $surat = \App\Models\Surat::findOrFail($id);
                return view('admin.surat.show', compact('surat'));
            } else {
                return back()->with('error', 'Fitur tidak tersedia saat ini.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Surat tidak ditemukan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                $surat = \App\Models\Surat::findOrFail($id);
                
                if ($request->has('status')) {
                    $surat->status = $request->status;
                    if ($request->status === 'Ditolak' && $request->has('catatan')) {
                        $surat->catatan = $request->catatan;
                    }
                    $surat->save();
                    
                    return back()->with('success', 'Status surat berhasil diperbarui.');
                }
                
                return back()->with('error', 'Tidak ada perubahan yang dilakukan.');
            } else {
                return back()->with('error', 'Fitur tidak tersedia saat ini.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
                $filePath = $request->file('berkas')->store('surat_berkas');
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
                    Storage::delete($surat->file_berkas);
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
        // Add debug logging
        Log::info('Kehilangan form submission started', [
            'user_id' => auth()->id(),
            'request_data' => $request->all()
        ]);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|size:16',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'jenis_barang' => 'required|string|max:255',
            'waktu_tempat' => 'required|string',
            'ktp_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_ktp_pelapor' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'bukti_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_laporan_polisi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'foto_file' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_dokumen_tambahan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            if (!auth()->check()) {
                Log::warning('Unauthenticated access to kehilangan submit');
                return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu untuk mengajukan surat.');
            }

            Log::info('User authenticated, checking existing requests', ['user_id' => auth()->id()]);

            $existingRequest = \App\Models\SuratKehilangan::where('user_id', auth()->id())
                ->where('status', 'diproses')
                ->first();

            if ($existingRequest) {
                Log::warning('User has existing pending request', ['user_id' => auth()->id(), 'existing_id' => $existingRequest->id]);
                return redirect()->back()->with('error', 'Anda masih memiliki permohonan surat kehilangan yang sedang diproses. Silakan tunggu hingga selesai sebelum mengajukan permohonan baru.');
            }

            Log::info('Processing file uploads if any');

            $fileData = [];
            
            if ($request->hasFile('ktp_file') || $request->hasFile('file_ktp_pelapor')) {
                try {
                    $file = $request->file('ktp_file') ?? $request->file('file_ktp_pelapor');
                    $fileData['file_ktp_pelapor'] = FileUploadHelper::uploadFile($file, 'kehilangan/ktp');
                } catch (\Exception $e) {
                    Log::error('KTP file upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('bukti_file') || $request->hasFile('file_laporan_polisi')) {
                try {
                    $file = $request->file('bukti_file') ?? $request->file('file_laporan_polisi');
                    $fileData['file_laporan_polisi'] = $file->store('kehilangan/laporan_polisi');
                    Log::info('Laporan polisi file uploaded', ['path' => $fileData['file_laporan_polisi']]);
                } catch (\Exception $e) {
                    Log::error('Laporan polisi file upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('foto_file') || $request->hasFile('file_kk')) {
                try {
                    $file = $request->file('foto_file') ?? $request->file('file_kk');
                    $fileData['file_kk'] = $file->store('kehilangan/kk');
                    Log::info('KK file uploaded', ['path' => $fileData['file_kk']]);
                } catch (\Exception $e) {
                    Log::error('KK file upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_dokumen_tambahan')) {
                try {
                    $fileData['file_dokumen_tambahan'] = $request->file('file_dokumen_tambahan')->store('kehilangan/dokumen_tambahan');
                    Log::info('Dokumen tambahan file uploaded', ['path' => $fileData['file_dokumen_tambahan']]);
                } catch (\Exception $e) {
                    Log::error('Dokumen tambahan file upload failed: ' . $e->getMessage());
                }
            }

            // Parse the waktu_tempat to extract meaningful data
            $waktuTempat = $validated['waktu_tempat'];
            // Try to extract a time like "15.00" or "15:00" (optionally preceded by the word 'pukul')
            $parsedTime = null;
            try {
                if (preg_match('/pukul\s*(\d{1,2})[\.:](\d{2})/i', $waktuTempat, $m)
                    || preg_match('/\b(\d{1,2})[\.:](\d{2})\s*(WIB|WITA|WIT)?\b/i', $waktuTempat, $m)) {
                    $hour = max(0, min(23, intval($m[1])));
                    $minute = max(0, min(59, intval($m[2])));
                    $parsedTime = sprintf('%02d:%02d:00', $hour, $minute);
                }
            } catch (\Exception $e) {
                Log::warning('Failed parsing waktu from kronologi', ['error' => $e->getMessage()]);
            }
            
            Log::info('Initializing verification stages');
            
            // Initialize verification stages using VerificationStageService
            $stages = null;
            try {
                $stages = VerificationStageService::initializeStages('kehilangan');
            } catch (\Exception $e) {
                Log::warning('VerificationStageService failed, using default stages: ' . $e->getMessage());
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
            
            Log::info('Creating kehilangan record with data', [
                'nama_lengkap' => $validated['nama'],
                'nik' => $validated['nik'],
                'user_id' => auth()->id(),
                'file_data' => $fileData
            ]);
            
            // Create the kehilangan record using mass assignment
            $kehilangan = \App\Models\SuratKehilangan::create(array_merge([
                'nama_lengkap' => $validated['nama'],
                'nik' => $validated['nik'],
                'jenis_kelamin' => 'Tidak Diisi', // Default value
                'tempat_lahir' => $validated['tempat_lahir'],
                // normalize to date string to avoid strict mode issues
                'tanggal_lahir' => Carbon::parse($validated['tanggal_lahir'])->toDateString(),
                'agama' => 'Tidak Diisi', // Default value
                'status_perkawinan' => 'Tidak Diisi',
                'kewarganegaraan' => 'WNI', 
                'pekerjaan' => 'Tidak Diisi', 
                'alamat' => $validated['alamat'],
                'barang_hilang' => $validated['jenis_barang'],
                'deskripsi_barang' => $validated['jenis_barang'], 
                'tempat_kehilangan' => 'Diisi dalam kronologi', 
                'tanggal_kehilangan' => now()->toDateString(),
                // only store a valid SQL TIME; otherwise keep null
                'waktu_kehilangan' => $parsedTime,
                'kronologi_kehilangan' => $waktuTempat,
                'keperluan' => 'Pembuatan surat keterangan kehilangan',
                'status' => 'diproses',
                'user_id' => auth()->id(),
                'tahapan_verifikasi' => $stages
            ], $fileData));

            Log::info('Kehilangan record created successfully', ['id' => $kehilangan->id]);

            return redirect()->back()->with('success', 'Permohonan surat keterangan kehilangan berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');
            
        } catch (\Exception $e) {
            Log::error('Error saving kehilangan form: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id() ?? 'guest'
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }

    public function ktpSubmit(Request $request)
    {
        // Debug: Log the request
        Log::info('KTP Form submission started', [
            'request_data' => $request->except(['file_ktp', 'file_kk', 'file_dokumen_tambahan']),
            'has_files' => [
                'file_ktp' => $request->hasFile('file_ktp'),
                'file_kk' => $request->hasFile('file_kk'),
                'file_dokumen_tambahan' => $request->hasFile('file_dokumen_tambahan'),
            ],
            'user_authenticated' => auth()->check(),
            'user_id' => auth()->id()
        ]);

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
            // File upload validation
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_dokumen_tambahan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        Log::info('KTP Form validation passed', ['validated_data' => $validated]);

        try {
            // Check if user is logged in
            if (!auth()->check()) {
                Log::warning('KTP Form: User not authenticated');
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengajukan permohonan surat.');
            }

            // Check for existing pending request
            $existingRequest = SuratKtp::where('user_id', auth()->id())
                ->whereIn('status', ['menunggu', 'diproses'])
                ->first();

            if ($existingRequest) {
                Log::info('KTP Form: User has existing pending request', ['existing_id' => $existingRequest->id]);
                return redirect()->back()->with('error', 'Anda masih memiliki permohonan surat keterangan KTP yang sedang diproses. Silakan tunggu hingga selesai sebelum mengajukan permohonan baru.');
            }

            // Handle file uploads
            $fileData = [];
            
            if ($request->hasFile('file_ktp')) {
                try {
                    $fileData['file_ktp'] = $request->file('file_ktp')->store('ktp/ktp');
                    Log::info('KTP file uploaded successfully', ['path' => $fileData['file_ktp']]);
                } catch (\Exception $e) {
                    Log::error('File KTP upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_kk')) {
                try {
                    $fileData['file_kk'] = $request->file('file_kk')->store('ktp/kk');
                    Log::info('KK file uploaded successfully', ['path' => $fileData['file_kk']]);
                } catch (\Exception $e) {
                    Log::error('File KK upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_dokumen_tambahan')) {
                try {
                    $fileData['file_dokumen_tambahan'] = $request->file('file_dokumen_tambahan')->store('ktp/dokumen_tambahan');
                    Log::info('Dokumen tambahan file uploaded successfully', ['path' => $fileData['file_dokumen_tambahan']]);
                } catch (\Exception $e) {
                    Log::error('File dokumen tambahan upload failed: ' . $e->getMessage());
                }
            }

            // Create KTP request record
            $createData = array_merge([
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
            ], $fileData);

            Log::info('KTP Form: Creating record with data', ['create_data' => $createData]);

            $suratKtp = SuratKtp::create($createData);

            Log::info('KTP Form: Record created successfully', ['surat_id' => $suratKtp->id]);

            // Initialize verification stages if the service exists
            if (class_exists('App\\Services\\VerificationStageService')) {
                try {
                    $stages = VerificationStageService::initializeStages('ktp');
                    $suratKtp->tahapan_verifikasi = json_encode($stages);
                    $suratKtp->save();
                    Log::info('KTP Form: Verification stages initialized', ['stages' => $stages]);
                } catch (\Exception $e) {
                    // Log the error but don't fail the request
                    Log::warning('Failed to initialize verification stages for KTP: ' . $e->getMessage());
                }
            }

            Log::info('KTP Form: Submission completed successfully', ['surat_id' => $suratKtp->id]);
            return redirect()->back()->with('success', 'Permohonan surat keterangan KTP berhasil dikirim. Silakan cek status permohonan Anda secara berkala.');

        } catch (\Exception $e) {
            Log::error('Error creating KTP request: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'request_data' => $request->except(['file_ktp', 'file_kk', 'file_dokumen_tambahan'])
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses permohonan. Silakan coba lagi. Error: ' . $e->getMessage());
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
            // File upload validation
            'file_ktp_pelapor' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_surat_dokter' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_dokumen_tambahan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengajukan permohonan surat.');
            }

            $existingRequest = \App\Models\SuratKematian::where('user_id', auth()->id())
                ->where('status', 'diproses')
                ->first();

            if ($existingRequest) {
                return redirect()->back()->with('error', 'Anda masih memiliki permohonan surat keterangan kematian yang sedang diproses. Silakan tunggu hingga selesai sebelum mengajukan permohonan baru.');
            }

            $fileData = [];
            
            if ($request->hasFile('file_ktp_pelapor')) {
                try {
                    $fileData['file_ktp_pelapor'] = $request->file('file_ktp_pelapor')->store('kematian/ktp');
                    Log::info('Kematian - KTP Pelapor uploaded successfully', ['path' => $fileData['file_ktp_pelapor']]);
                } catch (\Exception $e) {
                    Log::error('Kematian - KTP Pelapor upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_kk')) {
                try {
                    $fileData['file_kk'] = $request->file('file_kk')->store('kematian/kk');
                    Log::info('Kematian - KK uploaded successfully', ['path' => $fileData['file_kk']]);
                } catch (\Exception $e) {
                    Log::error('Kematian - KK upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_surat_dokter')) {
                try {
                    $fileData['file_surat_dokter'] = $request->file('file_surat_dokter')->store('kematian/surat_dokter');
                    Log::info('Kematian - Surat Dokter uploaded successfully', ['path' => $fileData['file_surat_dokter']]);
                } catch (\Exception $e) {
                    Log::error('Kematian - Surat Dokter upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_dokumen_tambahan')) {
                try {
                    $fileData['file_dokumen_tambahan'] = $request->file('file_dokumen_tambahan')->store('kematian/dokumen_tambahan');
                    Log::info('Kematian - Dokumen Tambahan uploaded successfully', ['path' => $fileData['file_dokumen_tambahan']]);
                } catch (\Exception $e) {
                    Log::error('Kematian - Dokumen Tambahan upload failed: ' . $e->getMessage());
                }
            }

            // Simpan data ke database
            $createData = array_merge([
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
            ], $fileData);

            \App\Models\SuratKematian::create($createData);

            return redirect()->back()->with('success', 'Permohonan surat keterangan kematian berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');

        } catch (\Exception $e) {
            Log::error('Error saving kematian form: ' . $e->getMessage());
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
            // File upload validation
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kk_lama' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_dokumen_tambahan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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

            // Handle file uploads
            $fileData = [];
            
            if ($request->hasFile('file_ktp')) {
                try {
                    $fileData['file_ktp'] = $request->file('file_ktp')->store('kk/ktp');
                    Log::info('KK - KTP file uploaded successfully', ['path' => $fileData['file_ktp']]);
                } catch (\Exception $e) {
                    Log::error('KK - KTP file upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_kk_lama')) {
                try {
                    $fileData['file_kk_lama'] = $request->file('file_kk_lama')->store('kk/kk_lama');
                    Log::info('KK - KK lama file uploaded successfully', ['path' => $fileData['file_kk_lama']]);
                } catch (\Exception $e) {
                    Log::error('KK - KK lama file upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_pendukung')) {
                try {
                    $pendukungPaths = [];
                    foreach ($request->file('file_dokumen_pendukung') as $file) {
                        if ($file && $file->isValid()) {
                            $pendukungPaths[] = $file->store('kk/surat_dokumen_pendukung');
                        }
                    }
                    if (!empty($pendukungPaths)) {
                        $fileData['file_pendukung'] = json_encode($pendukungPaths);
                        Log::info('KK - Pendukung files uploaded successfully', ['paths' => $pendukungPaths]);
                    }
                } catch (\Exception $e) {
                    Log::error('KK - Pendukung files upload failed: ' . $e->getMessage());
                }
            }

            // Simpan data ke database
            $createData = array_merge([
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
            ], $fileData);

            \App\Models\SuratKk::create($createData);

            return redirect()->back()->with('success', 'Permohonan surat keterangan KK berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');

        } catch (\Exception $e) {
            Log::error('Error saving KK form: ' . $e->getMessage());
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
            // File upload validation
            'file_ktp_ayah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_ktp_ibu' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_dokumen_tambahan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_surat_nikah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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

            // Handle file uploads
            $fileData = [];
            
            if ($request->hasFile('file_ktp_ayah')) {
                try {
                    $fileData['file_ktp_ayah'] = $request->file('file_ktp_ayah')->store('kelahiran/ktp_ayah');
                    Log::info('Kelahiran - KTP Ayah uploaded successfully', ['path' => $fileData['file_ktp_ayah']]);
                } catch (\Exception $e) {
                    Log::error('Kelahiran - KTP Ayah upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_ktp_ibu')) {
                try {
                    $fileData['file_ktp_ibu'] = $request->file('file_ktp_ibu')->store('kelahiran/ktp_ibu');
                    Log::info('Kelahiran - KTP Ibu uploaded successfully', ['path' => $fileData['file_ktp_ibu']]);
                } catch (\Exception $e) {
                    Log::error('Kelahiran - KTP Ibu upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_kk')) {
                try {
                    $fileData['file_kk'] = $request->file('file_kk')->store('kelahiran/kk');
                    Log::info('Kelahiran - KK uploaded successfully', ['path' => $fileData['file_kk']]);
                } catch (\Exception $e) {
                    Log::error('Kelahiran - KK upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_dokumen_tambahan')) {
                try {
                    $fileData['file_dokumen_tambahan'] = $request->file('file_dokumen_tambahan')->store('kelahiran/dokumen_tambahan');
                    Log::info('Kelahiran - Dokumen Tambahan uploaded successfully', ['path' => $fileData['file_dokumen_tambahan']]);
                } catch (\Exception $e) {
                    Log::error('Kelahiran - Dokumen Tambahan upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_surat_nikah')) {
                try {
                    $fileData['file_surat_nikah'] = $request->file('file_surat_nikah')->store('kelahiran/surat_nikah');
                    Log::info('Kelahiran - Surat Nikah uploaded successfully', ['path' => $fileData['file_surat_nikah']]);
                } catch (\Exception $e) {
                    Log::error('Kelahiran - Surat Nikah upload failed: ' . $e->getMessage());
                }
            }

            // Simpan data ke database
            $createData = array_merge([
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
            ], $fileData);

            \App\Models\SuratKelahiran::create($createData);

            return redirect()->back()->with('success', 'Permohonan surat keterangan kelahiran berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');

        } catch (\Exception $e) {
            Log::error('Error saving kelahiran form: ' . $e->getMessage());
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
            // File upload validation
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_pas_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
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

            // Handle file uploads
            $fileData = [];
            
            if ($request->hasFile('file_ktp')) {
                try {
                    $fileData['file_ktp'] = $request->file('file_ktp')->store('skck/ktp');
                    Log::info('SKCK - KTP file uploaded successfully', ['path' => $fileData['file_ktp']]);
                } catch (\Exception $e) {
                    Log::error('SKCK - KTP file upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_kk')) {
                try {
                    $fileData['file_kk'] = $request->file('file_kk')->store('skck/kk');
                    Log::info('SKCK - KK file uploaded successfully', ['path' => $fileData['file_kk']]);
                } catch (\Exception $e) {
                    Log::error('SKCK - KK file upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_pas_foto')) {
                try {
                    $fileData['file_pas_foto'] = $request->file('file_pas_foto')->store('skck/pas_foto');
                    Log::info('SKCK - Pas foto uploaded successfully', ['path' => $fileData['file_pas_foto']]);
                } catch (\Exception $e) {
                    Log::error('SKCK - Pas foto upload failed: ' . $e->getMessage());
                }
            }

            // Simpan data ke database
            $createData = array_merge([
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
            ], $fileData);

            SuratSkck::create($createData);

            return redirect()->back()->with('success', 'Permohonan Surat Pengantar SKCK berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');

        } catch (\Exception $e) {
            // Log the actual error for debugging
            Log::error('SKCK Submission Error: ' . $e->getMessage(), [
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
            // File upload validation
            'ktp_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'kk_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'akta_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Handle file uploads
        $fileData = [];
        
        if ($request->hasFile('ktp_file')) {
            try {
                $fileData['file_ktp'] = $request->file('ktp_file')->store('belum_menikah/ktp');
                Log::info('Belum Menikah - KTP file uploaded successfully', ['path' => $fileData['file_ktp']]);
            } catch (\Exception $e) {
                Log::error('Belum Menikah - KTP file upload failed: ' . $e->getMessage());
            }
        }
        
        if ($request->hasFile('kk_file')) {
            try {
                $fileData['file_kk'] = $request->file('kk_file')->store('belum_menikah/kk');
                Log::info('Belum Menikah - KK file uploaded successfully', ['path' => $fileData['file_kk']]);
            } catch (\Exception $e) {
                Log::error('Belum Menikah - KK file upload failed: ' . $e->getMessage());
            }
        }
        
        if ($request->hasFile('akta_file')) {
            try {
                $fileData['file_akta'] = $request->file('akta_file')->store('belum_menikah/akta');
                Log::info('Belum Menikah - Akta file uploaded successfully', ['path' => $fileData['file_akta']]);
            } catch (\Exception $e) {
                Log::error('Belum Menikah - Akta file upload failed: ' . $e->getMessage());
            }
        }

        $createData = array_merge([
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
            'tahapan_verifikasi' => json_encode(\App\Services\VerificationStageService::initializeStages('belum_menikah')),
        ], $fileData);

        BelumMenikah::create($createData);

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
        // Add debug logging
        Log::info('Domisili form submission started', [
            'user_id' => auth()->id(),
            'request_data' => $request->except(['file_ktp', 'file_kk', 'file_pengantar_rt', 'file_dokumen_tambahan'])
        ]);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'kewarganegaraan' => 'required|in:WNI,WNA',
            'status' => 'required|in:Kawin,Belum Kawin,Cerai Hidup,Cerai Mati',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'keperluan' => 'required|string',
            // File upload validation
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_pengantar_rt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_dokumen_tambahan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        Log::info('Domisili form validation passed');

        try {
            // Get authenticated user ID with fallback
            $userId = auth()->id();
            if (!$userId) {
                Log::warning('No authenticated user found, using fallback');
                // For testing purposes, create or use a default user
                $defaultUser = \App\Models\User::firstOrCreate(
                    ['email' => 'guest@desaganten.com'],
                    [
                        'name' => 'Guest User', 
                        'password' => bcrypt('password'),
                        'role' => 'user'
                    ]
                );
                $userId = $defaultUser->id;
                Log::info('Using fallback user', ['user_id' => $userId]);
            }

            Log::info('User authenticated, proceeding with file uploads');

            // Handle file uploads
            $fileData = [];
            
            if ($request->hasFile('file_ktp')) {
                try {
                    $fileData['file_ktp'] = $request->file('file_ktp')->store('domisili/ktp', 'public');
                    Log::info('KTP file uploaded successfully', ['path' => $fileData['file_ktp']]);
                } catch (\Exception $e) {
                    Log::error('File KTP upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_kk')) {
                try {
                    $fileData['file_kk'] = $request->file('file_kk')->store('domisili/kk', 'public');
                    Log::info('KK file uploaded successfully', ['path' => $fileData['file_kk']]);
                } catch (\Exception $e) {
                    Log::error('File KK upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_pengantar_rt')) {
                try {
                    $fileData['file_pengantar_rt'] = $request->file('file_pengantar_rt')->store('domisili/pengantar_rt', 'public');
                    Log::info('Pengantar RT file uploaded successfully', ['path' => $fileData['file_pengantar_rt']]);
                } catch (\Exception $e) {
                    Log::error('File pengantar RT upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_dokumen_tambahan')) {
                try {
                    $fileData['file_dokumen_tambahan'] = $request->file('file_dokumen_tambahan')->store('domisili/dokumen_tambahan', 'public');
                    Log::info('Dokumen tambahan file uploaded successfully', ['path' => $fileData['file_dokumen_tambahan']]);
                } catch (\Exception $e) {
                    Log::error('File dokumen tambahan upload failed: ' . $e->getMessage());
                }
            }

            Log::info('Files processed, creating domisili record');

            // Initialize verification stages safely
            $stages = null;
            try {
                if (class_exists('App\\Services\\VerificationStageService')) {
                    $stages = VerificationStageService::initializeStages('domisili');
                    Log::info('VerificationStageService used successfully', ['stages' => $stages]);
                }
            } catch (\Exception $e) {
                Log::warning('VerificationStageService failed, using default: ' . $e->getMessage());
            }

            // If verification service fails, use default stages
            if (!$stages || empty($stages)) {
                $stages = [
                    'tahap_1' => ['nama' => 'Verifikasi Dokumen', 'status' => 'menunggu', 'tanggal' => null],
                    'tahap_2' => ['nama' => 'Pemrosesan', 'status' => 'menunggu', 'tanggal' => null],
                    'tahap_3' => ['nama' => 'Selesai', 'status' => 'menunggu', 'tanggal' => null]
                ];
                Log::info('Using default verification stages', ['stages' => $stages]);
            }

            // Create domisili record with file paths
            $createData = array_merge([
                'user_id' => $userId,
                'nama' => $request->nama,
                'nik' => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin, // Already in correct format (L or P)
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'agama' => $request->agama,
                'kewarganegaraan' => $request->kewarganegaraan,
                'status' => $request->status,
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
                'keperluan' => $request->keperluan,
                'status_pengajuan' => 'menunggu',
                'tahapan_verifikasi' => json_encode($stages),
            ], $fileData);

            Log::info('Creating domisili record with data', ['data' => array_merge($createData, ['tahapan_verifikasi' => $stages])]);

            $domisiliRecord = \App\Models\Domisili::create($createData);

            Log::info('Domisili record created successfully', ['id' => $domisiliRecord->id]);

            return redirect()->back()->with('success', 'Permohonan surat keterangan domisili berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');

        } catch (\Exception $e) {
            Log::error('Error saving domisili form: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id() ?? 'guest'
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi. Error: ' . $e->getMessage());
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
            // File upload validation
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_foto_usaha' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu untuk mengajukan surat.');
            }

            // Handle file uploads
            $fileData = [];
            
            if ($request->hasFile('file_ktp')) {
                try {
                    $fileData['file_ktp'] = $request->file('file_ktp')->store('usaha/ktp');
                    Log::info('Usaha - KTP file uploaded successfully', ['path' => $fileData['file_ktp']]);
                } catch (\Exception $e) {
                    Log::error('Usaha - KTP file upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_kk')) {
                try {
                    $fileData['file_kk'] = $request->file('file_kk')->store('usaha/kk');
                    Log::info('Usaha - KK file uploaded successfully', ['path' => $fileData['file_kk']]);
                } catch (\Exception $e) {
                    Log::error('Usaha - KK file upload failed: ' . $e->getMessage());
                }
            }
            
            if ($request->hasFile('file_foto_usaha')) {
                try {
                    $fileData['file_foto_usaha'] = $request->file('file_foto_usaha')->store('usaha/foto_usaha');
                    Log::info('Usaha - Foto usaha uploaded successfully', ['path' => $fileData['file_foto_usaha']]);
                } catch (\Exception $e) {
                    Log::error('Usaha - Foto usaha upload failed: ' . $e->getMessage());
                }
            }

            // Create usaha record
            $createData = array_merge([
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
            ], $fileData);

            \App\Models\SuratUsaha::create($createData);

            return redirect()->back()->with('success', 'Permohonan surat keterangan usaha berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');

        } catch (\Exception $e) {
            Log::error('Error saving usaha form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }
}
