<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'nom',
        'prenom',
        'date_naissance',
        'adresse',
        'numero_matricule'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'lien_parent_eleve', 'eleve_id', 'parent_utilisateur_id')
            ->withPivot('type_relation')
            ->withTimestamps();
    }

    public function documents()
    {
        return $this->hasMany(DocumentJustificatif::class);
    }
}
