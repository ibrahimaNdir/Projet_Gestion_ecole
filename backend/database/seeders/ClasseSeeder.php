<?php

namespace Database\Seeders;

use App\Models\Classe;
use Illuminate\Database\Seeder;

class ClasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            [
                'nom' => '6ème A',
                'niveau' => '6ème',
                'capacite' => 30,
                'description' => 'Classe de 6ème section A',
                'statut' => 'active'
            ],
            [
                'nom' => '6ème B',
                'niveau' => '6ème',
                'capacite' => 28,
                'description' => 'Classe de 6ème section B',
                'statut' => 'active'
            ],
            [
                'nom' => '5ème A',
                'niveau' => '5ème',
                'capacite' => 32,
                'description' => 'Classe de 5ème section A',
                'statut' => 'active'
            ],
            [
                'nom' => '5ème B',
                'niveau' => '5ème',
                'capacite' => 30,
                'description' => 'Classe de 5ème section B',
                'statut' => 'active'
            ],
            [
                'nom' => '4ème A',
                'niveau' => '4ème',
                'capacite' => 29,
                'description' => 'Classe de 4ème section A',
                'statut' => 'active'
            ],
            [
                'nom' => '4ème B',
                'niveau' => '4ème',
                'capacite' => 31,
                'description' => 'Classe de 4ème section B',
                'statut' => 'active'
            ],
            [
                'nom' => '3ème A',
                'niveau' => '3ème',
                'capacite' => 27,
                'description' => 'Classe de 3ème section A',
                'statut' => 'active'
            ],
            [
                'nom' => '3ème B',
                'niveau' => '3ème',
                'capacite' => 28,
                'description' => 'Classe de 3ème section B',
                'statut' => 'active'
            ]
        ];

        foreach ($classes as $classe) {
            Classe::create($classe);
        }
    }
}