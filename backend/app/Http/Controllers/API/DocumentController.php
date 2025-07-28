<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Models\DocumentJustificatif;

use App\Models\Eleve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Services\DocumentService;

class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }


    public function store(StoreDocumentRequest $request, $eleveId)
    {
        $document = $this->documentService->store($eleveId, $request->file('document'), $request->type_document);

        return response()->json($document, 201);
    }


    public function destroy(DocumentJustificatif $document)
    {
        $this->documentService->delete($document);
        return response()->noContent();
    }
}
