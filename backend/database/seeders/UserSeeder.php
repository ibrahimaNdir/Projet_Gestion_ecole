<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupère le rôle 'admin'
        // Assurez-vous que RoleSeeder est exécuté AVANT UserSeeder
        $adminRole = Role::where('nom_roles', 'admin')->first();

        // Si le rôle admin n'existe pas (ce qui ne devrait pas arriver si RoleSeeder est exécuté avant)
        if (!$adminRole) {
            // Vous pouvez choisir de créer le rôle ici ou de lancer une erreur
            $this->command->error('Le rôle "admin" n\'a pas été trouvé. Assurez-vous que RoleSeeder est exécuté en premier.');
            return;
        }

        // Crée un utilisateur admin s'il n'existe pas déjà
        User::firstOrCreate(
            ['email' => 'admin@school.com'], // Condition pour trouver l'utilisateur existant
            [
                'nom_utilisateur' => 'Admin Principal',
                'mot_de_passe' => Hash::make('password'), // Hachez toujours les mots de passe !
                'roles_id' => $adminRole->id,
                'email_verified_at' => now(), // Optionnel: marquer l'email comme vérifié
            ]
        );

        $this->command->info('Utilisateur admin créé ou déjà existant.');
        //
    }
}
