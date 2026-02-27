<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\SuratSkck;
use App\Models\SuratKk;
use App\Models\SuratKematian;
use App\Models\SuratKelahiran;
use App\Models\SuratKehilangan;
use App\Models\BelumMenikah;
use App\Models\TidakMampu;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SuratPrintController extends Controller
{
    public function printDomisili($id)
    {
        $surat = \App\Models\Domisili::findOrFail($id);
        $data = ['surat' => $surat];
        return PDF::loadView('admin.surat.templates.domisili_pdf', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Surat_Keterangan_Domisili_'.$surat->nama.'.pdf');
    }

    public function printKtp($id)
    {
        // Try to find in SuratKtp model first
        $surat = null;
        if (class_exists('App\\Models\\SuratKtp')) {
            $surat = \App\Models\SuratKtp::find($id);
        }
        
        // If not found, try general Surat model - only if table exists
        if (!$surat) {
            try {
                if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                    $surat = \App\Models\Surat::find($id);
                }
            } catch (\Exception $e) {
                // Ignore error if table doesn't exist
            }
        }
        
        if (!$surat) {
            abort(404, 'Surat tidak ditemukan');
        }
        
        $data = ['surat' => $surat];
        return PDF::loadView('admin.surat.templates.ktp_pdf', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Surat_Keterangan_KTP_'.(($surat->nama_lengkap ?? $surat->nama ?? 'unknown')).'.pdf');
    }

    public function printKk($id)
    {
        // Try to find in SuratKk model first
        $surat = null;
        
        if (class_exists('App\\Models\\SuratKk')) {
            $surat = SuratKk::find($id);
        }
        
        // If not found, try general Surat model - only if table exists
        if (!$surat) {
            try {
                if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                    $surat = \App\Models\Surat::find($id);
                }
            } catch (\Exception $e) {
                // Ignore error if table doesn't exist
            }
        }
        
        // If still not found, try SuratKtp as fallback
        if (!$surat && class_exists('App\\Models\\SuratKtp')) {
            $surat = \App\Models\SuratKtp::find($id);
        }
        
        if (!$surat) {
            abort(404, 'Surat tidak ditemukan');
        }
        
        $data = ['surat' => $surat];
        return PDF::loadView('admin.surat.templates.kk_pdf', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Surat_Keterangan_KK_'.(($surat->nama_lengkap ?? $surat->nama ?? 'unknown')).'.pdf');
    }

    public function printSkck($id)
    {
        // Try to find in SuratSkck model first
        $surat = SuratSkck::find($id);
        
        // If not found, try SuratKtp model 
        if (!$surat && class_exists('App\\Models\\SuratKtp')) {
            $surat = \App\Models\SuratKtp::find($id);
        }
        
        // If not found, try general Surat model - only if table exists
        if (!$surat) {
            try {
                if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                    $surat = \App\Models\Surat::find($id);
                }
            } catch (\Exception $e) {
                // Ignore error if table doesn't exist
            }
        }
        
        // If still not found, try Domisili model (fallback)
        if (!$surat) {
            $surat = \App\Models\Domisili::find($id);
        }
        
        if (!$surat) {
            abort(404, 'Surat tidak ditemukan');
        }
        
        $data = ['surat' => $surat];
        return PDF::loadView('admin.surat.templates.skck_pdf', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Surat_Pengantar_SKCK_'.(($surat->nama_lengkap ?? $surat->nama ?? 'unknown')).'.pdf');
    }

    public function printKematian($id)
    {
        $surat = null;
        
        // Try SuratKematian model first
        if (class_exists('App\\Models\\SuratKematian')) {
            $surat = SuratKematian::find($id);
        }
        
        // If not found, try general Surat model - only if table exists
        if (!$surat) {
            try {
                if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                    $surat = \App\Models\Surat::find($id);
                }
            } catch (\Exception $e) {
                // Ignore error if table doesn't exist
            }
        }
        
        if (!$surat) {
            abort(404, 'Surat tidak ditemukan');
        }
        
        $data = ['surat' => $surat];
        return PDF::loadView('admin.surat.templates.kematian_pdf', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Surat_Keterangan_Kematian_'.(($surat->nama_almarhum ?? $surat->nama ?? 'unknown')).'.pdf');
    }

    public function printKelahiran($id)
    {
        $surat = null;
        
        // Try SuratKelahiran model first
        if (class_exists('App\\Models\\SuratKelahiran')) {
            $surat = SuratKelahiran::find($id);
        }
        
        // If not found, try general Surat model - only if table exists
        if (!$surat) {
            try {
                if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                    $surat = \App\Models\Surat::find($id);
                }
            } catch (\Exception $e) {
                // Ignore error if table doesn't exist
            }
        }
        
        if (!$surat) {
            abort(404, 'Surat tidak ditemukan');
        }
        
        $data = ['surat' => $surat];
        return PDF::loadView('admin.surat.templates.kelahiran_pdf', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Surat_Keterangan_Kelahiran_'.(($surat->nama_bayi ?? $surat->nama_anak ?? $surat->nama ?? 'unknown')).'.pdf');
    }

    public function printBelumMenikah($id)
    {
        $surat = \App\Models\BelumMenikah::findOrFail($id);
        $data = ['surat' => $surat];
        return PDF::loadView('admin.surat.templates.belum_menikah_pdf', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Surat_Keterangan_Belum_Menikah_'.$surat->nama.'.pdf');
    }

    public function printTidakMampu($id)
    {
        $surat = \App\Models\TidakMampu::findOrFail($id);
        $data = ['surat' => $surat];
        return PDF::loadView('admin.surat.templates.tidak_mampu_pdf', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Surat_Keterangan_Tidak_Mampu_'.$surat->nama.'.pdf');
    }

    public function printUsaha($id)
    {
        $surat = null;
        
        // Try SuratUsaha model first
        if (class_exists('App\\Models\\SuratUsaha')) {
            $surat = \App\Models\SuratUsaha::find($id);
        }
        
        // If not found, try general Surat model - only if table exists
        if (!$surat) {
            try {
                if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                    $surat = \App\Models\Surat::find($id);
                }
            } catch (\Exception $e) {
                // Ignore error if table doesn't exist
            }
        }
        
        if (!$surat) {
            abort(404, 'Surat tidak ditemukan');
        }
        
        $data = ['surat' => $surat];
        return PDF::loadView('admin.surat.templates.usaha_pdf', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Surat_Keterangan_Usaha_'.(($surat->nama_lengkap ?? $surat->nama ?? 'unknown')).'.pdf');
    }

    public function printKehilangan($id)
    {
        $surat = null;

        // Try SuratKehilangan model first
        if (class_exists('App\\Models\\SuratKehilangan')) {
            $surat = SuratKehilangan::find($id);
        }

        // If not found, try general Surat model - only if table exists
        if (!$surat) {
            try {
                if (class_exists('App\\Models\\Surat') && Schema::hasTable('surat')) {
                    $surat = \App\Models\Surat::find($id);
                }
            } catch (\Exception $e) {
                // Ignore error if table doesn't exist
            }
        }

        if (!$surat) {
            abort(404, 'Surat tidak ditemukan');
        }

        $data = ['surat' => $surat];
        return PDF::loadView('admin.surat.templates.kehilangan_pdf', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Surat_Keterangan_Kehilangan_'.(($surat->nama_lengkap ?? $surat->nama ?? 'unknown')).'.pdf');
    }

    /**
     * General printPdf method that routes to specific print methods
     */
    public function printPdf($type, $id)
    {
        switch (strtolower($type)) {
            case 'domisili':
                return $this->printDomisili($id);
            case 'ktp':
                return $this->printKtp($id);
            case 'kk':
                return $this->printKk($id);
            case 'skck':
                return $this->printSkck($id);
            case 'kehilangan':
                return $this->printKehilangan($id);
            case 'kematian':
                return $this->printKematian($id);
            case 'kelahiran':
                return $this->printKelahiran($id);
            case 'belum_menikah':
            case 'belum-menikah':
                return $this->printBelumMenikah($id);
            case 'tidak_mampu':
            case 'tidak-mampu':
                return $this->printTidakMampu($id);
            case 'usaha':
                return $this->printUsaha($id);
            default:
                abort(404, 'Jenis surat tidak ditemukan');
        }
    }
}
