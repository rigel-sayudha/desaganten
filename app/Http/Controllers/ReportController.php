<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Domisili;
use App\Models\TidakMampu;
use App\Models\BelumMenikah;
use App\Models\SuratKematian;
use App\Models\SuratUsaha;
use App\Models\SuratSkck;
use App\Models\SuratKtp;
use App\Models\SuratKelahiran;
use App\Models\SuratKk;
use App\Models\SuratKehilangan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function generateSystemReport()
    {
        try {
            $stats = $this->getSystemStatistics();
            
            $pdf = Pdf::loadView('reports.system-report', compact('stats'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'margin-top' => 10,
                    'margin-bottom' => 10,
                    'margin-left' => 10,
                    'margin-right' => 10,
                ]);
            
            $filename = 'Laporan_Sistem_Surat_Keterangan_Desa_Ganten_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat laporan PDF: ' . $e->getMessage());
        }
    }
    
    public function viewSystemReport()
    {
        try {
            $stats = $this->getSystemStatistics();
            return view('reports.system-report', compact('stats'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat laporan: ' . $e->getMessage());
        }
    }
    
    private function getSystemStatistics()
    {
        return [
            'total_users' => User::count() ?? 0,
            'total_surat' => $this->getTotalSuratCount(),
            'surat_by_type' => $this->getSuratCountByType(),
            'surat_by_status' => $this->getSuratCountByStatus(),
            'recent_activity' => $this->getRecentActivity(),
            'system_health' => $this->getSystemHealth(),
        ];
    }
    
    private function getTotalSuratCount()
    {
        $total = 0;
        
        $suratModels = [
            Domisili::class,
            TidakMampu::class,
            BelumMenikah::class,
            SuratKematian::class,
            SuratUsaha::class,
            SuratSkck::class,
            SuratKtp::class,
            SuratKelahiran::class,
            SuratKk::class,
            SuratKehilangan::class,
        ];
        
        foreach ($suratModels as $model) {
            try {
                if (class_exists($model)) {
                    $total += $model::count();
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        
        return $total;
    }
    
    private function getSuratCountByType()
    {
        $counts = [];
        
        $models = [
            'domisili' => Domisili::class,
            'tidak_mampu' => TidakMampu::class,
            'belum_menikah' => BelumMenikah::class,
            'kematian' => SuratKematian::class,
            'usaha' => SuratUsaha::class,
            'skck' => SuratSkck::class,
            'ktp' => SuratKtp::class,
            'kelahiran' => SuratKelahiran::class,
            'kk' => SuratKk::class,
            'kehilangan' => SuratKehilangan::class,
        ];
        
        foreach ($models as $key => $model) {
            try {
                if (class_exists($model)) {
                    $counts[$key] = $model::count();
                } else {
                    $counts[$key] = 0;
                }
            } catch (\Exception $e) {
                $counts[$key] = 0;
            }
        }
        
        return $counts;
    }
    
    private function getSuratCountByStatus()
    {
        $statusCounts = [
            'menunggu' => 0,
            'diproses' => 0,
            'selesai_diproses' => 0,
            'ditolak' => 0,
        ];
        
        $suratModels = [
            Domisili::class,
            TidakMampu::class,
            BelumMenikah::class,
            SuratKematian::class,
            SuratUsaha::class,
            SuratSkck::class,
            SuratKtp::class,
            SuratKelahiran::class,
            SuratKk::class,
            SuratKehilangan::class,
        ];
        
        foreach ($suratModels as $model) {
            try {
                if (class_exists($model)) {
                    $columns = ['status', 'status_surat', 'status_pengajuan'];
                    
                    foreach ($columns as $column) {
                        if (Schema::hasColumn((new $model)->getTable(), $column)) {
                            $statusData = $model::select($column)->get();
                            
                            foreach ($statusData as $item) {
                                $status = $item->{$column};
                                
                                if (in_array($status, ['menunggu', 'pending', 'waiting'])) {
                                    $statusCounts['menunggu']++;
                                } elseif (in_array($status, ['diproses', 'processing', 'in_progress'])) {
                                    $statusCounts['diproses']++;
                                } elseif (in_array($status, ['selesai_diproses', 'selesai', 'completed', 'done'])) {
                                    $statusCounts['selesai_diproses']++;
                                } elseif (in_array($status, ['ditolak', 'rejected', 'declined'])) {
                                    $statusCounts['ditolak']++;
                                }
                            }
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        
        return $statusCounts;
    }
    
    private function getRecentActivity()
    {
        $recentSurat = [];
        
        $suratModels = [
            'Domisili' => Domisili::class,
            'Tidak Mampu' => TidakMampu::class,
            'Belum Menikah' => BelumMenikah::class,
            'Kematian' => SuratKematian::class,
            'Usaha' => SuratUsaha::class,
            'SKCK' => SuratSkck::class,
            'KTP' => SuratKtp::class,
            'Kelahiran' => SuratKelahiran::class,
            'KK' => SuratKk::class,
            'Kehilangan' => SuratKehilangan::class,
        ];
        
        foreach ($suratModels as $name => $model) {
            try {
                if (class_exists($model)) {
                    $recent = $model::latest()->take(3)->get();
                    foreach ($recent as $item) {
                        $recentSurat[] = [
                            'type' => $name,
                            'created_at' => $item->created_at ?? now(),
                            'status' => $item->status ?? $item->status_surat ?? 'menunggu',
                        ];
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        
        usort($recentSurat, function($a, $b) {
            return $b['created_at']->timestamp - $a['created_at']->timestamp;
        });
        
        return array_slice($recentSurat, 0, 10);
    }
    
    private function getSystemHealth()
    {
        $health = [
            'database_connection' => 'OK',
            'user_count' => 0,
            'total_surat' => 0,
            'last_activity' => null,
        ];
        
        try {
            DB::connection()->getPdo();
            $health['database_connection'] = 'OK';
            $health['user_count'] = User::count();
            $health['total_surat'] = $this->getTotalSuratCount();
            
            $lastActivity = null;
            if (class_exists(Domisili::class)) {
                $lastActivity = Domisili::latest()->first();
            }
            
            $health['last_activity'] = $lastActivity ? $lastActivity->created_at : null;
            
        } catch (\Exception $e) {
            $health['database_connection'] = 'ERROR: ' . $e->getMessage();
        }
        
        return $health;
    }
}
