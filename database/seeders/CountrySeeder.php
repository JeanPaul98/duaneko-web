<?php

namespace Database\Seeders;

use App\Models\AdministrativeDivision;
use App\Models\Country;
use App\Models\DivisionLevel;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Peuple le Togo avec sa hiérarchie administrative réelle (Région > Préfecture > Commune)
     * pour servir de gabarit de référence — toutes les données existantes de la plateforme
     * (entreprises, zones) sont togolaises.
     */
    public function run(): void
    {
        $togo = Country::firstOrCreate(
            ['iso_code' => 'TG'],
            ['name' => 'Togo', 'phone_code' => '+228', 'currency' => 'XOF', 'is_active' => true]
        );

        $region = DivisionLevel::firstOrCreate(
            ['country_id' => $togo->id, 'depth' => 0],
            ['name' => 'Région']
        );

        $prefecture = DivisionLevel::firstOrCreate(
            ['country_id' => $togo->id, 'depth' => 1],
            ['name' => 'Préfecture']
        );

        $commune = DivisionLevel::firstOrCreate(
            ['country_id' => $togo->id, 'depth' => 2],
            ['name' => 'Commune']
        );

        $maritime = AdministrativeDivision::firstOrCreate([
            'country_id' => $togo->id,
            'division_level_id' => $region->id,
            'parent_id' => null,
            'name' => 'Région Maritime',
        ]);

        $plateaux = AdministrativeDivision::firstOrCreate([
            'country_id' => $togo->id,
            'division_level_id' => $region->id,
            'parent_id' => null,
            'name' => 'Région des Plateaux',
        ]);

        $golfe = AdministrativeDivision::firstOrCreate([
            'country_id' => $togo->id,
            'division_level_id' => $prefecture->id,
            'parent_id' => $maritime->id,
            'name' => 'Préfecture du Golfe',
        ]);

        $agoeNyive = AdministrativeDivision::firstOrCreate([
            'country_id' => $togo->id,
            'division_level_id' => $prefecture->id,
            'parent_id' => $maritime->id,
            'name' => 'Préfecture d\'Agoè-Nyivé',
        ]);

        AdministrativeDivision::firstOrCreate([
            'country_id' => $togo->id,
            'division_level_id' => $commune->id,
            'parent_id' => $golfe->id,
            'name' => 'Lomé',
        ]);

        AdministrativeDivision::firstOrCreate([
            'country_id' => $togo->id,
            'division_level_id' => $commune->id,
            'parent_id' => $agoeNyive->id,
            'name' => 'Agoè-Nyivé',
        ]);
    }
}
