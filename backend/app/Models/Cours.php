<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cours extends Model
{
    use HasFactory;

    protected $table = 'cours'; // Assurez-vous que c'est le nom exact de votre table

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'enseignant_id',
        'matiere_id',
        'classe_id',
        'annee_academique_id',
    ];

    // --- Relations ---

    /**
     * Un cours appartient à un enseignant.
     *
     * @return BelongsTo
     */
    public function enseignant(): BelongsTo
    {
        // 'enseignant_id' est la clé étrangère dans la table 'cours'
        // 'id' est la clé primaire dans la table 'enseignants'
        return $this->belongsTo(Enseignant::class, 'enseignant_id');
    }

    /**
     * Un cours est associé à une matière.
     *
     * @return BelongsTo
     */
    public function matiere(): BelongsTo
    {
        // 'matiere_id' est la clé étrangère dans la table 'cours'
        // 'id' est la clé primaire dans la table 'matieres'
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    /**
     * Un cours est attribué à une classe.
     *
     * @return BelongsTo
     */
    public function classe(): BelongsTo
    {
        // 'classe_id' est la clé étrangère dans la table 'cours'
        // 'id' est la clé primaire dans la table 'classes'
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * Un cours est donné pendant une année académique spécifique.
     *
     * @return BelongsTo
     */
    public function anneeAcademique(): BelongsTo
    {
        // 'annee_academique_id' est la clé étrangère dans la table 'cours'
        // 'id' est la clé primaire dans la table 'annees_academiques'
        return $this->belongsTo(AnneeAcademique::class, 'annee_academique_id');
    }

    /**
     * Un cours peut avoir plusieurs notes associées.
     *
     * @return HasMany
     */
    public function notes(): HasMany
    {
        // 'cours_id' est la clé étrangère dans la table 'notes'
        // 'id' est la clé primaire dans la table 'cours'
        return $this->hasMany(Note::class, 'cours_id');
    }
}
