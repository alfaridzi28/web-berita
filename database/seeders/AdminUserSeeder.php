<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@web-berita.com'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@web-berita.com',
                'password' => Hash::make('password123'),
            ]
        );

        $this->command->info('Admin user created: admin@web-berita.com / password123');
    }
}
