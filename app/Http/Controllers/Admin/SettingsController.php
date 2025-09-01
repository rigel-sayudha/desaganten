<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'kepala_desa_nama' => Setting::get('kepala_desa_nama', 'Munadi'),
            'kepala_desa_jabatan' => Setting::get('kepala_desa_jabatan', 'Kepala Desa Ganten'),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'kepala_desa_nama' => 'required|string|max:255',
            'kepala_desa_jabatan' => 'required|string|max:255',
        ]);

        Setting::set('kepala_desa_nama', $request->kepala_desa_nama, 'Nama Kepala Desa untuk ditampilkan di surat keterangan');
        Setting::set('kepala_desa_jabatan', $request->kepala_desa_jabatan, 'Jabatan Kepala Desa untuk ditampilkan di surat keterangan');

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
