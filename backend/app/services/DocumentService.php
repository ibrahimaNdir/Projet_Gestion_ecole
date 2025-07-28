<?php

namespace App\Services;

use App\Models\DocumentJustificatif;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function store(int $eleveId, UploadedFile $file, string $type): DocumentJustificatif
    {
        $path = $file->store('documents');

        return DocumentJustificatif::create([
            'eleve_id'       => $eleveId,
            'nom_fichier'    => $file->getClientOriginalName(),
            'chemin_stockage'=> $path,
            'type_document'  => $type,
            'date_upload'    => now(),
        ]);
    }

    public function delete(DocumentJustificatif $document): void
    {
        Storage::delete($document->chemin_stockage);
        $document->delete();
    }
}
