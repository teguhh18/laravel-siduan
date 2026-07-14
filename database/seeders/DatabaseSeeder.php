<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' => '08111111111',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Petugas',
            'email' => 'petugas@gmail.com',
            'phone' => '082222222222',
            'role' => 'petugas',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Masyarakat',
            'email' => 'masyarakat@gmail.com',
            'phone' => '083333333333',
            'role' => 'masyarakat',
            'password' => Hash::make('password'),
        ]);
    }
}
