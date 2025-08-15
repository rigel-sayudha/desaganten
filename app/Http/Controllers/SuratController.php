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
}
