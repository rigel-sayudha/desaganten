<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wilayah;
use App\Models\StatistikPekerjaan;
use App\Models\StatistikUmur;
use App\Models\StatistikPendidikan;
use Illuminate\Support\Facades\Log;

class StatistikController extends Controller
{
    public function statistik()
    {
        $wilayah = Wilayah::all();

        $dataPekerjaan = StatistikPekerjaan::active()->get();
        if ($dataPekerjaan->isEmpty()) {
            $dataPekerjaan = collect([
                ['nama' => 'Petani/Pekebun', 'jumlah' => 450, 'laki_laki' => 280, 'perempuan' => 170],
                ['nama' => 'Karyawan Swasta', 'jumlah' => 400, 'laki_laki' => 220, 'perempuan' => 180],
                ['nama' => 'Wiraswasta', 'jumlah' => 350, 'laki_laki' => 200, 'perempuan' => 150],
                ['nama' => 'Mengurus Rumah Tangga', 'jumlah' => 300, 'laki_laki' => 5, 'perempuan' => 295],
                ['nama' => 'Pelajar/Mahasiswa', 'jumlah' => 250, 'laki_laki' => 125, 'perempuan' => 125],
                ['nama' => 'Belum/Tidak Bekerja', 'jumlah' => 200, 'laki_laki' => 80, 'perempuan' => 120],
                ['nama' => 'Perdagangan', 'jumlah' => 200, 'laki_laki' => 90, 'perempuan' => 110],
                ['nama' => 'Pegawai Negeri Sipil', 'jumlah' => 150, 'laki_laki' => 75, 'perempuan' => 75],
                ['nama' => 'Industri', 'jumlah' => 120, 'laki_laki' => 70, 'perempuan' => 50],
                ['nama' => 'Peternak', 'jumlah' => 100, 'laki_laki' => 65, 'perempuan' => 35],
                ['nama' => 'Lainnya', 'jumlah' => 90, 'laki_laki' => 50, 'perempuan' => 40],
                ['nama' => 'Pensiunan', 'jumlah' => 80, 'laki_laki' => 45, 'perempuan' => 35],
                ['nama' => 'Konstruksi', 'jumlah' => 80, 'laki_laki' => 75, 'perempuan' => 5],
                ['nama' => 'Transportasi', 'jumlah' => 70, 'laki_laki' => 65, 'perempuan' => 5],
                ['nama' => 'Nelayan/Perikanan', 'jumlah' => 50, 'laki_laki' => 45, 'perempuan' => 5],
                ['nama' => 'TNI/Polri', 'jumlah' => 30, 'laki_laki' => 25, 'perempuan' => 5],
            ]);
        } else {
            $dataPekerjaan = $dataPekerjaan->map(function ($item) {
                return [
                    'nama' => $item->nama_pekerjaan,
                    'jumlah' => $item->jumlah,
                    'laki_laki' => $item->laki_laki,
                    'perempuan' => $item->perempuan
                ];
            });
        }

        $dataUmur = StatistikUmur::active()->orderBy('usia_min')->get();
        if ($dataUmur->isEmpty()) {
            $dataUmur = collect([
                ['kelompok' => '0-1 Tahun', 'laki_laki' => 25, 'perempuan' => 23, 'jumlah' => 48],
                ['kelompok' => '2-4 Tahun', 'laki_laki' => 35, 'perempuan' => 32, 'jumlah' => 67],
                ['kelompok' => '5-9 Tahun', 'laki_laki' => 55, 'perempuan' => 52, 'jumlah' => 107],
                ['kelompok' => '10-14 Tahun', 'laki_laki' => 68, 'perempuan' => 65, 'jumlah' => 133],
                ['kelompok' => '15-19 Tahun', 'laki_laki' => 75, 'perempuan' => 72, 'jumlah' => 147],
                ['kelompok' => '20-24 Tahun', 'laki_laki' => 85, 'perempuan' => 88, 'jumlah' => 173],
                ['kelompok' => '25-29 Tahun', 'laki_laki' => 95, 'perempuan' => 98, 'jumlah' => 193],
                ['kelompok' => '30-34 Tahun', 'laki_laki' => 105, 'perempuan' => 108, 'jumlah' => 213],
                ['kelompok' => '35-39 Tahun', 'laki_laki' => 98, 'perempuan' => 102, 'jumlah' => 200],
                ['kelompok' => '40-44 Tahun', 'laki_laki' => 88, 'perempuan' => 92, 'jumlah' => 180],
                ['kelompok' => '45-49 Tahun', 'laki_laki' => 78, 'perempuan' => 82, 'jumlah' => 160],
                ['kelompok' => '50-54 Tahun', 'laki_laki' => 68, 'perempuan' => 72, 'jumlah' => 140],
                ['kelompok' => '55-59 Tahun', 'laki_laki' => 58, 'perempuan' => 62, 'jumlah' => 120],
                ['kelompok' => '60-64 Tahun', 'laki_laki' => 48, 'perempuan' => 52, 'jumlah' => 100],
                ['kelompok' => '65-69 Tahun', 'laki_laki' => 38, 'perempuan' => 42, 'jumlah' => 80],
                ['kelompok' => '70-74 Tahun', 'laki_laki' => 28, 'perempuan' => 32, 'jumlah' => 60],
                ['kelompok' => '75-79 Tahun', 'laki_laki' => 18, 'perempuan' => 22, 'jumlah' => 40],
                ['kelompok' => '80+ Tahun', 'laki_laki' => 12, 'perempuan' => 18, 'jumlah' => 30],
            ]);
        } else {
            // Transform database data to match expected format
            $dataUmur = $dataUmur->map(function ($item) {
                return [
                    'kelompok' => $item->kelompok_umur,
                    'laki_laki' => $item->laki_laki,
                    'perempuan' => $item->perempuan,
                    'jumlah' => $item->jumlah
                ];
            });
        }

        $dataPendidikan = StatistikPendidikan::active()->orderBy('urutan')->get();
        if ($dataPendidikan->isEmpty()) {
            $dataPendidikan = collect([
                ['tingkat' => 'Tidak/Belum Sekolah', 'laki_laki' => 180, 'perempuan' => 190, 'jumlah' => 370],
                ['tingkat' => 'Tidak Tamat SD/Sederajat', 'laki_laki' => 85, 'perempuan' => 95, 'jumlah' => 180],
                ['tingkat' => 'Tamat SD/Sederajat', 'laki_laki' => 220, 'perempuan' => 230, 'jumlah' => 450],
                ['tingkat' => 'Tamat SMP/Sederajat', 'laki_laki' => 180, 'perempuan' => 190, 'jumlah' => 370],
                ['tingkat' => 'Tamat SMA/Sederajat', 'laki_laki' => 250, 'perempuan' => 260, 'jumlah' => 510],
                ['tingkat' => 'Diploma I/II', 'laki_laki' => 35, 'perempuan' => 45, 'jumlah' => 80],
                ['tingkat' => 'Diploma III', 'laki_laki' => 45, 'perempuan' => 55, 'jumlah' => 100],
                ['tingkat' => 'Diploma IV/Strata I', 'laki_laki' => 95, 'perempuan' => 105, 'jumlah' => 200],
                ['tingkat' => 'Strata II', 'laki_laki' => 25, 'perempuan' => 30, 'jumlah' => 55],
                ['tingkat' => 'Strata III', 'laki_laki' => 8, 'perempuan' => 7, 'jumlah' => 15],
            ]);
        } else {

            $dataPendidikan = $dataPendidikan->map(function ($item) {
                return [
                    'tingkat' => $item->tingkat_pendidikan,
                    'laki_laki' => $item->laki_laki,
                    'perempuan' => $item->perempuan,
                    'jumlah' => $item->jumlah
                ];
            });
        }

        $totalPenduduk = $dataPekerjaan->sum('jumlah');
        $totalLakiLaki = $dataPekerjaan->sum('laki_laki');
        $totalPerempuan = $dataPekerjaan->sum('perempuan');
        
        // If pekerjaan data is empty, use umur data as fallback
        if ($totalPenduduk == 0) {
            $totalPenduduk = $dataUmur->sum('jumlah');
            $totalLakiLaki = $dataUmur->sum('laki_laki');
            $totalPerempuan = $dataUmur->sum('perempuan');
        }
        
        // If still empty, use pendidikan data as final fallback
        if ($totalPenduduk == 0) {
            $totalPenduduk = $dataPendidikan->sum('jumlah');
            $totalLakiLaki = $dataPendidikan->sum('laki_laki');
            $totalPerempuan = $dataPendidikan->sum('perempuan');
        }
        
        // Calculate KK (estimasi 28% dari total penduduk)
        $totalKK = intval($totalPenduduk * 0.28);

        // Debug logging
        Log::info('Statistik Data Debug:', [
            'totalPenduduk' => $totalPenduduk,
            'totalLakiLaki' => $totalLakiLaki,
            'totalPerempuan' => $totalPerempuan,
            'totalKK' => $totalKK,
            'dataPekerjaan_count' => $dataPekerjaan->count(),
            'dataUmur_count' => $dataUmur->count(),
            'dataPendidikan_count' => $dataPendidikan->count()
        ]);

        return view('statistik.statistik', compact(
            'wilayah', 
            'dataPekerjaan',
            'dataUmur',
            'dataPendidikan',
            'totalPenduduk',
            'totalLakiLaki', 
            'totalPerempuan',
            'totalKK'
        ));
    }

    public function wilayah()
    {
        $data = \App\Models\StatistikWilayah::active()->ordered()->get();

        if ($data->isEmpty()) {
            $data = collect([
                (object)[
                    'nama_wilayah' => 'Dusun Ganten Lor',
                    'jenis_wilayah' => 'Dusun',
                    'laki_laki' => 425,
                    'perempuan' => 445,
                    'jumlah' => 870,
                    'keterangan' => 'Wilayah dusun terbesar di Desa Ganten'
                ],
                (object)[
                    'nama_wilayah' => 'Dusun Ganten Kidul',
                    'jenis_wilayah' => 'Dusun',
                    'laki_laki' => 365,
                    'perempuan' => 385,
                    'jumlah' => 750,
                    'keterangan' => 'Wilayah dusun bagian selatan'
                ],
                (object)[
                    'nama_wilayah' => 'Dusun Sempu',
                    'jenis_wilayah' => 'Dusun',
                    'laki_laki' => 310,
                    'perempuan' => 320,
                    'jumlah' => 630,
                    'keterangan' => 'Wilayah dusun bagian timur'
                ],
                (object)[
                    'nama_wilayah' => 'Dusun Kraguman',
                    'jenis_wilayah' => 'Dusun',
                    'laki_laki' => 280,
                    'perempuan' => 290,
                    'jumlah' => 570,
                    'keterangan' => 'Wilayah dusun bagian barat'
                ],
                (object)[
                    'nama_wilayah' => 'RW 01',
                    'jenis_wilayah' => 'RW',
                    'laki_laki' => 195,
                    'perempuan' => 205,
                    'jumlah' => 400,
                    'keterangan' => 'Rukun Warga 01'
                ],
                (object)[
                    'nama_wilayah' => 'RW 02',
                    'jenis_wilayah' => 'RW',
                    'laki_laki' => 185,
                    'perempuan' => 195,
                    'jumlah' => 380,
                    'keterangan' => 'Rukun Warga 02'
                ],
                (object)[
                    'nama_wilayah' => 'RW 03',
                    'jenis_wilayah' => 'RW',
                    'laki_laki' => 175,
                    'perempuan' => 185,
                    'jumlah' => 360,
                    'keterangan' => 'Rukun Warga 03'
                ]
            ]);
        }

        $totalPenduduk = $data->sum('jumlah');
        $totalLakiLaki = $data->sum('laki_laki');
        $totalPerempuan = $data->sum('perempuan');
        $totalWilayah = $data->count();

        $dataByJenis = $data->groupBy('jenis_wilayah');

        return view('statistik.wilayah', compact(
            'data', 
            'totalPenduduk', 
            'totalLakiLaki', 
            'totalPerempuan', 
            'totalWilayah',
            'dataByJenis'
        ));
    }


    public function umur()
    {
        $dataUmur = StatistikUmur::active()->orderBy('usia_min')->get();
        if ($dataUmur->isEmpty()) {
            $dataUmur = collect([
                ['kelompok' => '0-1 Tahun', 'laki_laki' => 25, 'perempuan' => 23, 'jumlah' => 48],
                ['kelompok' => '2-4 Tahun', 'laki_laki' => 35, 'perempuan' => 32, 'jumlah' => 67],
                ['kelompok' => '5-9 Tahun', 'laki_laki' => 55, 'perempuan' => 52, 'jumlah' => 107],
                ['kelompok' => '10-14 Tahun', 'laki_laki' => 68, 'perempuan' => 65, 'jumlah' => 133],
                ['kelompok' => '15-19 Tahun', 'laki_laki' => 75, 'perempuan' => 72, 'jumlah' => 147],
                ['kelompok' => '20-24 Tahun', 'laki_laki' => 85, 'perempuan' => 88, 'jumlah' => 173],
                ['kelompok' => '25-29 Tahun', 'laki_laki' => 95, 'perempuan' => 98, 'jumlah' => 193],
                ['kelompok' => '30-34 Tahun', 'laki_laki' => 105, 'perempuan' => 108, 'jumlah' => 213],
                ['kelompok' => '35-39 Tahun', 'laki_laki' => 98, 'perempuan' => 102, 'jumlah' => 200],
                ['kelompok' => '40-44 Tahun', 'laki_laki' => 88, 'perempuan' => 92, 'jumlah' => 180],
                ['kelompok' => '45-49 Tahun', 'laki_laki' => 78, 'perempuan' => 82, 'jumlah' => 160],
                ['kelompok' => '50-54 Tahun', 'laki_laki' => 68, 'perempuan' => 72, 'jumlah' => 140],
                ['kelompok' => '55-59 Tahun', 'laki_laki' => 58, 'perempuan' => 62, 'jumlah' => 120],
                ['kelompok' => '60-64 Tahun', 'laki_laki' => 48, 'perempuan' => 52, 'jumlah' => 100],
                ['kelompok' => '65-69 Tahun', 'laki_laki' => 38, 'perempuan' => 42, 'jumlah' => 80],
                ['kelompok' => '70-74 Tahun', 'laki_laki' => 28, 'perempuan' => 32, 'jumlah' => 60],
                ['kelompok' => '75-79 Tahun', 'laki_laki' => 18, 'perempuan' => 22, 'jumlah' => 40],
                ['kelompok' => '80+ Tahun', 'laki_laki' => 12, 'perempuan' => 18, 'jumlah' => 30],
            ]);
        } else {
            $dataUmur = $dataUmur->map(function ($item) {
                return [
                    'kelompok' => $item->kelompok_umur,
                    'laki_laki' => $item->laki_laki,
                    'perempuan' => $item->perempuan,
                    'jumlah' => $item->jumlah
                ];
            });
        }

        $totalPenduduk = $dataUmur->sum('jumlah');
        $totalLakiLaki = $dataUmur->sum('laki_laki');
        $totalPerempuan = $dataUmur->sum('perempuan');
        
        $wilayah = Wilayah::all();
        $totalKK = $wilayah->sum('jumlah_kk') ?: (int) round($totalPenduduk / 4); // Estimate 4 people per KK

        return view('statistik.umur', compact(
            'dataUmur', 
            'totalPenduduk', 
            'totalLakiLaki', 
            'totalPerempuan',
            'totalKK'
        ));
    }

    public function usia()
    {
        // Redirect to umur page
        return redirect()->route('statistik.umur');
    }

    public function pendidikan()
    {
        // Data Pendidikan dari database atau fallback ke dummy data
        $dataPendidikan = StatistikPendidikan::active()->get();
        if ($dataPendidikan->isEmpty()) {
            $dataPendidikan = collect([
                ['tingkat' => 'Tidak/Belum Sekolah', 'laki_laki' => 180, 'perempuan' => 190, 'jumlah' => 370],
                ['tingkat' => 'Tidak Tamat SD/Sederajat', 'laki_laki' => 85, 'perempuan' => 95, 'jumlah' => 180],
                ['tingkat' => 'Tamat SD/Sederajat', 'laki_laki' => 320, 'perempuan' => 340, 'jumlah' => 660],
                ['tingkat' => 'Tamat SLTP/Sederajat', 'laki_laki' => 380, 'perempuan' => 370, 'jumlah' => 750],
                ['tingkat' => 'Tamat SLTA/Sederajat', 'laki_laki' => 420, 'perempuan' => 430, 'jumlah' => 850],
                ['tingkat' => 'Tamat D-1/Sederajat', 'laki_laki' => 25, 'perempuan' => 35, 'jumlah' => 60],
                ['tingkat' => 'Tamat D-2/Sederajat', 'laki_laki' => 30, 'perempuan' => 40, 'jumlah' => 70],
                ['tingkat' => 'Tamat D-3/Sederajat', 'laki_laki' => 45, 'perempuan' => 55, 'jumlah' => 100],
                ['tingkat' => 'Tamat S-1/Sederajat', 'laki_laki' => 120, 'perempuan' => 130, 'jumlah' => 250],
                ['tingkat' => 'Tamat S-2/Sederajat', 'laki_laki' => 15, 'perempuan' => 20, 'jumlah' => 35],
            ]);
        } else {
            // Transform database data to match expected format
            $dataPendidikan = $dataPendidikan->map(function ($item) {
                return [
                    'tingkat' => $item->tingkat_pendidikan,
                    'laki_laki' => $item->laki_laki,
                    'perempuan' => $item->perempuan,
                    'jumlah' => $item->jumlah
                ];
            });
        }

        // Calculate totals
        $totalPenduduk = $dataPendidikan->sum('jumlah');
        $totalLakiLaki = $dataPendidikan->sum('laki_laki');
        $totalPerempuan = $dataPendidikan->sum('perempuan');
        
        // Get total KK from Wilayah table or use estimated count
        $wilayah = Wilayah::all();
        $totalKK = $wilayah->sum('jumlah_kk') ?: (int) round($totalPenduduk / 4); // Estimate 4 people per KK

        return view('statistik.pendidikan', compact(
            'dataPendidikan', 
            'totalPenduduk', 
            'totalLakiLaki', 
            'totalPerempuan',
            'totalKK'
        ));
    }

    /**
     * Display pekerjaan statistics page
     */
    /**
     * Display pekerjaan statistics page
     */
    public function pekerjaan()
    {
        // Data Pekerjaan dari database atau fallback ke dummy data
        $pekerjaan = StatistikPekerjaan::active()->get();
        if ($pekerjaan->isEmpty()) {
            $pekerjaan = collect([
                (object)['nama' => 'Petani/Pekebun', 'jumlah' => 450, 'laki_laki' => 280, 'perempuan' => 170],
                (object)['nama' => 'Karyawan Swasta', 'jumlah' => 400, 'laki_laki' => 220, 'perempuan' => 180],
                (object)['nama' => 'Wiraswasta', 'jumlah' => 350, 'laki_laki' => 200, 'perempuan' => 150],
                (object)['nama' => 'Mengurus Rumah Tangga', 'jumlah' => 300, 'laki_laki' => 5, 'perempuan' => 295],
                (object)['nama' => 'Pelajar/Mahasiswa', 'jumlah' => 250, 'laki_laki' => 125, 'perempuan' => 125],
                (object)['nama' => 'Belum/Tidak Bekerja', 'jumlah' => 200, 'laki_laki' => 80, 'perempuan' => 120],
                (object)['nama' => 'Perdagangan', 'jumlah' => 200, 'laki_laki' => 90, 'perempuan' => 110],
                (object)['nama' => 'Pegawai Negeri Sipil', 'jumlah' => 150, 'laki_laki' => 75, 'perempuan' => 75],
                (object)['nama' => 'Industri', 'jumlah' => 120, 'laki_laki' => 70, 'perempuan' => 50],
                (object)['nama' => 'Peternak', 'jumlah' => 100, 'laki_laki' => 65, 'perempuan' => 35],
                (object)['nama' => 'Lainnya', 'jumlah' => 90, 'laki_laki' => 50, 'perempuan' => 40],
                (object)['nama' => 'Pensiunan', 'jumlah' => 80, 'laki_laki' => 45, 'perempuan' => 35],
                (object)['nama' => 'Konstruksi', 'jumlah' => 80, 'laki_laki' => 75, 'perempuan' => 5],
                (object)['nama' => 'Transportasi', 'jumlah' => 70, 'laki_laki' => 65, 'perempuan' => 5],
                (object)['nama' => 'Nelayan/Perikanan', 'jumlah' => 50, 'laki_laki' => 45, 'perempuan' => 5],
                (object)['nama' => 'TNI/Polri', 'jumlah' => 30, 'laki_laki' => 25, 'perempuan' => 5],
            ]);
        } else {
            // Transform database data to match expected format
            $pekerjaan = $pekerjaan->map(function ($item) {
                return (object)[
                    'nama' => $item->nama_pekerjaan,
                    'jumlah' => $item->jumlah,
                    'laki_laki' => $item->laki_laki,
                    'perempuan' => $item->perempuan
                ];
            });
        }

        // Calculate totals from pekerjaan data
        $totalPenduduk = $pekerjaan->sum('jumlah');
        $totalLakiLaki = $pekerjaan->sum('laki_laki');
        $totalPerempuan = $pekerjaan->sum('perempuan');
        
        // Get total KK from Wilayah table or use estimated count
        $wilayah = Wilayah::all();
        $totalKK = $wilayah->sum('jumlah_kk') ?: (int) round($totalPenduduk / 4); // Estimate 4 people per KK

        Log::info('Pekerjaan Data Debug:', [
            'totalPenduduk' => $totalPenduduk,
            'totalLakiLaki' => $totalLakiLaki,
            'totalPerempuan' => $totalPerempuan,
            'totalKK' => $totalKK,
            'pekerjaan_count' => $pekerjaan->count()
        ]);

        return view('statistik.pekerjaan', compact(
            'pekerjaan',
            'totalPenduduk',
            'totalLakiLaki', 
            'totalPerempuan',
            'totalKK'
        ));
    }
}
