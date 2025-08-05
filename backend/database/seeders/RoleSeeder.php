<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crée le rôle 'admin' s'il n'existe pas déjà
        Role::firstOrCreate(['nom_roles' => 'admin']);

        // Vous pouvez ajouter d'autres rôles ici si nécessaire, par exemple 'enseignant', 'eleve'
        Role::firstOrCreate(['nom_roles' => 'enseignant']);
         Role::firstOrCreate(['nom_roles' => 'eleve']);
        Role::firstOrCreate(['nom_roles' => 'parent']);
        //
    }
}
