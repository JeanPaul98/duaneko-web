<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// php artisan db:seed --class=AgentSeeder

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('agents')->insert(
        [
            'first_name' => "Sellé",
            'last_name' => "Diop",
            'phone_number' => "772723430",
            'email' => 'diop@gmail.com',
            'password' => Hash::make('diop@gmail.com'),
            'company_id' => "1",
        ]);
    }
}
