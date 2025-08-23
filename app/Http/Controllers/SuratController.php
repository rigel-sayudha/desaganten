<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;

class SuratController extends Controller
{
    public function index()
    {
        $surat = Surat::orderBy('created_at', 'desc')->get();
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
        Surat::create($data);
        return redirect('/surat/form')->with('success','Surat berhasil diajukan.');
    }

    public function destroy($id)
    {
        $surat = Surat::findOrFail($id);
        if ($surat->file_berkas) {
            \Storage::disk('public')->delete($surat->file_berkas);
        }
        $surat->delete();
        return back()->with('success','Surat berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $surat = Surat::findOrFail($id);
        $surat->status = $request->status;
        $surat->catatan = $request->catatan;
        $surat->save();
        return back()->with('success','Status surat diperbarui.');
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
        return redirect()->back()->with('success', 'Permohonan surat keterangan SKCK berhasil dikirim.');
    }
}
