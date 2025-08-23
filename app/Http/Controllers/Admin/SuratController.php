<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    // Minimal controller for admin routes

    public function index()
    {
        // Ambil semua surat dari berbagai jenis
        $suratList = collect();
        // Surat Domisili
        $domisili = \App\Models\Domisili::all();
        foreach ($domisili as $item) {
            $suratList->push((object)[
                'id' => $item->id,
                'nama_pemohon' => $item->nama,
                'jenis_surat' => 'domisili',
                'created_at' => $item->created_at,
                'status' => $item->status_pengajuan ?? $item->status,
            ]);
        }
        // Surat KTP
        $ktp = \App\Models\SuratKtp::all();
        foreach ($ktp as $item) {
            $suratList->push((object)[
                'id' => $item->id,
                'nama_pemohon' => $item->nama_lengkap,
                'jenis_surat' => 'ktp',
                'created_at' => $item->created_at,
                'status' => $item->status,
            ]);
        }
        // Surat lain (jika ada model Surat)
        if (class_exists('App\\Models\\Surat')) {
            $surat = \App\Models\Surat::all();
            foreach ($surat as $item) {
                $suratList->push((object)[
                    'id' => $item->id,
                    'nama_pemohon' => $item->nama_pemohon ?? $item->nama ?? '-',
                    'jenis_surat' => $item->jenis_surat,
                    'created_at' => $item->created_at,
                    'status' => $item->status ?? '-',
                ]);
            }
        }
        return view('admin.surat.index', compact('suratList'));
    }

    public function create()
    {
        return view('admin.surat.create');
    }

    public function store(Request $request)
    {
        // Handle store logic based on jenis_surat
        $jenis = $request->input('jenis_surat');
        
        if ($jenis === 'domisili') {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'nik' => 'required|string|max:16',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'agama' => 'required|string|max:255',
                'status_perkawinan' => 'required|string|max:255',
                'pekerjaan' => 'required|string|max:255',
                'alamat' => 'required|string',
                'keperluan' => 'required|string',
            ]);
            
            \App\Models\Domisili::create($validated);
        }
        
        return redirect()->route('admin.surat.index')->with('success', 'Surat berhasil ditambahkan.');
    }

    public function show($id)
    {
        // Find and show specific surat
        $surat = null;
        $jenis = '';
        
        // Try to find in Domisili
        $domisili = \App\Models\Domisili::find($id);
        if ($domisili) {
            $surat = $domisili;
            $jenis = 'domisili';
        }

        // Try to find in SuratKtp
        if (!$surat && class_exists('App\\Models\\SuratKtp')) {
            $ktp = \App\Models\SuratKtp::find($id);
            if ($ktp) {
                $surat = $ktp;
                $jenis = 'ktp';
            }
        }

        // Try to find in Surat (general)
        if (!$surat && class_exists('App\\Models\\Surat')) {
            $suratGeneral = \App\Models\Surat::find($id);
            if ($suratGeneral) {
                $surat = $suratGeneral;
                $jenis = $suratGeneral->jenis_surat;
            }
        }

        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan.');
        }

        return view('admin.surat.show', compact('surat', 'jenis'));
    }

    // Handle KTP submission
    public function ktpSubmit(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'agama' => 'required|string',
            'status_perkawinan' => 'required|string',
            'nik' => 'required|string|size:16',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'pekerjaan' => 'required|string',
            'alamat' => 'required|string',
            'keperluan' => 'required|string',
        ]);

        $suratKtp = new \App\Models\SuratKtp();
        $suratKtp->fill($validated);
        $suratKtp->status = 'menunggu';
        $suratKtp->save();

        return redirect()->back()->with('success', 'Pengajuan surat KTP berhasil disimpan.');
    }

    public function dataSurat(Request $request)
    {
        $jenis = $request->query('jenis');
        
        if ($jenis === 'domisili') {
            $data = \App\Models\Domisili::orderBy('created_at', 'desc')->get();
            $result = $data->map(function($item) {
                return [
                    'id' => $item->id,
                    'nik' => $item->nik,
                    'nama' => $item->nama,
                    'tempat_lahir' => $item->tempat_lahir,
                    'tanggal_lahir' => $item->tanggal_lahir,
                    'jenis_kelamin' => $item->jenis_kelamin,
                    'kewarganegaraan' => $item->kewarganegaraan,
                    'agama' => $item->agama,
                    'status' => $item->status,
                    'pekerjaan' => $item->pekerjaan,
                    'alamat' => $item->alamat,
                    'keperluan' => $item->keperluan,
                    'status_pengajuan' => $item->status_pengajuan,
                    'jenis_surat' => 'domisili',
                    'tanggal_pengajuan' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                ];
            });
            return response()->json($result, 200);
        } elseif ($jenis === 'ktp') {
            // Handle KTP data if exists
            if (class_exists('App\\Models\\SuratKtp')) {
                $data = \App\Models\SuratKtp::orderBy('created_at', 'desc')->get();
                $result = $data->map(function($item) {
                    return [
                        'id' => $item->id,
                        'nama_lengkap' => $item->nama_lengkap,
                        'nik' => $item->nik,
                        'tempat_lahir' => $item->tempat_lahir,
                        'tanggal_lahir' => $item->tanggal_lahir,
                        'jenis_kelamin' => $item->jenis_kelamin,
                        'agama' => $item->agama,
                        'status_perkawinan' => $item->status_perkawinan,
                        'pekerjaan' => $item->pekerjaan,
                        'alamat' => $item->alamat,
                        'keperluan' => $item->keperluan,
                        'status' => $item->status,
                        'jenis_surat' => 'ktp',
                        'tanggal_pengajuan' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                    ];
                });
                return response()->json($result, 200);
            }
        } elseif ($jenis && class_exists('App\\Models\\Surat')) {
            // Handle other surat types
            $data = \App\Models\Surat::where('jenis_surat', $jenis)->orderBy('created_at', 'desc')->get();
            $result = $data->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama_pemohon' => $item->nama_pemohon ?? $item->nama ?? '-',
                    'jenis_surat' => $item->jenis_surat,
                    'tanggal_pengajuan' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                    'status' => $item->status ?? '-',
                ];
            });
            return response()->json($result, 200);
        }
        
        // Return empty array if no valid jenis or no data found
        return response()->json([], 200);
    }

    public function edit($id)
    {
        // For now, we'll handle different types based on what exists
        
        // Try to find in Domisili
        $domisili = \App\Models\Domisili::find($id);
        if ($domisili) {
            return view('admin.surat.edit', [
                'surat' => $domisili,
                'jenis' => 'domisili'
            ]);
        }

        // Try to find in SuratKtp
        if (class_exists('App\\Models\\SuratKtp')) {
            $ktp = \App\Models\SuratKtp::find($id);
            if ($ktp) {
                return view('admin.surat.edit', [
                    'surat' => $ktp,
                    'jenis' => 'ktp'
                ]);
            }
        }

        // Try to find in Surat (general)
        if (class_exists('App\\Models\\Surat')) {
            $surat = \App\Models\Surat::find($id);
            if ($surat) {
                return view('admin.surat.edit', [
                    'surat' => $surat,
                    'jenis' => $surat->jenis_surat
                ]);
            }
        }

        return redirect()->back()->with('error', 'Surat tidak ditemukan.');
    }

    public function update(Request $request, $id)
    {
        // Determine which model to update based on the data
        $jenis = $request->input('jenis_surat');
        
        if ($jenis === 'domisili') {
            $surat = \App\Models\Domisili::findOrFail($id);
            $surat->update($request->all());
        } elseif ($jenis === 'ktp' && class_exists('App\\Models\\SuratKtp')) {
            $surat = \App\Models\SuratKtp::findOrFail($id);
            $surat->update($request->all());
        } elseif (class_exists('App\\Models\\Surat')) {
            $surat = \App\Models\Surat::findOrFail($id);
            $surat->update($request->all());
        } else {
            return redirect()->back()->with('error', 'Jenis surat tidak dikenali.');
        }

        return redirect()->route('admin.surat.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Try to delete from all possible models
        $deleted = false;

        // Try Domisili
        $domisili = \App\Models\Domisili::find($id);
        if ($domisili) {
            $domisili->delete();
            $deleted = true;
        }

        // Try SuratKtp
        if (!$deleted && class_exists('App\\Models\\SuratKtp')) {
            $ktp = \App\Models\SuratKtp::find($id);
            if ($ktp) {
                $ktp->delete();
                $deleted = true;
            }
        }

        // Try Surat (general)
        if (!$deleted && class_exists('App\\Models\\Surat')) {
            $surat = \App\Models\Surat::find($id);
            if ($surat) {
                $surat->delete();
                $deleted = true;
            }
        }

        if ($deleted) {
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Surat berhasil dihapus.']);
            }
            return redirect()->route('admin.surat.index')->with('success', 'Surat berhasil dihapus.');
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Surat tidak ditemukan.']);
        }
        return redirect()->back()->with('error', 'Surat tidak ditemukan.');
    }
}
