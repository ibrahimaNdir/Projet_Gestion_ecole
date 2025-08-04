<?php

namespace Database\Seeders;

use App\Models\Enseignant;
use Illuminate\Database\Seeder;

class EnseignantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enseignants = [
            [
                'nom' => 'Dubois',
                'prenom' => 'Jean',
                'date_naissance' => '1980-03-15',
                'lieu_naissance' => 'Paris, France',
                'sexe' => 'M',
                'adresse' => '123 Avenue de la République, 75011 Paris',
                'telephone' => '+33 6 11 22 33 44',
                'email' => 'jean.dubois@school.com',
                'specialite' => 'Mathématiques',
                'diplome' => 'Master en Mathématiques',
                'date_embauche' => '2015-09-01',
                'statut' => 'actif'
            ],
            [
                'nom' => 'Leroy',
                'prenom' => 'Marie',
                'date_naissance' => '1985-07-22',
                'lieu_naissance' => 'Lyon, France',
                'sexe' => 'F',
                'adresse' => '456 Rue de la Croix-Rousse, 69004 Lyon',
                'telephone' => '+33 6 55 66 77 88',
                'email' => 'marie.leroy@school.com',
                'specialite' => 'Français',
                'diplome' => 'Master en Littérature',
                'date_embauche' => '2016-09-01',
                'statut' => 'actif'
            ],
            [
                'nom' => 'Garcia',
                'prenom' => 'Carlos',
                'date_naissance' => '1982-11-08',
                'lieu_naissance' => 'Madrid, Espagne',
                'sexe' => 'M',
                'adresse' => '789 Boulevard Saint-Michel, 75005 Paris',
                'telephone' => '+33 6 99 88 77 66',
                'email' => 'carlos.garcia@school.com',
                'specialite' => 'Histoire-Géographie',
                'diplome' => 'Master en Histoire',
                'date_embauche' => '2017-09-01',
                'statut' => 'actif'
            ],
            [
                'nom' => 'Simon',
                'prenom' => 'Anne',
                'date_naissance' => '1988-04-12',
                'lieu_naissance' => 'Bordeaux, France',
                'sexe' => 'F',
                'adresse' => '321 Cours de l\'Intendance, 33000 Bordeaux',
                'telephone' => '+33 6 44 33 22 11',
                'email' => 'anne.simon@school.com',
                'specialite' => 'Sciences Physiques',
                'diplome' => 'Master en Physique',
                'date_embauche' => '2018-09-01',
                'statut' => 'actif'
            ],
            [
                'nom' => 'Rousseau',
                'prenom' => 'Pierre',
                'date_naissance' => '1983-09-30',
                'lieu_naissance' => 'Strasbourg, France',
                'sexe' => 'M',
                'adresse' => '654 Grand\'Rue, 67000 Strasbourg',
                'telephone' => '+33 6 77 66 55 44',
                'email' => 'pierre.rousseau@school.com',
                'specialite' => 'Sciences de la Vie et de la Terre',
                'diplome' => 'Master en Biologie',
                'date_embauche' => '2019-09-01',
                'statut' => 'actif'
            ]
        ];

        foreach ($enseignants as $enseignant) {
            Enseignant::create($enseignant);
        }
    }
}