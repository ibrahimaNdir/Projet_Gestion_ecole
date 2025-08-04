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
        Schema::table('classes', function (Blueprint $table) {
            $table->renameColumn('nom_classe', 'nom');
            $table->renameColumn('niveau_scolaire', 'niveau');
            $table->integer('capacite')->nullable()->after('niveau');
            $table->text('description')->nullable()->after('capacite');
            $table->enum('statut', ['active', 'inactive'])->default('active')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->renameColumn('nom', 'nom_classe');
            $table->renameColumn('niveau', 'niveau_scolaire');
            $table->dropColumn(['capacite', 'description', 'statut']);
        });
    }
};
