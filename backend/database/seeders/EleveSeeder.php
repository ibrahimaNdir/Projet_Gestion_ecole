<?php

namespace Database\Seeders;

use App\Models\Eleve;
use Illuminate\Database\Seeder;

class EleveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eleves = [
            [
                'nom' => 'Dupont',
                'prenom' => 'Marie',
                'date_naissance' => '2008-05-15',
                'lieu_naissance' => 'Paris, France',
                'sexe' => 'F',
                'adresse' => '123 Rue de la Paix, 75001 Paris',
                'telephone' => '+33 6 12 34 56 78',
                'email' => 'marie.dupont@email.com',
                'statut' => 'actif',
                'date_inscription' => '2023-09-01'
            ],
            [
                'nom' => 'Martin',
                'prenom' => 'Pierre',
                'date_naissance' => '2007-12-03',
                'lieu_naissance' => 'Lyon, France',
                'sexe' => 'M',
                'adresse' => '456 Avenue des Champs, 69001 Lyon',
                'telephone' => '+33 6 98 76 54 32',
                'email' => 'pierre.martin@email.com',
                'statut' => 'actif',
                'date_inscription' => '2023-09-01'
            ],
            [
                'nom' => 'Bernard',
                'prenom' => 'Sophie',
                'date_naissance' => '2009-03-22',
                'lieu_naissance' => 'Marseille, France',
                'sexe' => 'F',
                'adresse' => '789 Boulevard de la Mer, 13001 Marseille',
                'telephone' => '+33 6 45 67 89 01',
                'email' => 'sophie.bernard@email.com',
                'statut' => 'actif',
                'date_inscription' => '2023-09-01'
            ],
            [
                'nom' => 'Petit',
                'prenom' => 'Lucas',
                'date_naissance' => '2008-08-10',
                'lieu_naissance' => 'Toulouse, France',
                'sexe' => 'M',
                'adresse' => '321 Rue du Capitole, 31000 Toulouse',
                'telephone' => '+33 6 23 45 67 89',
                'email' => 'lucas.petit@email.com',
                'statut' => 'actif',
                'date_inscription' => '2023-09-01'
            ],
            [
                'nom' => 'Moreau',
                'prenom' => 'Emma',
                'date_naissance' => '2007-11-18',
                'lieu_naissance' => 'Nantes, France',
                'sexe' => 'F',
                'adresse' => '654 Quai de la Fosse, 44000 Nantes',
                'telephone' => '+33 6 78 90 12 34',
                'email' => 'emma.moreau@email.com',
                'statut' => 'actif',
                'date_inscription' => '2023-09-01'
            ]
        ];

        foreach ($eleves as $eleve) {
            Eleve::create($eleve);
        }
    }
}