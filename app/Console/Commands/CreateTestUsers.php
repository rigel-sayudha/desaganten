<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test users with different roles for testing role-based access control';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin Test',
                'email' => 'admin@test.com',
                'password' => Hash::make('password123'),
                'role' => 'admin'
            ]
        );

        // Create regular user
        $user = User::updateOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'User Test',
                'email' => 'user@test.com',
                'password' => Hash::make('password123'),
                'role' => 'user'
            ]
        );

        $this->info('Test users created successfully!');
        $this->info('Admin: admin@test.com / password123 (role: admin)');
        $this->info('User: user@test.com / password123 (role: user)');
        
        return Command::SUCCESS;
    }
}
