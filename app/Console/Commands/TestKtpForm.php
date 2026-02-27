<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SuratKtp;
use App\Models\User;

class TestKtpForm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:ktp-form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test KTP form submission';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if we have users
        $userCount = User::count();
        $this->info("Total users in database: $userCount");
        
        if ($userCount === 0) {
            // Create a test user
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'nik' => '1234567890123456',
            ]);
            $this->info("Created test user with ID: {$user->id}");
        } else {
            $user = User::first();
            $this->info("Using existing user with ID: {$user->id}");
        }

        // Test KTP submission
        try {
            $ktpData = [
                'user_id' => $user->id,
                'nama_lengkap' => 'Test User KTP',
                'jenis_kelamin' => 'Laki-laki',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Kawin',
                'nik' => '1234567890123456',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-01',
                'pekerjaan' => 'Programmer',
                'alamat' => 'Jl. Test No. 123',
                'keperluan' => 'Testing form submission',
                'status' => 'menunggu',
            ];

            $surat = SuratKtp::create($ktpData);
            $this->info("✅ KTP form submission test SUCCESSFUL!");
            $this->info("Created surat with ID: {$surat->id}");
            
            // Test with file data
            $ktpWithFiles = [
                'user_id' => $user->id,
                'nama_lengkap' => 'Test User KTP With Files',
                'jenis_kelamin' => 'Perempuan',
                'agama' => 'Kristen',
                'status_perkawinan' => 'Kawin',
                'nik' => '6543210987654321',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1985-05-15',
                'pekerjaan' => 'Designer',
                'alamat' => 'Jl. File Test No. 456',
                'keperluan' => 'Testing with file uploads',
                'status' => 'menunggu',
                'file_ktp' => 'test_ktp.pdf',
                'file_kk' => 'test_kk.pdf',
                'file_dokumen_tambahan' => 'test_dokumen.pdf',
            ];

            $suratWithFiles = SuratKtp::create($ktpWithFiles);
            $this->info("✅ KTP form with files test SUCCESSFUL!");
            $this->info("Created surat with files, ID: {$suratWithFiles->id}");

            // Clean up
            $surat->delete();
            $suratWithFiles->delete();
            $this->info("Test records cleaned up");

        } catch (\Exception $e) {
            $this->error("❌ KTP form submission test FAILED!");
            $this->error("Error: " . $e->getMessage());
            $this->error("File: " . $e->getFile());
            $this->error("Line: " . $e->getLine());
        }
    }
}
