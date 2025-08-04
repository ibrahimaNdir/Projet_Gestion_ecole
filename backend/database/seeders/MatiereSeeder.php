<?php

namespace Database\Seeders;

use App\Models\Matiere;
use Illuminate\Database\Seeder;

class MatiereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $matieres = [
            [
                'nom' => 'Mathématiques',
                'code' => 'MATH',
                'description' => 'Mathématiques générales',
                'statut' => 'active'
            ],
            [
                'nom' => 'Français',
                'code' => 'FRAN',
                'description' => 'Langue française et littérature',
                'statut' => 'active'
            ],
            [
                'nom' => 'Histoire-Géographie',
                'code' => 'HIST',
                'description' => 'Histoire et géographie',
                'statut' => 'active'
            ],
            [
                'nom' => 'Sciences Physiques',
                'code' => 'PHYS',
                'description' => 'Physique et chimie',
                'statut' => 'active'
            ],
            [
                'nom' => 'Sciences de la Vie et de la Terre',
                'code' => 'SVT',
                'description' => 'Biologie et géologie',
                'statut' => 'active'
            ],
            [
                'nom' => 'Anglais',
                'code' => 'ANG',
                'description' => 'Langue anglaise',
                'statut' => 'active'
            ],
            [
                'nom' => 'Espagnol',
                'code' => 'ESP',
                'description' => 'Langue espagnole',
                'statut' => 'active'
            ],
            [
                'nom' => 'Allemand',
                'code' => 'ALL',
                'description' => 'Langue allemande',
                'statut' => 'active'
            ],
            [
                'nom' => 'Technologie',
                'code' => 'TECH',
                'description' => 'Technologie et informatique',
                'statut' => 'active'
            ],
            [
                'nom' => 'Arts Plastiques',
                'code' => 'ART',
                'description' => 'Arts plastiques et visuels',
                'statut' => 'active'
            ],
            [
                'nom' => 'Éducation Musicale',
                'code' => 'MUS',
                'description' => 'Musique et culture musicale',
                'statut' => 'active'
            ],
            [
                'nom' => 'Éducation Physique et Sportive',
                'code' => 'EPS',
                'description' => 'Sport et activités physiques',
                'statut' => 'active'
            ]
        ];

        foreach ($matieres as $matiere) {
            Matiere::create($matiere);
        }
    }
}