<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// php artisan db:seed --class=AdminSeeder

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->insert([
            'name' => "Birante SY",
            'email' => 'birantesy@gmail.com',
            'password' => Hash::make('birantesy@gmail.com'),
        ]);
    }
}
