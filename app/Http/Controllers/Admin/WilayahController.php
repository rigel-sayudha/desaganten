<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wilayah;

class WilayahController extends Controller
{
    public function index()
    {
        $wilayah = Wilayah::all();
        return view('admin.wilayah.index', compact('wilayah'));
    }

    public function create()
    {
        return view('admin.wilayah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jumlah' => 'required|integer',
            'laki_laki' => 'required|integer',
            'perempuan' => 'required|integer',
        ]);
        Wilayah::create($request->all());
        return redirect()->route('admin.wilayah.index')->with('success', 'Data wilayah berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $wilayah = Wilayah::findOrFail($id);
        return view('admin.wilayah.edit', compact('wilayah'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'jumlah' => 'required|integer',
            'laki_laki' => 'required|integer',
            'perempuan' => 'required|integer',
        ]);
        $wilayah = Wilayah::findOrFail($id);
        $wilayah->update($request->all());
        return redirect()->route('admin.wilayah.index')->with('success', 'Data wilayah berhasil diupdate.');
    }

    public function destroy($id)
    {
        $wilayah = Wilayah::findOrFail($id);
        $wilayah->delete();
        return redirect()->route('admin.wilayah.index')->with('success', 'Data wilayah berhasil dihapus.');
    }
}
