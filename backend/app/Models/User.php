<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // Relation One-to-Many avec Role (un utilisateur a un rôle)
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'roles_id');
    }

    // --- Nouvelle relation pour les parents d'élèves ---
    /**
     * Un utilisateur (ayant le rôle de parent) peut avoir plusieurs élèves.
     *
     * @return BelongsToMany
     */
    public function elevesEnfants(): BelongsToMany
    {
        // 'Eleve::class' est le modèle que tu veux atteindre.
        // 'lien_parent_eleve' est le nom de ta table pivot.
        // 'parent_utilisateur_id' est la clé étrangère du modèle User (moi-même) dans la table pivot.
        // 'eleve_id' est la clé étrangère du modèle Eleve dans la table pivot.
        return $this->belongsToMany(Eleve::class, 'lien_parent_eleve', 'parent_utilisateur_id', 'eleve_id')
            ->using(LienParentEleve::class); // Spécifie le modèle pivot si tu en as créé un
        // ->withPivot('type_lien') si tu as des colonnes supplémentaires dans la pivot
        // ->withTimestamps(); // Si tu veux accéder aux created_at/updated_at de la pivot
    }
}
