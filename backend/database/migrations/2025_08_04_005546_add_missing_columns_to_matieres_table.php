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
        Schema::table('matieres', function (Blueprint $table) {
            $table->renameColumn('nom_matiere', 'nom');
            $table->string('code')->unique()->after('nom');
            $table->text('description')->nullable()->after('code');
            $table->enum('statut', ['active', 'inactive'])->default('active')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matieres', function (Blueprint $table) {
            $table->renameColumn('nom', 'nom_matiere');
            $table->dropColumn(['code', 'description', 'statut']);
        });
    }
};
