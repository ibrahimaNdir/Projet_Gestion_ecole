<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notes extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notes'; // Assurez-vous que c'est le nom exact de votre table

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'eleve_id',
        'cours_id',
        'valeur_note',
        'date_saisie',
        'periode_evaluation_id',
        'type_evaluation',
        'commentaire_enseignant',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_saisie' => 'date', // Convertit automatiquement la colonne en objet Carbon (date)
    ];

    // --- Relations ---

    /**
     * Une note appartient à un élève.
     *
     * @return BelongsTo
     */
    public function eleve(): BelongsTo
    {
        // 'eleve_id' est la clé étrangère dans la table 'notes'
        // 'id' est la clé primaire dans la table 'eleves'
        return $this->belongsTo(Eleve::class, 'eleve_id');
    }

    /**
     * Une note est associée à un cours spécifique.
     *
     * @return BelongsTo
     */
    public function cours(): BelongsTo
    {
        // 'cours_id' est la clé étrangère dans la table 'notes'
        // 'id' est la clé primaire dans la table 'cours'
        return $this->belongsTo(Cours::class, 'cours_id');
    }

    /**
     * Une note est attribuée pour une période d'évaluation spécifique.
     *
     * @return BelongsTo
     */
    public function periodeEvaluation(): BelongsTo
    {
        // 'periode_evaluation_id' est la clé étrangère dans la table 'notes'
        // 'id' est la clé primaire dans la table 'periodes_evaluation'
        return $this->belongsTo(PeriodeEvaluation::class, 'periode_evaluation_id');
    }
}
