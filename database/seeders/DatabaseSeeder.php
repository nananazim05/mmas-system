<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Cipta Akaun Admin Pertama (Tanpa Sengkang)
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@mtib.gov.my',
            'ic_number' => '000000000000', // <--- TUKAR DI SINI (12 digit)
            'staff_number' => 'ADMIN001',
            'password' => Hash::make('password'),
            'section' => 'Unit IT',
            'division' => 'Khidmat Pengurusan',
            'grade' => 'F41',
            'role' => 'admin',
        ]);
    }
}