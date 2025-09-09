<?php

namespace App\Http\Controllers;

use App\Models\RekapSuratKeluar;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RekapSuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = RekapSuratKeluar::selesai()->latest();

        // Filter berdasarkan jenis surat
        if ($request->filled('jenis_surat')) {
            $query->where('jenis_surat', $request->jenis_surat);
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_surat', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_surat', $request->tahun);
        }

        $rekapSurat = $query->paginate(20);

        // Hitung statistik berdasarkan filter yang sama
        $statisticQuery = RekapSuratKeluar::query();
        
        if ($request->filled('jenis_surat')) {
            $statisticQuery->where('jenis_surat', $request->jenis_surat);
        }
        
        if ($request->filled('bulan')) {
            $statisticQuery->whereMonth('tanggal_surat', $request->bulan);
        }
        
        if ($request->filled('tahun')) {
            $statisticQuery->whereYear('tanggal_surat', $request->tahun);
        }

        $statistics = [
            'total' => $statisticQuery->count(),
            'selesai' => $statisticQuery->clone()->selesai()->count(),
            'diproses' => $statisticQuery->clone()->diproses()->count(),
            'pending' => $statisticQuery->clone()->pending()->count(),
        ];

        return view('laporan.rekap-surat-keluar', compact('rekapSurat', 'statistics'));
    }
}
