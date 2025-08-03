<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enseignants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('utilisateur_id')
                ->unique()
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');


            $table->string('nom');
            $table->string('prenom');

            // Colonnes optionnelles.
            $table->date('date_naissance')->nullable(); // Peut être NULL
            $table->string('telephone')->nullable();    // Peut être NULL

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignants');
    }
};
