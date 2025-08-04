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
        Schema::table('enseignants', function (Blueprint $table) {
            $table->string('lieu_naissance')->nullable()->after('date_naissance');
            $table->enum('sexe', ['M', 'F'])->nullable()->after('lieu_naissance');
            $table->text('adresse')->nullable()->after('sexe');
            $table->string('email')->unique()->nullable()->after('telephone');
            $table->string('photo')->nullable()->after('email');
            $table->string('specialite')->nullable()->after('photo');
            $table->string('diplome')->nullable()->after('specialite');
            $table->date('date_embauche')->nullable()->after('diplome');
            $table->enum('statut', ['actif', 'inactif'])->default('actif')->after('date_embauche');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enseignants', function (Blueprint $table) {
            $table->dropColumn([
                'lieu_naissance',
                'sexe',
                'adresse',
                'email',
                'photo',
                'specialite',
                'diplome',
                'date_embauche',
                'statut'
            ]);
        });
    }
};
