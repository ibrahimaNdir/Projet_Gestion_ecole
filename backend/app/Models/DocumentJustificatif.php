<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentJustificatif extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'nom_fichier',
        'chemin_stockage',
        'type_document',
        'chemin_fichier',
        'date_upload',

    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }
}
