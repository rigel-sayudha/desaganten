<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Surat;
use App\Models\Domisili;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Debug log
            \Log::info('Dashboard controller accessed');
            
            // Hitung jumlah penduduk (user dengan role user)
            $jumlahPenduduk = User::where('role', 'user')->count();
            \Log::info('Jumlah penduduk: ' . $jumlahPenduduk);
            
            // Hitung total surat masuk (semua jenis surat)
            $jumlahSuratMasuk = $this->getTotalSuratMasuk();
            \Log::info('Jumlah surat masuk: ' . $jumlahSuratMasuk);
            
            // Hitung jumlah wilayah
            $jumlahWilayah = Wilayah::count();
            \Log::info('Jumlah wilayah: ' . $jumlahWilayah);
            
            // Data statistik per wilayah
            $statistikWilayah = $this->getStatistikWilayah();
            
            // Info desa
            $infoDesa = $this->getInfoDesa();
            
            $data = compact(
                'jumlahPenduduk',
                'jumlahSuratMasuk', 
                'jumlahWilayah',
                'statistikWilayah',
                'infoDesa'
            );
            
            \Log::info('Dashboard data prepared', $data);
            
            return view('admin.dashboard', $data);
            
        } catch (\Exception $e) {
            \Log::error('Dashboard controller error: ' . $e->getMessage());
            
            // Return view with default values
            return view('admin.dashboard', [
                'jumlahPenduduk' => 0,
                'jumlahSuratMasuk' => 0,
                'jumlahWilayah' => 0,
                'statistikWilayah' => [
                    'labels' => ['Ganten', 'Karang', 'Sumber', 'Ngasem', 'Jaten', 'Sidoharjo', 'Sumberagung'],
                    'data' => [0, 0, 0, 0, 0, 0, 0]
                ],
                'infoDesa' => [
                    'jumlah_rt' => 12,
                    'jumlah_rw' => 4,
                    'jumlah_dusun' => 7,
                    'nama_wilayah' => 'Ganten, Karang, Sumber, Ngasem, Jaten, Sidoharjo, Sumberagung'
                ]
            ]);
        }
    }
    
    private function getTotalSuratMasuk()
    {
        $total = 0;
        
        // Hitung dari tabel domisili
        $total += Domisili::count();
        
        // Tambahkan dari tabel surat lainnya jika ada
        try {
            // Cek apakah ada tabel surat lain
            $tables = DB::select("SHOW TABLES LIKE 'surat_%'");
            foreach ($tables as $table) {
                $tableName = array_values((array) $table)[0];
                if ($tableName !== 'surat_domisili') { // Skip jika sudah dihitung
                    $count = DB::table($tableName)->count();
                    $total += $count;
                }
            }
            
            // Juga hitung dari tabel surat utama jika ada
            if (DB::getSchemaBuilder()->hasTable('surat')) {
                $total += DB::table('surat')->count();
            }
        } catch (\Exception $e) {
            // Jika ada error, hanya gunakan domisili
        }
        
        return $total;
    }
    
    private function getStatistikWilayah()
    {
        // Ambil data wilayah dengan jumlah penduduk
        $wilayah = Wilayah::select('nama as nama_wilayah', 'jumlah')
            ->orderBy('jumlah', 'desc')
            ->get();
        
        // Jika data wilayah kosong, berikan data default
        if ($wilayah->isEmpty()) {
            return [
                'labels' => ['Ganten', 'Karang', 'Sumber', 'Ngasem', 'Jaten', 'Sidoharjo', 'Sumberagung'],
                'data' => [0, 0, 0, 0, 0, 0, 0]
            ];
        }
        
        return [
            'labels' => $wilayah->pluck('nama_wilayah')->toArray(),
            'data' => $wilayah->pluck('jumlah')->toArray()
        ];
    }
    
    private function getInfoDesa()
    {
        // Ambil informasi desa dari database
        $jumlahRT = User::where('role', 'user')
            ->whereNotNull('rt')
            ->distinct('rt')
            ->count('rt');
            
        $jumlahRW = User::where('role', 'user')
            ->whereNotNull('rw')
            ->distinct('rw')
            ->count('rw');
            
        $jumlahDusun = Wilayah::count();
        
        $namaWilayah = Wilayah::pluck('nama')->implode(', ');
        
        return [
            'jumlah_rt' => $jumlahRT ?: 12, // Default jika tidak ada data
            'jumlah_rw' => $jumlahRW ?: 4,
            'jumlah_dusun' => $jumlahDusun ?: 7,
            'nama_wilayah' => $namaWilayah ?: 'Ganten, Karang, Sumber, Ngasem, Jaten, Sidoharjo, Sumberagung'
        ];
    }
}
