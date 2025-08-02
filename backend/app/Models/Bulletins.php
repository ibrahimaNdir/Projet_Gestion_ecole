<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bulletins extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bulletins'; // Assurez-vous que c'est le nom exact de votre table

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'eleve_id',
        'annee_academique_id',
        'periode_evaluation_id',
        'date_generation',
        'url_bulletin_pdf',
        'moyenne_generale',
        'mention',
        'rang_classe',
        'appreciation_generale',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_generation' => 'date', // Convertit automatiquement la colonne en objet Carbon (date)
        // 'moyenne_generale' => 'decimal:2', // Optionnel: pour s'assurer que la moyenne est toujours un décimal à 2 chiffres
    ];

    // --- Relations ---

    /**
     * Un bulletin appartient à un élève.
     *
     * @return BelongsTo
     */
    public function eleve(): BelongsTo
    {
        // 'eleve_id' est la clé étrangère dans la table 'bulletins'
        // 'id' est la clé primaire dans la table 'eleves'
        return $this->belongsTo(Eleve::class, 'eleve_id');
    }

    /**
     * Un bulletin est associé à une année académique spécifique.
     *
     * @return BelongsTo
     */
    public function anneeAcademique(): BelongsTo
    {
        // 'annee_academique_id' est la clé étrangère dans la table 'bulletins'
        // 'id' est la clé primaire dans la table 'annees_academiques'
        return $this->belongsTo(AnneeAcademique::class, 'annee_academique_id');
    }

    /**
     * Un bulletin est attribué pour une période d'évaluation spécifique.
     *
     * @return BelongsTo
     */
    public function periodeEvaluation(): BelongsTo
    {
        // 'periode_evaluation_id' est la clé étrangère dans la table 'bulletins'
        // 'id' est la clé primaire dans la table 'periodes_evaluation'
        return $this->belongsTo(PeriodeEvaluation::class, 'periode_evaluation_id');
    }
}
