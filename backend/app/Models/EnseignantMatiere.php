<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnseignantMatiere extends Model
{
    use HasFactory;
    protected $table = 'enseignant_matiere'; // Assure-toi que c'est le nom exact de ta table

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'enseignant_id',
        'matiere_id',
        'annee_academique_id',
        // Ajoute ici toute autre colonne spécifique que tu pourrais avoir dans ta table pivot,
        // par exemple, 'date_affectation_debut', 'date_affectation_fin'
    ];

    /**
     * Indique si l'ID est auto-incrémenté.
     * Par défaut à true pour les modèles, mais utile de le noter pour les tables pivots avec PK simple.
     *
     * @var bool
     */
    public $incrementing = true; // Si tu as $table->id() dans ta migration

    // --- Relations inverses (optionnel mais utile pour naviguer depuis la pivot) ---

    /**
     * L'enseignant lié à cette affectation.
     *
     * @return BelongsTo
     */
    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(Enseignant::class, 'enseignant_id');
    }

    /**
     * La matière liée à cette affectation.
     *
     * @return BelongsTo
     */
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    /**
     * L'année académique liée à cette affectation.
     *
     * @return BelongsTo
     */
    public function anneeAcademique(): BelongsTo
    {
        return $this->belongsTo(AnneeAcademique::class, 'annee_academique_id');
    }
}
