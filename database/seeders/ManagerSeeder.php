<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// php artisan db:seed --class=ManagerSeeder

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('managers')->insert([
            'first_name' => "Birante",
            'last_name' => "SY",
            'phone_number' => "776857298",
            'email' => 'birantesy@gmail.com',
            'password' => Hash::make('birantesy@gmail.com'),
            'company_id' => "1",
        ]);
    }
}
