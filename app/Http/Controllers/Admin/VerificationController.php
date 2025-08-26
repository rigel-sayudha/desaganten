<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VerificationStageService;
use App\Models\Domisili;
use App\Models\TidakMampu;
use App\Models\BelumMenikah;

class VerificationController extends Controller
{
    public function index()
    {
        // Get all surat that need verification
        $domisiliSurat = Domisili::where('status', '!=', 'sudah diverifikasi')->get();
        $tidakMampuSurat = TidakMampu::where('status', '!=', 'sudah diverifikasi')->get();
        $belumMenikahSurat = BelumMenikah::where('status', '!=', 'sudah diverifikasi')->get();

        // Calculate statistics
        $totalPending = $domisiliSurat->count() + $tidakMampuSurat->count() + $belumMenikahSurat->count();
        $totalDomisili = $domisiliSurat->count();
        $totalTidakMampu = $tidakMampuSurat->count();
        $totalBelumMenikah = $belumMenikahSurat->count();

        return view('admin.verification.index', compact(
            'domisiliSurat',
            'tidakMampuSurat', 
            'belumMenikahSurat',
            'totalPending',
            'totalDomisili',
            'totalTidakMampu',
            'totalBelumMenikah'
        ));
    }

    public function show($type, $id)
    {
        $surat = $this->getSuratByType($type, $id);
        
        if (!$surat) {
            return redirect()->back()->with('error', 'Surat tidak ditemukan');
        }

        // Initialize stages if not exists
        if (empty($surat->tahapan_verifikasi)) {
            $stages = VerificationStageService::initializeStages($type);
            $surat->tahapan_verifikasi = $stages;
            $surat->save();
        } else {
            $stages = $surat->tahapan_verifikasi;
            
            // Ensure stages is an array (handle JSON string from database)
            if (is_string($stages)) {
                $stages = json_decode($stages, true);
            }
            
            // If still not valid array, reinitialize
            if (!is_array($stages) || empty($stages)) {
                $stages = VerificationStageService::initializeStages($type);
                $surat->tahapan_verifikasi = $stages;
                $surat->save();
            }
        }

        $progress = VerificationStageService::getProgressPercentage($stages);
        $currentStage = VerificationStageService::getCurrentStage($stages);
        $estimatedCompletion = VerificationStageService::getEstimatedCompletion($stages) ?? now()->addDays(7);

        return view('admin.verification.show', compact(
            'surat', 'type', 'stages', 'progress', 'currentStage', 'estimatedCompletion'
        ));
    }

    public function updateStage(Request $request, $type, $id, $stageNumber)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,rejected',
            'notes' => 'nullable|string|max:1000'
        ]);

        $surat = $this->getSuratByType($type, $id);
        
        if (!$surat) {
            return response()->json(['error' => 'Surat tidak ditemukan'], 404);
        }

        $stages = $surat->tahapan_verifikasi ?? [];
        
        // Ensure stages is an array (handle JSON string from database)
        if (is_string($stages)) {
            $stages = json_decode($stages, true);
        }
        
        if (!is_array($stages)) {
            $stages = [];
        }
        
        $stages = VerificationStageService::updateStageStatus(
            $stages, 
            (int)$stageNumber, 
            $request->status, 
            $request->notes,
            auth()->user()->name
        );

        $surat->tahapan_verifikasi = $stages;
        $surat->tanggal_verifikasi_terakhir = now();
        
        // Update overall status based on stages
        $previousStatus = $surat->status;
        if ($request->status === 'rejected') {
            $surat->status = 'ditolak';
        } elseif (VerificationStageService::getProgressPercentage($stages) === 100) {
            $surat->status = 'sudah diverifikasi';
        } else {
            $surat->status = 'diproses';
        }

        $surat->save();

        // Check if surat just got verified (100% complete)
        $currentProgress = VerificationStageService::getProgressPercentage($stages);
        if ($currentProgress === 100 && $previousStatus !== 'sudah diverifikasi') {
            // Add notification for user about completed verification
            session()->flash('verification_completed', [
                'type' => $type,
                'id' => $surat->id,
                'nama' => $surat->nama,
                'message' => 'Surat keterangan telah selesai diverifikasi dan dapat diunduh'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tahapan verifikasi berhasil diperbarui',
            'progress' => VerificationStageService::getProgressPercentage($stages),
            'currentStage' => VerificationStageService::getCurrentStage($stages)
        ]);
    }

    public function addNote(Request $request, $type, $id)
    {
        $request->validate([
            'note' => 'required|string|max:1000'
        ]);

        $surat = $this->getSuratByType($type, $id);
        
        if (!$surat) {
            return response()->json(['error' => 'Surat tidak ditemukan'], 404);
        }

        $existingNotes = $surat->catatan_verifikasi ?? '';
        $newNote = '[' . now()->format('d/m/Y H:i') . '] ' . auth()->user()->name . ': ' . $request->note;
        
        $surat->catatan_verifikasi = $existingNotes ? $existingNotes . "\n" . $newNote : $newNote;
        $surat->save();

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil ditambahkan',
            'note' => $newNote
        ]);
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
            case 'ktp':
                return \App\Models\SuratKtp::find($id);
            case 'kk':
            case 'skck':
            case 'kematian':
            case 'kelahiran':
                // For general surat types, try to find in Surat model
                return \App\Models\Surat::where('id', $id)
                                         ->where('jenis_surat', $type)
                                         ->first();
            default:
                // Fallback: try to find in general Surat model
                return \App\Models\Surat::find($id);
        }
    }
}
