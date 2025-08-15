<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat;

class SuratController extends Controller
{
    // Menampilkan halaman daftar surat keterangan
    public function index()
    {
        $jenisSurat = [
            'domisili' => 'Surat Keterangan Domisili',
            'usaha' => 'Surat Keterangan Usaha',
            'tidak_mampu' => 'Surat Keterangan Tidak Mampu',
        ];
        return view('admin.surat.index', compact('jenisSurat'));
    }
    // Endpoint untuk preview data pengajuan surat sesuai jenis
    public function previewData($jenis)
    {
        $data = Surat::where('jenis_surat', $jenis)->get()->map(function($item) {
            return [
                'id' => $item->id,
                'nama_pemohon' => $item->nama_pemohon,
                'jenis_surat' => $item->jenis_surat,
                'tanggal_pengajuan' => $item->created_at->format('d-m-Y'),
                'status' => $item->status,
            ];
        });
        return response()->json($data);
    }

    // Endpoint detail surat
    public function detail($id)
    {
        // Coba cari di Surat dulu
        $surat = Surat::find($id);
        if ($surat && $surat->jenis_surat === 'Surat Keterangan Domisili') {
            // Jika surat domisili, ambil dari tabel Domisili
            $surat = \App\Models\Domisili::find($id);
        }
        // Jika tidak ada di Surat, coba cari di Domisili
        if (!$surat) {
            $surat = \App\Models\Domisili::findOrFail($id);
        }
        return view('admin.surat.detail', compact('surat'));
    }
}
