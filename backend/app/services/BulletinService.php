<?php

namespace App\services;

use App\Models\Bulletins;
use App\Models\MatiereCoefClasse;
use App\Models\Notes;

class BulletinService
{
    protected AnneeAcademiqueService $anneeAcademiqueService;
    protected NotesService $noteService; // Pour récupérer les notes
    protected MatiereCoefClasseService $matiereClasseCoefficientService; // Pour les coefficients

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
     *
     */
    public function index()
    {
        // Charge les relations eleve, anneeAcademique et periodeEvaluation
        return Bulletins::with(['eleve', 'anneeAcademique', 'periodeEvaluation'])->get();

    }

    /**
     * Crée un nouveau bulletin.
     *
     * @param array $data Les données validées pour la création.
     * @return Bulletin L'instance du bulletin créée.
     * @throws Exception Si la création échoue.
     */
    public function store(array $data): Bulletin
    {
        try {
            // Utilise firstOrCreate pour éviter les doublons basés sur la contrainte d'unicité composite.
            // Si le bulletin existe déjà pour cet élève, cette année et cette période, il est retourné.
            // Sinon, un nouveau bulletin est créé.
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

     */
    public function show(int $id)
    {
        MatiereCoefClasse::destroy($id);
        return true;
    }

    /**
     * Met à jour un bulletin existant.
     *
     * @param int $id L'ID du bulletin à mettre à jour.
     * @param array $data Les données validées pour la mise à jour.
     * @return Bulletin|null Le bulletin mis à jour ou null si non trouvé.
     * @throws Exception Si la mise à jour échoue.
     */
    public function update(int $id, array $data): ?Bulletins
    {
        try {
            $bulletin = Bulletins::find($id);

            if ($bulletin) {
                $bulletin->update($data);
                $bulletin->load(['eleve', 'anneeAcademique', 'periodeEvaluation']); // Recharger les relations
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
     * Récupère tous les bulletins d'un élève pour une année académique donnée (pour consultation historique).
     *
     * @param int $eleveId L'ID de l'élève.
     * @param int $anneeAcademiqueId L'ID de l'année académique.
     * @return Collection<int, Bulletin>
     */
    public function getBulletinsByEleveAndAnnee(int $eleveId, int $anneeAcademiqueId): Collection
    {
        return Bulletins::where('eleve_id', $eleveId)
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->with(['periodeEvaluation']) // Charger la période pour le contexte
            ->get();
    }

    /**
     * Récupère tous les bulletins d'un élève pour une période d'évaluation donnée (pour consultation historique).
     *
     * @param int $eleveId L'ID de l'élève.
     * @param int $periodeEvaluationId L'ID de la période d'évaluation.
     * @return Collection<int, Bulletin>
     */
    public function getBulletinsByEleveAndPeriode(int $eleveId, int $periodeEvaluationId): Collection
    {
        // Pour cette méthode, l'année académique est implicitement liée à la période d'évaluation.
        // Si la période d'évaluation est toujours unique par année, pas besoin de la passer explicitement.
        return Bulletins::where('eleve_id', $eleveId)
            ->where('periode_evaluation_id', $periodeEvaluationId)
            ->with(['anneeAcademique']) // Charger l'année académique pour le contexte
            ->get();
    }

    /**
     * Récupère un bulletin spécifique par élève, année académique et période d'évaluation (pour consultation historique).
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
     * Cette méthode est un placeholder. Dans une application réelle, elle inclurait :
     * 1. La récupération de toutes les notes de l'élève pour cette période/année.
     * 2. Le calcul des moyennes par matière et de la moyenne générale.
     * 3. La détermination de la mention et du rang.
     * 4. La génération d'un fichier PDF (par ex. avec des librairies comme Dompdf ou Snappy).
     * 5. Le stockage du PDF et la mise à jour du Bulletin en base de données avec l'URL et les moyennes/mentions.
     *
     * Pour cet exemple, elle se contente de créer/mettre à jour le bulletin avec les données fournies.
     *
     * @param int $eleveId L'ID de l'élève.
     * @param int $anneeAcademiqueId L'ID de l'année académique. // Gardé pour la flexibilité de l'admin
     * @param int $periodeEvaluationId L'ID de la période d'évaluation.
     * @param array $data Données optionnelles pour le bulletin (moyenne, mention, etc.).
     * @return Bulletin
     * @throws Exception Si la génération/mise à jour échoue.
     */
    public function generateBulletin(int $eleveId, int $anneeAcademiqueId, int $periodeEvaluationId, array $data = []): Bulletin
    {
        try {
            // Dans un scénario réel, vous calculeriez ici la moyenne, mention, rang, etc.
            // Pour l'exemple, nous utilisons les données passées ou des valeurs par défaut.
            $moyenneGenerale = $data['moyenne_generale'] ?? $this->calculateMoyenneGenerale($eleveId, $anneeAcademiqueId, $periodeEvaluationId);
            $mention = $data['mention'] ?? $this->determineMention($moyenneGenerale);
            $rangClasse = $data['rang_classe'] ?? null; // Le calcul du rang est plus complexe et dépend de la classe de l'élève

            // Crée ou met à jour le bulletin
            $bulletin =Bulletins::updateOrCreate(
                [
                    'eleve_id' => $eleveId,
                    'annee_academique_id' => $anneeAcademiqueId,
                    'periode_evaluation_id' => $periodeEvaluationId,
                ],
                [
                    'date_generation' => now(), // Date de génération actuelle
                    'moyenne_generale' => $moyenneGenerale,
                    'mention' => $mention,
                    'rang_classe' => $rangClasse,
                    'appreciation_generale' => $data['appreciation_generale'] ?? null,
                    // 'url_bulletin_pdf' => 'path/to/generated_bulletin.pdf', // Mettre à jour après la génération réelle du PDF
                ]
            );

            // Ici, vous appelleriez la logique de génération de PDF et mettriez à jour 'url_bulletin_pdf'
            // Exemple: $pdfPath = $this->generatePdf($bulletin);
            // $bulletin->update(['url_bulletin_pdf' => $pdfPath]);

            return $bulletin->load(['eleve', 'anneeAcademique', 'periodeEvaluation']);

        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la génération du bulletin : " . $e->getMessage());
        }
    }

    /**
     * Calcule la moyenne générale pondérée d'un élève pour une période d'évaluation.
     * Cette méthode est mise à jour pour utiliser les coefficients des matières.
     *
     * @param int $eleveId L'ID de l'élève.
     * @param int $anneeAcademiqueId L'ID de l'année académique.
     * @param int $periodeEvaluationId L'ID de la période d'évaluation.
     * @return float
     */
    protected function calculateMoyenneGenerale(int $eleveId, int $anneeAcademiqueId, int $periodeEvaluationId): float
    {
        // Récupérer toutes les notes de l'élève pour la période et l'année spécifiées
        $notes = $this->noteService->getNotesByEleveAndPeriode($eleveId, $periodeEvaluationId);

        $totalPointsPonderes = 0;
        $totalCoefficients = 0;
        $matieresProcessed = []; // Pour s'assurer de ne traiter qu'une fois la moyenne par matière

        foreach ($notes as $note) {
            $matiereId = $note->cours->matiere_id;
            $classeId = $note->cours->classe_id; // L'élève est dans cette classe pour ce cours

            // Si la matière n'a pas encore été traitée pour cette période/classe
            if (!isset($matieresProcessed[$matiereId])) {
                // Récupérer toutes les notes de l'élève pour cette matière et cette période
                // (via le cours de cette matière)
                $notesMatiere = Notes::where('eleve_id', $eleveId)
                    ->where('periode_evaluation_id', $periodeEvaluationId)
                    ->whereHas('cours', function ($query) use ($matiereId, $anneeAcademiqueId) {
                        $query->where('matiere_id', $matiereId)
                            ->where('annee_academique_id', $anneeAcademiqueId);
                    })
                    ->get();

                if ($notesMatiere->isNotEmpty()) {
                    $moyenneMatiere = $notesMatiere->avg('valeur_note');

                    // Récupérer le coefficient pour cette matière et cette classe
                    // L'année académique n'est PLUS un critère pour le coefficient lui-même.
                    $coefficient = $this->matiereClasseCoefficientService->getCoefficient(
                        $matiereId,
                        $classeId
                    );

                    // Si aucun coefficient n'est trouvé, on peut choisir de :
                    // 1. Lancer une exception (recommandé pour s'assurer que tous les coefficients sont définis)
                    // 2. Utiliser un coefficient par défaut (ex: 1)
                    if ($coefficient === null) {
                        // Pour l'instant, lançons une exception pour forcer la configuration
                        throw new Exception("Coefficient non défini pour la matière ID {$matiereId} dans la classe ID {$classeId}.");
                        // Ou: $coefficient = 1; // Utiliser un défaut
                    }

                    $totalPointsPonderes += ($moyenneMatiere * $coefficient);
                    $totalCoefficients += $coefficient;
                    $matieresProcessed[$matiereId] = true; // Marquer la matière comme traitée
                }
            }
        }

        if ($totalCoefficients === 0) {
            return 0.0; // Éviter la division par zéro si l'élève n'a pas de notes ou de coefficients définis.
        }

        return round((float) ($totalPointsPonderes / $totalCoefficients), 2); // Arrondir à 2 décimales
    }

    /**
     * Détermine la mention en fonction de la moyenne générale.
     * Exemple de logique simple.
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
