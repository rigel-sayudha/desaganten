<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StatistikPendidikan;
use App\Models\StatistikPekerjaan;
use App\Models\StatistikUmur;

class TestModelsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test statistik models after table rename';

    public function handle()
    {
        $this->info('Testing renamed statistik tables...');
        
        try {
            $pendidikanCount = StatistikPendidikan::count();
            $this->info("✅ StatistikPendidikan table connection: OK (records: $pendidikanCount)");
        } catch (\Exception $e) {
            $this->error("❌ StatistikPendidikan table connection: ERROR - " . $e->getMessage());
        }

        try {
            $pekerjaanCount = StatistikPekerjaan::count();
            $this->info("✅ StatistikPekerjaan table connection: OK (records: $pekerjaanCount)");
        } catch (\Exception $e) {
            $this->error("❌ StatistikPekerjaan table connection: ERROR - " . $e->getMessage());
        }

        try {
            $umurCount = StatistikUmur::count();
            $this->info("✅ StatistikUmur table connection: OK (records: $umurCount)");
        } catch (\Exception $e) {
            $this->error("❌ StatistikUmur table connection: ERROR - " . $e->getMessage());
        }

        $this->info('Table rename migration verification completed!');
        
        return 0;
    }
}
