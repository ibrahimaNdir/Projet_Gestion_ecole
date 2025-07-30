<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeAcademique extends Model
{
    use HasFactory;
    protected $table = 'annees_academiques';

    // Champs qui peuvent être remplis massivement
    protected $fillable = [
        'annee_debut',
        'annee_fin',
        'est_actuelle',
    ];


    public function periodesEvaluation()
    {

        return $this->hasMany(PeriodeEvaluation::class, 'annee_academique_id');
    }

}
