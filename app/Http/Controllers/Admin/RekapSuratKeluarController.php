<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekapSuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RekapSuratKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = RekapSuratKeluar::orderBy('tanggal_surat', 'desc');

        // Filter berdasarkan status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->dari_tanggal) {
            $query->where('tanggal_surat', '>=', $request->dari_tanggal);
        }
        if ($request->sampai_tanggal) {
            $query->where('tanggal_surat', '<=', $request->sampai_tanggal);
        }

        // Filter berdasarkan jenis surat
        if ($request->jenis_surat) {
            $query->where('jenis_surat', 'like', '%' . $request->jenis_surat . '%');
        }

        $rekapSurat = $query->paginate(15);

        // Statistik
        $stats = [
            'total' => RekapSuratKeluar::count(),
            'selesai' => RekapSuratKeluar::selesai()->count(),
            'diproses' => RekapSuratKeluar::diproses()->count(),
            'pending' => RekapSuratKeluar::pending()->count(),
            'ditolak' => RekapSuratKeluar::ditolak()->count(),
            'bulan_ini' => RekapSuratKeluar::whereMonth('tanggal_surat', Carbon::now()->month)
                         ->whereYear('tanggal_surat', Carbon::now()->year)->count(),
        ];

        return view('admin.laporan.rekap-surat-keluar.index', compact('rekapSurat', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.laporan.rekap-surat-keluar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_surat' => 'required|date',
            'nomor_surat' => 'nullable|string|max:100|unique:rekap_surat_keluar',
            'nama_pemohon' => 'required|string|max:255',
            'jenis_surat' => 'required|string|max:255',
            'untuk_keperluan' => 'required|string',
            'status' => 'required|in:pending,diproses,selesai,ditolak',
            'keterangan' => 'nullable|string',
        ]);

        RekapSuratKeluar::create($request->all());

        return redirect()->route('admin.laporan.rekap-surat-keluar.index')
                        ->with('success', 'Data rekap surat keluar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RekapSuratKeluar $rekapSuratKeluar)
    {
        return view('admin.laporan.rekap-surat-keluar.show', [
            'rekapSurat' => $rekapSuratKeluar
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RekapSuratKeluar $rekapSuratKeluar)
    {
        return view('admin.laporan.rekap-surat-keluar.edit', [
            'rekapSurat' => $rekapSuratKeluar
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RekapSuratKeluar $rekapSuratKeluar)
    {
        $request->validate([
            'tanggal_surat' => 'required|date',
            'nomor_surat' => 'nullable|string|max:100|unique:rekap_surat_keluar,nomor_surat,' . $rekapSuratKeluar->id,
            'nama_pemohon' => 'required|string|max:255',
            'jenis_surat' => 'required|string|max:255',
            'untuk_keperluan' => 'required|string',
            'status' => 'required|in:pending,diproses,selesai,ditolak',
            'keterangan' => 'nullable|string',
        ]);

        $rekapSuratKeluar->update($request->all());

        return redirect()->route('admin.laporan.rekap-surat-keluar.index')
                        ->with('success', 'Data rekap surat keluar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RekapSuratKeluar $rekapSuratKeluar)
    {
        $rekapSuratKeluar->delete();

        return redirect()->route('admin.laporan.rekap-surat-keluar.index')
                        ->with('success', 'Data rekap surat keluar berhasil dihapus.');
    }

    /**
     * Sync data from existing surat tables
     */
    public function syncData()
    {
        try {
            DB::beginTransaction();
            
            $syncCount = 0;
            
            // Sync dari berbagai tabel surat yang statusnya sudah selesai
            $suratTables = [
                'domisili' => 'Surat Keterangan Domisili',
                'tidak_mampu' => 'Surat Keterangan Tidak Mampu', 
                'belum_menikah' => 'Surat Keterangan Belum Menikah',
                'surat_kematian' => 'Surat Keterangan Kematian',
                'surat_usaha' => 'Surat Keterangan Usaha',
                'surat_skck' => 'Surat Pengantar SKCK',
                'surat_kk' => 'Surat Pengantar Kartu Keluarga',
                'surat_ktp' => 'Surat Pengantar KTP',
                'surat_kelahiran' => 'Surat Keterangan Kelahiran',
                'surat_kehilangan' => 'Surat Keterangan Kehilangan',
                'surat' => 'Surat Pengantar',
            ];
            
            foreach ($suratTables as $table => $jenisSurat) {
                // Ambil data dari tabel surat yang statusnya selesai
                $surats = DB::table($table)
                    ->whereIn('status', ['selesai', 'diproses', 'pending'])
                    ->whereNotExists(function ($query) use ($table) {
                        $query->select(DB::raw(1))
                              ->from('rekap_surat_keluar')
                              ->whereRaw('rekap_surat_keluar.surat_type = ? AND rekap_surat_keluar.surat_id = ' . $table . '.id', [$table]);
                    })
                    ->get();
                
                foreach ($surats as $surat) {
                    // Generate nomor surat jika belum ada
                    $nomorSurat = $surat->nomor_surat ?? $this->generateNomorSurat($jenisSurat);
                    
                    // Tentukan nama pemohon berdasarkan field yang ada
                    $namaPemohon = $surat->nama ?? $surat->nama_lengkap ?? $surat->nama_pelapor ?? 'Tidak Diketahui';
                    
                    // Tentukan keperluan
                    $keperluan = $surat->keperluan ?? $surat->keterangan ?? $surat->catatan ?? 'Keperluan administrasi';
                    
                    // Map status ke format baru
                    $statusMap = [
                        'selesai' => 'selesai',
                        'diproses' => 'diproses', 
                        'pending' => 'pending',
                        'Menunggu Verifikasi' => 'pending',
                        'menunggu' => 'pending',
                        'ditolak' => 'ditolak'
                    ];
                    $mappedStatus = $statusMap[$surat->status] ?? 'pending';
                    
                    RekapSuratKeluar::create([
                        'tanggal_surat' => $surat->created_at ?? now(),
                        'nomor_surat' => $nomorSurat,
                        'nama_pemohon' => $namaPemohon,
                        'jenis_surat' => $jenisSurat,
                        'untuk_keperluan' => $keperluan,
                        'status' => $mappedStatus,
                        'keterangan' => 'Data disinkronisasi dari sistem',
                        'surat_type' => $table,
                        'surat_id' => $surat->id,
                    ]);
                    
                    $syncCount++;
                }
            }
            
            DB::commit();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'synced' => $syncCount,
                    'message' => "Berhasil menyinkronkan {$syncCount} data surat."
                ]);
            }
            
            return redirect()->route('admin.laporan.rekap-surat-keluar.index')
                            ->with('success', "Berhasil menyinkronkan {$syncCount} data surat.");
                            
        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyinkronkan data: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.laporan.rekap-surat-keluar.index')
                            ->with('error', 'Gagal menyinkronkan data: ' . $e->getMessage());
        }
    }
    
    private function generateNomorSurat($jenisSurat)
    {
        $prefix = 'SK';
        $year = date('Y');
        $month = date('m');
        
        $lastNumber = RekapSuratKeluar::whereYear('tanggal_surat', $year)
                                    ->whereMonth('tanggal_surat', $month)
                                    ->count() + 1;
        
        return sprintf('%s/%03d/%s/%s/DESGANTEN', $prefix, $lastNumber, $month, $year);
    }
}
