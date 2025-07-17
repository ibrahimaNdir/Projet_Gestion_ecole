<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Models\DocumentJustificatif;
use App\Models\Eleve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(StoreDocumentRequest $request, Eleve $eleve)
    {
        $file = $request->file('document');

        $path = $file->store("eleves/{$eleve->id}/documents", 'public');

        $document = $eleve->documents()->create([
            'nom_fichier' => $file->getClientOriginalName(),
            'chemin_stockage' => $path,
            'type_document' => $request->input('type_document'),
            'date_upload' => now(),
            'taille_fichier' => $file->getSize() / 1024,
        ]);

        return response()->json($document, 201);
    }

    public function destroy(DocumentJustificatif $document)
    {
        Storage::disk('public')->delete($document->chemin_stockage);
        $document->delete();

        return response()->noContent();
    }
}
