<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Source: database/seeders/admin_user.js — seedAdminUser
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@kiluah.test');
        $password = env('ADMIN_PASS', 'password');

        if (User::where('email', $email)->exists()) {
            $this->command?->info('Admin user already exists, skipping.');

            return;
        }

        User::create([
            'name'     => 'Admin',
            'email'    => $email,
            'password' => Hash::make($password),
            'role'     => RoleEnum::ADMIN,
        ]);

        $this->command?->info('Admin user created successfully.');
    }
}
