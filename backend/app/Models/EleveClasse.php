<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EleveClasse extends Model
{
    use HasFactory;
    protected $table = 'eleve_classe'; // Nom de la table de liaison

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'eleve_id',
        'classe_id',
        'annee_academique_id',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_affectation' => 'date', // Convertit automatiquement la colonne en objet Carbon (date)
    ];

    // --- Relations ---

    /**
     * Une affectation Élève-Classe appartient à un élève.
     *
     * @return BelongsTo
     */
    public function eleve(): BelongsTo
    {
        // 'eleve_id' est la clé étrangère dans la table 'eleve_classe'
        // 'id' est la clé primaire dans la table 'eleves'
        return $this->belongsTo(Eleve::class, 'eleve_id');
    }

    /**
     * Une affectation Élève-Classe appartient à une classe.
     *
     * @return BelongsTo
     */
    public function classe(): BelongsTo
    {
        // 'classe_id' est la clé étrangère dans la table 'eleve_classe'
        // 'id' est la clé primaire dans la table 'classes'
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * Une affectation Élève-Classe est associée à une année académique spécifique.
     *
     * @return BelongsTo
     */
    public function anneeAcademique(): BelongsTo
    {
        // 'annee_academique_id' est la clé étrangère dans la table 'eleve_classe'
        // 'id' est la clé primaire dans la table 'annees_academiques'
        return $this->belongsTo(AnneeAcademique::class, 'annee_academique_id');
    }

}
