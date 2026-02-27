<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SuratKtp;
use App\Models\SuratKehilangan;
use App\Models\Domisili;
use App\Services\VerificationStageService;
use Illuminate\Support\Facades\Log;

class TestAllForms extends Command
{
    protected $signature = 'test:all-forms';
    protected $description = 'Test all surat forms functionality';

    public function handle()
    {
        $this->info('🧪 Testing All Forms Functionality...');
        
        // Get or create test user
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'nik' => '1234567890123456',
            ]);
            $this->info("Created test user: {$user->email}");
        }

        // Test 1: KTP Form
        $this->info("\n📝 Testing KTP Form...");
        try {
            $ktp = SuratKtp::create([
                'user_id' => $user->id,
                'nama_lengkap' => 'Test KTP User',
                'jenis_kelamin' => 'Laki-laki',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Kawin',
                'nik' => '1234567890123456',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-01',
                'pekerjaan' => 'Programmer',
                'alamat' => 'Jl. Test No. 123',
                'keperluan' => 'Testing KTP form',
                'status' => 'menunggu',
            ]);
            $this->info("✅ KTP Form: SUCCESS (ID: {$ktp->id})");
            $ktp->delete();
        } catch (\Exception $e) {
            $this->error("❌ KTP Form: FAILED - " . $e->getMessage());
        }

        // Test 2: VerificationStageService
        $this->info("\n🔄 Testing VerificationStageService...");
        try {
            $stages = VerificationStageService::initializeStages('ktp');
            $this->info("✅ VerificationStageService: SUCCESS");
            $this->info("   Stages count: " . count($stages));
        } catch (\Exception $e) {
            $this->error("❌ VerificationStageService: FAILED - " . $e->getMessage());
            $this->error("   File: " . $e->getFile());
            $this->error("   Line: " . $e->getLine());
        }

        // Test 3: Kehilangan Form
        $this->info("\n📋 Testing Kehilangan Form...");
        try {
            $kehilangan = SuratKehilangan::create([
                'user_id' => $user->id,
                'nama_lengkap' => 'Test Kehilangan User',
                'nik' => '1234567890123456',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-01',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Kawin',
                'kewarganegaraan' => 'WNI',
                'pekerjaan' => 'Programmer',
                'alamat' => 'Jl. Test No. 123',
                'barang_hilang' => 'KTP',
                'deskripsi_barang' => 'KTP atas nama Test User',
                'tempat_kehilangan' => 'Jakarta',
                'tanggal_kehilangan' => '2025-01-01',
                'waktu_kehilangan' => '10:00:00',
                'kronologi_kehilangan' => 'Test kronologi kehilangan KTP',
                'keperluan' => 'Testing kehilangan form',
                'status' => 'menunggu',
            ]);
            $this->info("✅ Kehilangan Form: SUCCESS (ID: {$kehilangan->id})");
            $kehilangan->delete();
        } catch (\Exception $e) {
            $this->error("❌ Kehilangan Form: FAILED - " . $e->getMessage());
        }

        // Test 4: Domisili Form  
        $this->info("\n🏠 Testing Domisili Form...");
        try {
            $domisili = Domisili::create([
                'user_id' => $user->id,
                'nama' => 'Test Domisili User',
                'nik' => '1234567890123456',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'L', // L for Laki-laki, P for Perempuan
                'kewarganegaraan' => 'Indonesia',
                'agama' => 'Islam',
                'pekerjaan' => 'Programmer',
                'alamat' => 'Jl. Test No. 123',
                'keperluan' => 'Testing domisili form',
                'status' => 'pending',
            ]);
            $this->info("✅ Domisili Form: SUCCESS (ID: {$domisili->id})");
            $domisili->delete();
        } catch (\Exception $e) {
            $this->error("❌ Domisili Form: FAILED - " . $e->getMessage());
        }

        $this->info("\n📊 Form Testing Complete!");
        $this->info("Check storage/logs/laravel.log for detailed logs");
    }
}
