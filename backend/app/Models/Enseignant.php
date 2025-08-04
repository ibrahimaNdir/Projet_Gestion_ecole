<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'lieu_naissance',
        'sexe',
        'adresse',
        'telephone',
        'email',
        'photo',
        'specialite',
        'diplome',
        'date_embauche',
        'statut',
        'utilisateur_id'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_embauche' => 'date',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'enseignantmatiere', 'enseignant_id', 'matiere_id')
            ->withTimestamps();
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'enseignant_classe', 'enseignant_id', 'classe_id')
            ->withTimestamps();
    }
}
