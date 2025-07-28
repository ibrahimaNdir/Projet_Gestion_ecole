<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Enseignant extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'nom',
        'prenom',
        'telephone',
        'specialite'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
