<?php

namespace Database\Seeders;

use App\Models\Campagne;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CampagneSeeder extends Seeder
{
    public function run(): void
    {
        Campagne::create([
            'uuid'                   => (string) Str::uuid(),
            'code_campagne'          => 'CI-2026-10',
            'libelle'                => 'Campagne de déclaration des conflits d\'intérêts 2026',
            'date_ouverture'         => '2026-10-08 00:00:00',
            'date_cloture'           => '2026-10-20 23:59:00',
            'statut'                 => 'OUVERTE',
            'proprietaire_traitement' => 'M. Perol KOULETIO',
            'direction_pilote'       => 'Conformité',
        ]);
    }
}