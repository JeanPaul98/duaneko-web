<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// php artisan db:seed --class=ZoneSeeder

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('zones')->insert([
            'name' => "Ouakam",
            'google_map_name' => "Ouakam",
            'northeast_latitude' => "14.7333799",
            'northeast_longitude' => "-17.4755624",
            'southwest_latitude' => '14.7097626',
            'southwest_longitude' => "-17.5098244",
            'company_id' => "1",
        ]);
    }
}
