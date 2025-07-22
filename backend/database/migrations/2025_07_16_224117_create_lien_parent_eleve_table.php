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
        Schema::create('lien_parent_eleve', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_utilisateur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->string('type_relation')->nullable(); // comme pere, mere, tuteur
            $table->unique(['parent_utilisateur_id', 'eleve_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lien_parent_eleve');
    }
};
