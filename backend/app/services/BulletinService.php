<?php

namespace App\services;

use App\Models\Bulletins;
use App\Models\MatiereCoefClasse;
use App\Models\Notes;
use Illuminate\Support\Facades\View;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Collection;
use App\Models\Eleve;
use App\Models\AnneeAcademique;
use App\Models\PeriodeEvaluation;

class BulletinService
{
    protected AnneeAcademiqueService $anneeAcademiqueService;
    protected NotesService $noteService;
    protected MatiereCoefClasseService $matiereClasseCoefficientService;

    public function __construct(
        AnneeAcademiqueService $anneeAcademiqueService,
        NotesService $noteService,
        MatiereCoefClasseService $matiereClasseCoefficientService
    ) {
        $this->anneeAcademiqueService = $anneeAcademiqueService;
        $this->noteService = $noteService;
        $this->matiereClasseCoefficientService = $matiereClasseCoefficientService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Bulletins::with(['eleve', 'anneeAcademique', 'periodeEvaluation'])->get();
    }

    /**
     * Crée un nouveau bulletin.
     *
     * @param array $data Les données validées pour la création.
     * @return Bulletins L'instance du bulletin créée.
     * @throws Exception Si la création échoue.
     */
    public function store(array $data): Bulletins
    {
        try {
            return Bulletins::firstOrCreate(
                [
                    'eleve_id' => $data['eleve_id'],
                    'annee_academique_id' => $data['annee_academique_id'],
                    'periode_evaluation_id' => $data['periode_evaluation_id'],
                ],
                [
                    'date_generation' => $data['date_generation'] ?? now(),
                    'url_bulletin_pdf' => $data['url_bulletin_pdf'] ?? null,
                    'moyenne_generale' => $data['moyenne_generale'] ?? null,
                    'mention' => $data['mention'] ?? null,
                    'rang_classe' => $data['rang_classe'] ?? null,
                    'appreciation_generale' => $data['appreciation_generale'] ?? null,
                ]
            );
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la création du bulletin : " . $e->getMessage());
        }
    }

    /**
     * Met à jour un bulletin existant.
     *
     * @param int $id L'ID du bulletin à mettre à jour.
     * @param array $data Les données validées pour la mise à jour.
     * @return Bulletins|null Le bulletin mis à jour ou null si non trouvé.
     * @throws Exception Si la mise à jour échoue.
     */
    public function update(int $id, array $data): ?Bulletins
    {
        try {
            $bulletin = Bulletins::find($id);
            if ($bulletin) {
                $bulletin->update($data);
                $bulletin->load(['eleve', 'anneeAcademique', 'periodeEvaluation']);
            }
            return $bulletin;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la mise à jour du bulletin : " . $e->getMessage());
        }
    }

    /**
     * Supprime un bulletin.
     *
     * @param int $id L'ID du bulletin à supprimer.
     * @return bool Vrai si le bulletin a été supprimé, faux sinon.
     */
    public function destroy(int $id): bool
    {
        return (bool) Bulletins::destroy($id);
    }

    /**
     * Récupère tous les bulletins d'un élève pour une année académique donnée.
     *
     * @param int $eleveId L'ID de l'élève.
     * @param int $anneeAcademiqueId L'ID de l'année académique.
     * @return Collection<int, Bulletins>
     */
    public function getBulletinsByEleveAndAnnee(int $eleveId, int $anneeAcademiqueId): Collection
    {
        return Bulletins::where('eleve_id', $eleveId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->with(['periodeEvaluation'])
            ->get();
    }

    /**
     * Récupère tous les bulletins d'un élève pour une période d'évaluation donnée.
     *
     * @param int $eleveId L'ID de l'élève.
     * @param int $periodeEvaluationId L'ID de la période d'évaluation.
     * @return Collection<int, Bulletins>
     */
    public function getBulletinsByEleveAndPeriode(int $eleveId, int $periodeEvaluationId): Collection
    {
        return Bulletins::where('eleve_id', $eleveId)
            ->where('periode_evaluation_id', $periodeEvaluationId)
            ->with(['anneeAcademique'])
            ->get();
    }

    /**
     * Récupère un bulletin spécifique par élève, année académique et période d'évaluation.
     *
     * @param int $eleveId L'ID de l'élève.
     * @param int $anneeAcademiqueId L'ID de l'année académique.
     * @param int $periodeEvaluationId L'ID de la période d'évaluation.
     * @return Bulletins|null
     */
    public function getBulletinByEleveAnneePeriode(int $eleveId, int $anneeAcademiqueId, int $periodeEvaluationId): ?Bulletins
    {
        return Bulletins::where('eleve_id', $eleveId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->where('periode_evaluation_id', $periodeEvaluationId)
            ->with(['eleve', 'anneeAcademique', 'periodeEvaluation'])
            ->first();
    }

    /**
     * Génère un bulletin pour un élève, une année et une période spécifiques.
     *
     * @param int $eleveId L'ID de l'élève.
     * @param int $anneeAcademiqueId L'ID de l'année académique.
     * @param int $periodeEvaluationId L'ID de la période d'évaluation.
     * @return Bulletins
     * @throws Exception Si la génération/mise à jour échoue.
     */
    public function generateBulletin(int $eleveId, int $anneeAcademiqueId, int $periodeEvaluationId): Bulletins
    {
        try {
            // 1. Récupérer les données de base
            $eleve = Eleve::with('classe')->findOrFail($eleveId);
            $annee = AnneeAcademique::findOrFail($anneeAcademiqueId);
            $periode = PeriodeEvaluation::findOrFail($periodeEvaluationId);

            // 2. Préparer les données pour le bulletin (notes par matière, moyenne, etc.)
            $bulletinData = $this->getNotesDetailsPourBulletin($eleveId, $anneeAcademiqueId, $periodeEvaluationId);

            // 3. Calculer la moyenne générale et la mention
            $moyenneGenerale = $bulletinData['moyenneGenerale'];
            $mention = $this->determineMention($moyenneGenerale);

            // 4. Rendre la vue Blade en HTML
            $html = View::make('bulletins.bulletin', [
                'eleve' => $eleve,
                'annee' => $annee,
                'periode' => $periode,
                'matieres' => $bulletinData['matieres'], // On passe les données préparées
                'moyenneGenerale' => $moyenneGenerale,
                'mention' => $mention
            ])->render();

            // 5. Initialiser Dompdf
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');

            // 6. Générer le PDF
            $dompdf->render();

            // 7. Sauvegarder le fichier PDF
            $fileName = "bulletin_{$eleveId}_{$anneeAcademiqueId}_{$periodeEvaluationId}.pdf";
            $pdfContent = $dompdf->output();
            Storage::disk('public')->put("bulletins/{$fileName}", $pdfContent);
            $url_bulletin_pdf = Storage::disk('public')->url("bulletins/{$fileName}");

            // 8. Créer ou mettre à jour le bulletin dans la base de données AVEC toutes les données
            $bulletin = Bulletins::updateOrCreate(
                [
                    'eleve_id' => $eleveId,
                    'annee_academique_id' => $anneeAcademiqueId,
                    'periode_evaluation_id' => $periodeEvaluationId,
                ],
                [
                    'date_generation' => now(),
                    'moyenne_generale' => $moyenneGenerale,
                    'mention' => $mention,
                    'url_bulletin_pdf' => $url_bulletin_pdf,
                    'rang_classe' => null, // Logique à ajouter si nécessaire
                    'appreciation_generale' => null, // Logique à ajouter si nécessaire
                ]
            );

            // Important : retourne le bulletin mis à jour, il est maintenant complet
            return $bulletin->load(['eleve', 'anneeAcademique', 'periodeEvaluation']);

        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la génération du bulletin : " . $e->getMessage());
        }
    }

    /**
     * Récupère et prépare les notes par matière et la moyenne générale pour un bulletin.
     *
     * @param int $eleveId L'ID de l'élève.
     * @param int $anneeAcademiqueId L'ID de l'année académique.
     * @param int $periodeEvaluationId L'ID de la période d'évaluation.
     * @return array
     */
    protected function getNotesDetailsPourBulletin(int $eleveId, int $anneeAcademiqueId, int $periodeEvaluationId): array
    {
        $notes = Notes::with(['cours.matiere', 'cours.classe'])
            ->where('eleve_id', $eleveId)
            ->where('periode_evaluation_id', $periodeEvaluationId)
            ->get();

        $matieres = collect([]);
        $totalPointsPonderes = 0;
        $totalCoefficients = 0;

        // Groupement des notes par matière
        $notesByMatiere = $notes->groupBy('cours.matiere.id');

        foreach ($notesByMatiere as $matiereId => $notesCollection) {
            $matiere = $notesCollection->first()->cours->matiere;
            $classeId = $notesCollection->first()->cours->classe_id;

            $moyenneMatiere = $notesCollection->avg('valeur_note');
            $coefficient = $this->matiereClasseCoefficientService->getCoefficient(
                $matiereId,
                $classeId
            );

            if ($coefficient === null) {
                // Gérer le cas où le coefficient n'est pas défini
                $coefficient = 1; // Ou lancer une exception
            }

            $notePonderee = $moyenneMatiere * $coefficient;

            $totalPointsPonderes += $notePonderee;
            $totalCoefficients += $coefficient;

            $matieres->push([
                'nom' => $matiere->nom_matiere,
                'moyenne' => round($moyenneMatiere, 2),
                'coefficient' => $coefficient,
                'note_ponderee' => round($notePonderee, 2),
            ]);
        }

        $moyenneGenerale = $totalCoefficients > 0 ? round((float) ($totalPointsPonderes / $totalCoefficients), 2) : 0.0;

        return [
            'matieres' => $matieres,
            'moyenneGenerale' => $moyenneGenerale,
        ];
    }


    /**
     * Détermine la mention en fonction de la moyenne générale.
     *
     * @param float $moyenne La moyenne générale.
     * @return string
     */
    protected function determineMention(float $moyenne): string
    {
        if ($moyenne >= 18) {
            return 'Excellent';
        } elseif ($moyenne >= 16) {
            return 'Très Bien';
        } elseif ($moyenne >= 14) {
            return 'Bien';
        } elseif ($moyenne >= 12) {
            return 'Assez Bien';
        } elseif ($moyenne >= 10) {
            return 'Passable';
        } else {
            return 'Insuffisant';
        }
    }
}
