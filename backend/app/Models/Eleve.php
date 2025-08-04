<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Eleve extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'date_naissance',
        'lieu_naissance',
        'sexe',
        'adresse',
        'telephone',
        'email',
        'photo',
        'statut',
        'date_inscription',
        'utilisateur_id'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_inscription' => 'date',
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

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'eleveclasse', 'eleve_id', 'classe_id')
            ->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($eleve) {
            if (empty($eleve->matricule)) {
                $eleve->matricule = self::generateMatricule();
            }
        });
    }

    private static function generateMatricule()
    {
        // Exemple de matricule : "ELEVE" + année en 4 chiffres + un numéro aléatoire unique
        $year = date('Y');
        $random = mt_rand(1000, 9999);

        // Tu peux aussi vérifier en base que ce matricule n'existe pas pour garantir l'unicité
        $matricule = "ELEVE{$year}{$random}";

        while (self::where('matricule', $matricule)->exists()) {
            $random = mt_rand(1000, 9999);
            $matricule = "ELEVE{$year}{$random}";
        }

        return $matricule;
    }
}
