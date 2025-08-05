<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,   // Exécuté en premier
            UserSeeder::class,   // Dépend de RoleSeeder, donc exécuté après
            // ... autres seeders (EnseignantSeeder, MatiereSeeder, etc.)
        ]);
    }
}
