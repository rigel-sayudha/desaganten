<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Domisili;
use App\Models\TidakMampu;
use App\Models\BelumMenikah;
use App\Services\VerificationStageService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SuratUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all surat by user NIK
        $suratDomisili = Domisili::where('nik', $user->nik)->get();
        $suratTidakMampu = TidakMampu::where('nik', $user->nik)->get();
        $suratBelumMenikah = BelumMenikah::where('nik', $user->nik)->get();
        
        // Combine all surat
        $allSurat = collect();
        
        foreach ($suratDomisili as $surat) {
            $surat->jenis_surat = 'domisili';
            $surat->nama_pemohon = $surat->nama;
            $allSurat->push($surat);
        }
        
        foreach ($suratTidakMampu as $surat) {
            $surat->jenis_surat = 'tidak_mampu';
            $surat->nama_pemohon = $surat->nama;
            $allSurat->push($surat);
        }
        
        foreach ($suratBelumMenikah as $surat) {
            $surat->jenis_surat = 'belum_menikah';
            $surat->nama_pemohon = $surat->nama;
            $allSurat->push($surat);
        }
        
        // Sort by created_at desc
        $allSurat = $allSurat->sortByDesc('created_at');
        
        return view('user.surat.index', compact('allSurat'));
    }
    
    public function printPdf($type, $id)
    {
        $surat = $this->getSuratByType($type, $id);
        $user = Auth::user();
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan');
        }
        
        // Check if user is the owner
        if ($surat->nik !== $user->nik) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke surat ini');
        }
        
        // Check if surat is verified
        if ($surat->status !== 'sudah diverifikasi') {
            return redirect()->back()->with('error', 'Surat belum selesai diverifikasi');
        }
        
        $progress = 0;
        if ($surat->tahapan_verifikasi) {
            $stages = is_string($surat->tahapan_verifikasi) ? json_decode($surat->tahapan_verifikasi, true) : $surat->tahapan_verifikasi;
            $progress = VerificationStageService::getProgressPercentage($stages);
        }
        
        if ($progress < 100) {
            return redirect()->back()->with('error', 'Surat belum selesai diverifikasi');
        }
        
        $jenisSuratMap = [
            'domisili' => 'Surat Keterangan Domisili',
            'tidak_mampu' => 'Surat Keterangan Tidak Mampu',
            'belum_menikah' => 'Surat Keterangan Belum Menikah'
        ];
        
        $jenisSurat = $jenisSuratMap[$type] ?? ucfirst(str_replace('_', ' ', $type));
        
        $data = [
            'surat' => $surat,
            'type' => $type,
            'jenisSurat' => $jenisSurat,
            'user' => $user,
            'tanggal_cetak' => now()->format('d F Y')
        ];
        
        $pdf = PDF::loadView('user.surat.print.' . $type, $data);
        
        return $pdf->download('surat_' . $type . '_' . $surat->nama . '.pdf');
    }
    
    public function show($type, $id)
    {
        $surat = $this->getSuratByType($type, $id);
        $user = Auth::user();
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan');
        }
        
        // Check if user is the owner
        if ($surat->nik !== $user->nik) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke surat ini');
        }
        
        $stages = [];
        $progress = 0;
        $currentStage = 0;
        
        if ($surat->tahapan_verifikasi) {
            $stages = is_string($surat->tahapan_verifikasi) ? json_decode($surat->tahapan_verifikasi, true) : $surat->tahapan_verifikasi;
            $progress = VerificationStageService::getProgressPercentage($stages);
            $currentStage = VerificationStageService::getCurrentStage($stages);
        }
        
        $jenisSuratMap = [
            'domisili' => 'Surat Keterangan Domisili',
            'tidak_mampu' => 'Surat Keterangan Tidak Mampu',
            'belum_menikah' => 'Surat Keterangan Belum Menikah'
        ];
        
        $jenisSurat = $jenisSuratMap[$type] ?? ucfirst(str_replace('_', ' ', $type));
        
        return view('user.surat.show', compact('surat', 'type', 'stages', 'progress', 'currentStage', 'jenisSurat'));
    }
    
    private function getSuratByType($type, $id)
    {
        switch ($type) {
            case 'domisili':
                return Domisili::find($id);
            case 'tidak_mampu':
                return TidakMampu::find($id);
            case 'belum_menikah':
                return BelumMenikah::find($id);
            default:
                return null;
        }
    }
}
