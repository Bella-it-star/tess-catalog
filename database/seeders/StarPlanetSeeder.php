<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Star;
use App\Models\ToiPlanet;

class StarPlanetSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Bintang 1
        $star1 = Star::create(['tic_id' => '129637892']);
        ToiPlanet::create([
            'star_id' => $star1->id,
            'toi_number' => '155.01',
            'master_priority' => 3,
            'sg1b_priority' => 4,
            'tess_notes' => 'TESS target',
            'personal_notes' => 'Butuh konfirmasi lanjut'
        ]);

        // Buat Bintang 2
        $star2 = Star::create(['tic_id' => '394570308']);
        ToiPlanet::create([
            'star_id' => $star2->id,
            'toi_number' => '150.01',
            'master_priority' => 3,
            'sg1b_priority' => 4,
            'tess_notes' => 'Observation done',
            'personal_notes' => 'Target prioritas'
        ]);
    }
}