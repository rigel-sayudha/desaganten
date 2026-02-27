<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckTablesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all tables and their data count';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking all tables and their data count...');
        
        $tables = [
            'surat',
            'domisili', 
            'tidak_mampu',
            'belum_menikah',
            'surat_usaha',
            'surat_ktp',
            'surat_kematian',
            'surat_kelahiran',
            'surat_skck',
            'surat_kk',
            'surat_kehilangan',
            'rekap_surat_keluars'
        ];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $this->info("Table: $table - Records: $count");
                
                if ($count > 0 && $count <= 5) {
                    $this->info("Sample data from $table:");
                    $samples = DB::table($table)->limit(2)->get();
                    foreach ($samples as $sample) {
                        $this->line("  ID: " . ($sample->id ?? 'N/A'));
                        if (isset($sample->nama)) $this->line("  Nama: " . $sample->nama);
                        if (isset($sample->nama_lengkap)) $this->line("  Nama Lengkap: " . $sample->nama_lengkap);
                        if (isset($sample->jenis_surat)) $this->line("  Jenis Surat: " . $sample->jenis_surat);
                        if (isset($sample->status)) $this->line("  Status: " . $sample->status);
                        if (isset($sample->created_at)) $this->line("  Created: " . $sample->created_at);
                        $this->line("  ---");
                    }
                }
            } else {
                $this->error("Table: $table - NOT EXISTS");
            }
        }
        
        return 0;
    }
}
