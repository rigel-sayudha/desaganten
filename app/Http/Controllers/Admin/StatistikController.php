<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatistikPekerjaan;
use App\Models\StatistikUmur;
use App\Models\StatistikPendidikan;
use App\Models\StatistikWilayah;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalPekerjaan = StatistikPekerjaan::active()->count();
        $totalUmur = StatistikUmur::active()->count();
        $totalPendidikan = StatistikPendidikan::active()->count();
        $totalWilayah = StatistikWilayah::active()->count();
        
        return view('admin.statistik.index', compact(
            'totalPekerjaan', 
            'totalUmur', 
            'totalPendidikan',
            'totalWilayah'
        ));
    }

    // ========== PEKERJAAN METHODS ==========
    public function pekerjaan()
    {
        $data = StatistikPekerjaan::active()->get();
        
        // Get population totals from Wilayah data
        $wilayah = Wilayah::all();
        $totalPenduduk = $wilayah->sum('jumlah');
        $totalLakiLaki = $wilayah->sum('laki_laki');
        $totalPerempuan = $wilayah->sum('perempuan');
        $totalKK = intval($totalPenduduk * 0.28); // Estimasi KK (28% dari total penduduk)
        
        return view('admin.statistik.pekerjaan.index', compact(
            'data', 
            'totalPenduduk', 
            'totalLakiLaki', 
            'totalPerempuan', 
            'totalKK'
        ));
    }

    public function createPekerjaan()
    {
        return view('admin.statistik.pekerjaan.create');
    }

    public function storePekerjaan(Request $request)
    {
        $request->validate([
            'nama_pekerjaan' => 'required|string|max:255',
            'laki_laki' => 'required|integer|min:0',
            'perempuan' => 'required|integer|min:0',
            'keterangan' => 'nullable|string'
        ]);

        StatistikPekerjaan::create($request->all());

        return redirect()->route('admin.statistik.pekerjaan')
            ->with('success', 'Data pekerjaan berhasil ditambahkan.');
    }

    public function editPekerjaan(StatistikPekerjaan $pekerjaan)
    {
        return view('admin.statistik.pekerjaan.edit', compact('pekerjaan'));
    }

    public function updatePekerjaan(Request $request, StatistikPekerjaan $pekerjaan)
    {
        $request->validate([
            'nama_pekerjaan' => 'required|string|max:255',
            'laki_laki' => 'required|integer|min:0',
            'perempuan' => 'required|integer|min:0',
            'keterangan' => 'nullable|string'
        ]);

        $pekerjaan->update($request->all());

        return redirect()->route('admin.statistik.pekerjaan')
            ->with('success', 'Data pekerjaan berhasil diupdate.');
    }

    public function destroyPekerjaan(StatistikPekerjaan $pekerjaan)
    {
        $pekerjaan->update(['is_active' => false]);

        return redirect()->route('admin.statistik.pekerjaan')
            ->with('success', 'Data pekerjaan berhasil dihapus.');
    }

    // ========== UMUR METHODS ==========
    public function umur()
    {
        $data = StatistikUmur::active()->orderBy('usia_min')->get();
        return view('admin.statistik.umur.index', compact('data'));
    }

    public function createUmur()
    {
        return view('admin.statistik.umur.create');
    }

    public function storeUmur(Request $request)
    {
        $request->validate([
            'kelompok_umur' => 'required|string|max:255',
            'usia_min' => 'required|integer|min:0',
            'usia_max' => 'nullable|integer|min:0|gte:usia_min',
            'laki_laki' => 'required|integer|min:0',
            'perempuan' => 'required|integer|min:0',
            'keterangan' => 'nullable|string'
        ]);

        StatistikUmur::create($request->all());

        return redirect()->route('admin.statistik.umur')
            ->with('success', 'Data umur berhasil ditambahkan.');
    }

    public function editUmur(StatistikUmur $umur)
    {
        return view('admin.statistik.umur.edit', compact('umur'));
    }

    public function updateUmur(Request $request, StatistikUmur $umur)
    {
        $request->validate([
            'kelompok_umur' => 'required|string|max:255',
            'usia_min' => 'required|integer|min:0',
            'usia_max' => 'nullable|integer|min:0|gte:usia_min',
            'laki_laki' => 'required|integer|min:0',
            'perempuan' => 'required|integer|min:0',
            'keterangan' => 'nullable|string'
        ]);

        $umur->update($request->all());

        return redirect()->route('admin.statistik.umur')
            ->with('success', 'Data umur berhasil diupdate.');
    }

    public function destroyUmur(StatistikUmur $umur)
    {
        $umur->update(['is_active' => false]);

        return redirect()->route('admin.statistik.umur')
            ->with('success', 'Data umur berhasil dihapus.');
    }

    // ========== PENDIDIKAN METHODS ==========
    public function pendidikan()
    {
        $data = StatistikPendidikan::active()->get();
        return view('admin.statistik.pendidikan.index', compact('data'));
    }

    public function createPendidikan()
    {
        return view('admin.statistik.pendidikan.create');
    }

    public function storePendidikan(Request $request)
    {
        $request->validate([
            'tingkat_pendidikan' => 'required|string|max:255',
            'urutan' => 'required|integer|min:0',
            'laki_laki' => 'required|integer|min:0',
            'perempuan' => 'required|integer|min:0',
            'keterangan' => 'nullable|string'
        ]);

        StatistikPendidikan::create($request->all());

        return redirect()->route('admin.statistik.pendidikan')
            ->with('success', 'Data pendidikan berhasil ditambahkan.');
    }

    public function editPendidikan(StatistikPendidikan $pendidikan)
    {
        return view('admin.statistik.pendidikan.edit', compact('pendidikan'));
    }

    public function updatePendidikan(Request $request, StatistikPendidikan $pendidikan)
    {
        $request->validate([
            'tingkat_pendidikan' => 'required|string|max:255',
            'urutan' => 'required|integer|min:0',
            'laki_laki' => 'required|integer|min:0',
            'perempuan' => 'required|integer|min:0',
            'keterangan' => 'nullable|string'
        ]);

        $pendidikan->update($request->all());

        return redirect()->route('admin.statistik.pendidikan')
            ->with('success', 'Data pendidikan berhasil diupdate.');
    }

    public function destroyPendidikan(StatistikPendidikan $pendidikan)
    {
        $pendidikan->update(['is_active' => false]);

        return redirect()->route('admin.statistik.pendidikan')
            ->with('success', 'Data pendidikan berhasil dihapus.');
    }

    /**
     * Create a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
