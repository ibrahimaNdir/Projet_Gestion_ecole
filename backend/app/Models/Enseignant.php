<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enseignant extends Model
{
    use HasFactory;
    protected $table = 'enseignants'; // Assurez-vous que c'est le nom exact de votre table

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'utilisateur_id',      // Clé étrangère vers la table 'users'
        'nom',
        'prenom',
        'date_naissance',      // Colonne optionnelle
        'telephone',           // Colonne optionnelle
        'adresse',             // Colonne optionnelle (si vous l'avez ajoutée dans la migration)
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_naissance' => 'date', // Convertit automatiquement la colonne en objet Carbon (date)
    ];

    // --- Relations ---

    /**
     * Un enseignant appartient à un utilisateur (son compte de connexion).
     * C'est une relation BelongsTo car 'enseignants' a la clé étrangère 'utilisateur_id'.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        // 'utilisateur_id' est la clé étrangère dans la table 'enseignants'
        // 'id' est la clé primaire dans la table 'users' (par défaut, pas besoin de le spécifier)
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    /**
     * Un enseignant peut enseigner plusieurs cours.
     * C'est une relation HasMany car la table 'cours' a une clé étrangère 'enseignant_id' qui pointe vers 'enseignants'.
     *
     * @return HasMany
     */
    public function cours(): HasMany
    {
        // 'Cours::class' est le modèle enfant.
        // 'enseignant_id' est la clé étrangère dans la table 'cours'.
        return $this->hasMany(Cours::class, 'enseignant_id');
    }

    /**
     * Un enseignant peut être affecté à plusieurs matières pour différentes années.
     * C'est une relation HasMany car la table 'enseignant_matiere' a une clé étrangère 'enseignant_id'.
     *
     * @return HasMany
     */
    public function affectationsMatieres(): HasMany
    {
        // 'EnseignantMatiere::class' est le modèle enfant (table pivot).
        // 'enseignant_id' est la clé étrangère dans la table 'enseignant_matiere'.
        return $this->hasMany(EnseignantMatiere::class, 'enseignant_id');
    }
}
