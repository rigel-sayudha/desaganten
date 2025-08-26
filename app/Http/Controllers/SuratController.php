<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\BelumMenikah;
use App\Models\TidakMampu;
use App\Models\SuratSkck;
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
        $request->validate([
            'nama' => 'required',
            'nik' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'jenis_barang' => 'required',
            'waktu_tempat' => 'required',
        ]);
        return redirect()->back()->with('success', 'Permohonan surat keterangan kehilangan berhasil dikirim.');
    }

    public function ktpSubmit(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'status_perkawinan' => 'required',
            'nik' => 'required|digits:16',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'pekerjaan' => 'required',
            'alamat' => 'required',
            'keperluan' => 'required',
        ]);
        return redirect()->back()->with('success', 'Permohonan surat keterangan KTP berhasil dikirim.');
    }

    public function kematianSubmit(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'kewarganegaraan' => 'required',
            'agama' => 'required',
            'status_perkawinan' => 'required',
            'pekerjaan' => 'required',
            'alamat' => 'required',
            'rt_rw' => 'required',
            'hari' => 'required',
            'tanggal_meninggal' => 'required',
            'tempat_kematian' => 'required',
            'kecamatan' => 'required',
            'kabupaten' => 'required',
            'provinsi' => 'required',
            'sebab_kematian' => 'required',
        ]);
        return redirect()->back()->with('success', 'Permohonan surat keterangan kematian berhasil dikirim.');
    }

    public function kkSubmit(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'status_perkawinan' => 'required',
            'nik' => 'required|digits:16',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'pekerjaan' => 'required',
            'alamat' => 'required',
            'keperluan' => 'required',
        ]);
        return redirect()->back()->with('success', 'Permohonan surat keterangan KK berhasil dikirim.');
    }

    public function kelahiranSubmit(Request $request)
    {
        $request->validate([
            'nama_anak' => 'required',
            'anak_ke' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir_anak' => 'required',
            'alamat_anak' => 'required',
            'penolong_kelahiran' => 'required',
            'alamat_bidan' => 'required',
            'ibu_nik' => 'required',
            'ibu_nama' => 'required',
            'ibu_tempat_lahir' => 'required',
            'ibu_tanggal_lahir' => 'required',
            'ibu_alamat' => 'required',
        ]);
        return redirect()->back()->with('success', 'Permohonan surat keterangan kelahiran berhasil dikirim.');
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
                ->where('status', 'menunggu')
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
                'status' => 'menunggu',
                'user_id' => auth()->id(),
                'tahapan_verifikasi' => [
                    'rt' => ['status' => 'menunggu', 'tanggal' => null, 'catatan' => null],
                    'rw' => ['status' => 'menunggu', 'tanggal' => null, 'catatan' => null],
                    'kelurahan' => ['status' => 'menunggu', 'tanggal' => null, 'catatan' => null]
                ]
            ]);

            return redirect()->back()->with('success', 'Permohonan Surat Pengantar SKCK berhasil dikirim. Tim verifikasi akan segera memproses permohonan Anda.');

        } catch (\Exception $e) {
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
            'pernyataan' => 'required|accepted',
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
}
