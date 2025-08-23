<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat;
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
        $surat = \App\Models\SuratKtp::findOrFail($id);
        $data = ['surat' => $surat];
        return PDF::loadView('admin.surat.templates.ktp_pdf', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Surat_Keterangan_KTP_'.$surat->nama_lengkap.'.pdf');
    }

    public function printKk($id)
    {
        $surat = Surat::findOrFail($id);
        $data = ['surat' => $surat];
        return PDF::loadView('admin.surat.templates.kk_pdf', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Surat_Keterangan_KK_'.$surat->nama_lengkap.'.pdf');
    }

    public function printSkck($id)
    {
        $surat = Surat::findOrFail($id);
        $data = ['surat' => $surat];
        return PDF::loadView('admin.surat.templates.skck_pdf', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Surat_Pengantar_SKCK_'.$surat->nama_lengkap.'.pdf');
    }

    public function printKematian($id)
    {
        $surat = Surat::findOrFail($id);
        $data = ['surat' => $surat];
        return PDF::loadView('admin.surat.templates.kematian_pdf', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Surat_Keterangan_Kematian_'.$surat->nama.'.pdf');
    }

    public function printKelahiran($id)
    {
        $surat = Surat::findOrFail($id);
        $data = ['surat' => $surat];
        return PDF::loadView('admin.surat.templates.kelahiran_pdf', $data)
            ->setPaper('A4', 'portrait')
            ->stream('Surat_Keterangan_Kelahiran_'.$surat->nama_anak.'.pdf');
    }
}
