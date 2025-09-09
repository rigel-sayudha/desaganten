<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RekapSuratKeluar;
use App\Models\Surat;
use App\Models\Domisili;
use App\Models\TidakMampu;
use App\Models\SuratUsaha;
use App\Models\SuratKtp;
use Illuminate\Support\Facades\DB;

class SyncRekapSuratCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:rekap-surat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data from existing surat tables to rekap_surat_keluars';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting data synchronization...');
        
        // Clear existing data
        RekapSuratKeluar::truncate();
        $this->info('Cleared existing rekap data.');
        
        $synced = 0;
        
        // Sync from Surat table
        $this->info('Syncing from main Surat table...');
        $surats = Surat::all();
        foreach ($surats as $surat) {
            RekapSuratKeluar::create([
                'tanggal_surat' => $surat->created_at->format('Y-m-d'),
                'nomor_surat' => $surat->nomor_surat ?? null,
                'nama_pemohon' => $surat->nama ?? $surat->nama_lengkap ?? 'Unknown',
                'jenis_surat' => $surat->jenis_surat ?? 'Surat Umum',
                'untuk_keperluan' => $surat->keperluan ?? 'Tidak disebutkan',
                'status' => $this->mapStatus($surat->status),
                'keterangan' => 'Data dari tabel surat',
                'surat_type' => 'Surat',
                'surat_id' => $surat->id,
            ]);
            $synced++;
        }
        
        // Sync from Domisili table
        $this->info('Syncing from Domisili table...');
        $domisilis = Domisili::all();
        foreach ($domisilis as $domisili) {
            RekapSuratKeluar::create([
                'tanggal_surat' => $domisili->created_at->format('Y-m-d'),
                'nomor_surat' => $domisili->nomor_surat ?? null,
                'nama_pemohon' => $domisili->nama ?? $domisili->nama_lengkap ?? 'Unknown',
                'jenis_surat' => 'Surat Keterangan Domisili',
                'untuk_keperluan' => $domisili->keperluan ?? 'Keterangan Domisili',
                'status' => $this->mapStatus($domisili->status),
                'keterangan' => 'Data dari tabel domisili',
                'surat_type' => 'Domisili',
                'surat_id' => $domisili->id,
            ]);
            $synced++;
        }
        
        // Sync from TidakMampu table
        $this->info('Syncing from TidakMampu table...');
        $tidakMampus = TidakMampu::all();
        foreach ($tidakMampus as $tidakMampu) {
            RekapSuratKeluar::create([
                'tanggal_surat' => $tidakMampu->created_at->format('Y-m-d'),
                'nomor_surat' => $tidakMampu->nomor_surat ?? null,
                'nama_pemohon' => $tidakMampu->nama ?? $tidakMampu->nama_lengkap ?? 'Unknown',
                'jenis_surat' => 'Surat Keterangan Tidak Mampu',
                'untuk_keperluan' => $tidakMampu->keperluan ?? 'Keterangan Tidak Mampu',
                'status' => $this->mapStatus($tidakMampu->status),
                'keterangan' => 'Data dari tabel tidak_mampu',
                'surat_type' => 'TidakMampu',
                'surat_id' => $tidakMampu->id,
            ]);
            $synced++;
        }
        
        // Sync from SuratUsaha table if exists
        if (class_exists('App\Models\SuratUsaha')) {
            $this->info('Syncing from SuratUsaha table...');
            $suratUsahas = SuratUsaha::all();
            foreach ($suratUsahas as $suratUsaha) {
                RekapSuratKeluar::create([
                    'tanggal_surat' => $suratUsaha->created_at->format('Y-m-d'),
                    'nomor_surat' => $suratUsaha->nomor_surat ?? null,
                    'nama_pemohon' => $suratUsaha->nama ?? $suratUsaha->nama_lengkap ?? 'Unknown',
                    'jenis_surat' => 'Surat Keterangan Usaha',
                    'untuk_keperluan' => $suratUsaha->keperluan ?? 'Keterangan Usaha',
                    'status' => $this->mapStatus($suratUsaha->status),
                    'keterangan' => 'Data dari tabel surat_usaha',
                    'surat_type' => 'SuratUsaha',
                    'surat_id' => $suratUsaha->id,
                ]);
                $synced++;
            }
        }
        
        $this->info("Synchronization completed! Total synced: $synced records");
        
        return 0;
    }
    
    private function mapStatus($originalStatus)
    {
        switch (strtolower($originalStatus)) {
            case 'pending':
            case 'menunggu':
            case 'menunggu_verifikasi':
                return 'pending';
            case 'diproses':
            case 'proses':
            case 'sedang_diproses':
                return 'diproses';
            case 'selesai':
            case 'completed':
            case 'approved':
            case 'selesai_diproses':
                return 'selesai';
            case 'ditolak':
            case 'rejected':
                return 'ditolak';
            default:
                return 'pending';
        }
    }
}
