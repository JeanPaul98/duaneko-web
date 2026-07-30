<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// php artisan db:seed --class=UserSeeder

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'first_name' => "Mobile",
            'last_name' => "Test",
            'phone_number' => "770000000",
            'email' => 'mobiletest@gmail.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
