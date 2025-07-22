<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LienParentEleve extends Model
{
    protected $table = 'lien_parent_eleve';

    protected $fillable = [
        'parent_utilisateur_id',
        'eleve_id',
        'type_relation'
    ];
}
