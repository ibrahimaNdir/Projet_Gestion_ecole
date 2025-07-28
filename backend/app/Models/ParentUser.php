<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'nom',
        'prenom',
        'email',
        'telephone'
    ];
    protected $visible = [
        'id',
        'utilisateur_id',
        'nom',
        'prenom',
        'email',
        'telephone',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function eleves()
    {
        return $this->belongsToMany(Eleve::class, 'lien_parent_eleve', 'parent_utilisateur_id', 'eleve_id')
            ->withPivot('type_relation')
            ->withTimestamps();
    }
}
