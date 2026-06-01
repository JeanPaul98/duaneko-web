<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// php artisan db:seed --class=ReportSeeder

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reports')->insert([
            'image' => "",
            'type' => "wild_dumps",
            'latitude' => "14.758749",
            'longitude' => " -17.468954",
            'status' => 'pending',
            'description' => "Maudits soient-ils, dis-je une autre et si, au contraire. Quiconque viendra pour me faire sortir une chose mauvaise.",
            
        ]);
    }
}
