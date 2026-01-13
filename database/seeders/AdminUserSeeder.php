<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        \App\Models\User::updateOrCreate(
            ['email' => 'superadmin@bps.com'],
            [
                'name' => 'Super Admin BPS',
                'password' => bcrypt('password'),
                'role' => 'super_admin',
            ]
        );

        // Create Regular Admin
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@bps.com'],
            [
                'name' => 'Admin BPS',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );
    }
}
