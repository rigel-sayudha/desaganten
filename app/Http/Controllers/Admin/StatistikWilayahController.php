<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatistikWilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatistikWilayahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = StatistikWilayah::active()->ordered()->get();
        $statistics = StatistikWilayah::getTotalStatistics();
        
        return view('admin.statistik.wilayah.index', compact('data', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisWilayahOptions = StatistikWilayah::getJenisWilayahOptions();
        
        return view('admin.statistik.wilayah.create', compact('jenisWilayahOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_wilayah' => 'required|string|max:255',
            'jenis_wilayah' => 'required|string|max:100',
            'laki_laki' => 'required|integer|min:0',
            'perempuan' => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ], [
            'nama_wilayah.required' => 'Nama wilayah wajib diisi',
            'nama_wilayah.max' => 'Nama wilayah maksimal 255 karakter',
            'jenis_wilayah.required' => 'Jenis wilayah wajib dipilih',
            'laki_laki.required' => 'Jumlah laki-laki wajib diisi',
            'laki_laki.integer' => 'Jumlah laki-laki harus berupa angka',
            'laki_laki.min' => 'Jumlah laki-laki tidak boleh negatif',
            'perempuan.required' => 'Jumlah perempuan wajib diisi',
            'perempuan.integer' => 'Jumlah perempuan harus berupa angka',
            'perempuan.min' => 'Jumlah perempuan tidak boleh negatif',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            StatistikWilayah::create([
                'nama_wilayah' => $request->nama_wilayah,
                'jenis_wilayah' => $request->jenis_wilayah,
                'laki_laki' => $request->laki_laki,
                'perempuan' => $request->perempuan,
                'keterangan' => $request->keterangan,
                'is_active' => $request->boolean('is_active', true)
            ]);

            return redirect()->route('admin.statistik.wilayah.index')
                ->with('success', 'Data wilayah berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan data wilayah: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StatistikWilayah $wilayah)
    {
        return view('admin.statistik.wilayah.show', compact('wilayah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StatistikWilayah $wilayah)
    {
        $jenisWilayahOptions = StatistikWilayah::getJenisWilayahOptions();
        
        return view('admin.statistik.wilayah.edit', compact('wilayah', 'jenisWilayahOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StatistikWilayah $wilayah)
    {
        $validator = Validator::make($request->all(), [
            'nama_wilayah' => 'required|string|max:255',
            'jenis_wilayah' => 'required|string|max:100',
            'laki_laki' => 'required|integer|min:0',
            'perempuan' => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ], [
            'nama_wilayah.required' => 'Nama wilayah wajib diisi',
            'nama_wilayah.max' => 'Nama wilayah maksimal 255 karakter',
            'jenis_wilayah.required' => 'Jenis wilayah wajib dipilih',
            'laki_laki.required' => 'Jumlah laki-laki wajib diisi',
            'laki_laki.integer' => 'Jumlah laki-laki harus berupa angka',
            'laki_laki.min' => 'Jumlah laki-laki tidak boleh negatif',
            'perempuan.required' => 'Jumlah perempuan wajib diisi',
            'perempuan.integer' => 'Jumlah perempuan harus berupa angka',
            'perempuan.min' => 'Jumlah perempuan tidak boleh negatif',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $wilayah->update([
                'nama_wilayah' => $request->nama_wilayah,
                'jenis_wilayah' => $request->jenis_wilayah,
                'laki_laki' => $request->laki_laki,
                'perempuan' => $request->perempuan,
                'keterangan' => $request->keterangan,
                'is_active' => $request->boolean('is_active', true)
            ]);

            return redirect()->route('admin.statistik.wilayah.index')
                ->with('success', 'Data wilayah berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data wilayah: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StatistikWilayah $wilayah)
    {
        try {
            $wilayah->delete();
            
            return redirect()->route('admin.statistik.wilayah.index')
                ->with('success', 'Data wilayah berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data wilayah: ' . $e->getMessage());
        }
    }
}
