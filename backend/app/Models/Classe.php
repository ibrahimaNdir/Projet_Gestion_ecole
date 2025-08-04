<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;
    protected $table = 'classes';

    protected $fillable = [
        'nom',
        'niveau',
        'capacite',
        'description',
        'statut'
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    public function eleves()
    {
        return $this->belongsToMany(Eleve::class, 'eleveclasse', 'classe_id', 'eleve_id')
            ->withTimestamps();
    }

    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'enseignant_classe', 'classe_id', 'enseignant_id')
            ->withTimestamps();
    }

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'matiere_classe_coefficients', 'classe_id', 'matiere_id')
            ->withPivot('coefficient')
            ->withTimestamps();
    }
}
