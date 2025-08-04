<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Enseignant;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats()
    {
        $stats = [
            'eleves' => Eleve::count(),
            'enseignants' => Enseignant::count(),
            'classes' => Classe::count(),
            'matieres' => Matiere::count(),
        ];

        return response()->json($stats);
    }
}