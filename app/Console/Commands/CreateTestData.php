<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Domisili;
use App\Models\SuratKtp;
use App\Models\BelumMenikah;
use App\Models\SuratSkck;
use Illuminate\Support\Facades\Hash;

class CreateTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test data for notification system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Create test user if not exists
        $testUser = User::where('email', 'test@user.com')->first();
        if (!$testUser) {
            $testUser = User::create([
                'name' => 'Test User',
                'email' => 'test@user.com',
                'password' => Hash::make('password'),
                'role' => 'user'
            ]);
            $this->info('Test user created with ID: ' . $testUser->id);
        } else {
            $this->info('Test user already exists with ID: ' . $testUser->id);
        }

        // Create test domisili if not exists
        $domisili = Domisili::where('user_id', $testUser->id)->first();
        if (!$domisili) {
            $domisili = Domisili::create([
                'user_id' => $testUser->id,
                'nama' => 'Test User Domisili',
                'nik' => '1234567890123456',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Test No. 123',
                'keperluan' => 'Test notification system',
                'status_pengajuan' => 'Menunggu Verifikasi'
            ]);
            $this->info('Test domisili created with ID: ' . $domisili->id);
        }

        $this->info('Test data creation completed!');
        $this->info('Test user: test@user.com / password');
        $this->info('Domisili ID: ' . ($domisili ? $domisili->id : 'not created'));
        $this->info('You can now test notifications in admin panel.');
        
        return 0;
    }
}
