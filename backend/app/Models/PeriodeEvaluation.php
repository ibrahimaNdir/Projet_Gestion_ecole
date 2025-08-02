<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeEvaluation extends Model
{
    use HasFactory;
    // Spécifie explicitement le nom de la table
    protected $table = 'periodes_evaluation';

    // Champs qui peuvent être remplis massivement
    protected $fillable = [
        'nom_periode',
        'annee_academique_id',
        'date_debut',
        'date_fin',
    ];
    // Définis la relation avec le modèle AnneeAcademique
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'annee_academique_id');
    }
}
