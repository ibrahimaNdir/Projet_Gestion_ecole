<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    use HasFactory;
    protected $table = 'matieres';

    // Champs qui peuvent être remplis massivement
    protected $fillable = [
        'nom_matiere',

    ];
}
