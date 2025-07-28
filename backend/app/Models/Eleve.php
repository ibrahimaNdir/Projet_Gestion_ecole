<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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
