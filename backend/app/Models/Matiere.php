<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    use HasFactory;
    protected $table = 'matieres';

    protected $fillable = [
        'nom',
        'code',
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

    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'enseignantmatiere', 'matiere_id', 'enseignant_id')
            ->withTimestamps();
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'matiere_classe_coefficients', 'matiere_id', 'classe_id')
            ->withPivot('coefficient')
            ->withTimestamps();
    }
}
