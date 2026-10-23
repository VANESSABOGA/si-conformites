<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Declaration;
use App\Models\DeclarationConflit;
use App\Models\DeclarationHistorique;
use App\Notifications\DeclarationConflitNotification;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Back-office Conformité : examen, validation et rejet des déclarations.
 */
class DeclarationAdminController extends Controller
{

    public function index(Request $request)
    {
        /* ============================================================
     | Requête de base — toutes les déclarations (OUI + NON)
     ============================================================ */
        $query = Declaration::query()
            ->with(['collaborateur', 'conflit', 'campagne'])
            ->orderByDesc('date_soumission');

        /* ============================================================
     | Filtres
     ============================================================ */
        if ($request->filled('q')) {
            $search = trim((string) $request->input('q'));
            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('reference', 'like', "%{$search}%")
                        ->orWhere('matricule', 'like', "%{$search}%")
                        ->orWhere('nom_prenoms', 'like', "%{$search}%");
                });
            }
        }

        if ($request->filled('reponse_q1')) {
            $query->where('reponse_q1', $request->input('reponse_q1'));
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }
        if ($request->filled('direction')) {
            $query->where('direction', $request->input('direction'));
        }
        if ($request->filled('nature')) {
            $nature = $request->input('nature');
            $query->whereHas('conflit', fn($q) => $q->whereJsonContains('nature_conflit', $nature));
        }
        if ($request->filled('du')) {
            $query->whereDate('date_soumission', '>=', $request->input('du'));
        }
        if ($request->filled('au')) {
            $query->whereDate('date_soumission', '<=', $request->input('au'));
        }

        // ⚠️ Pas de paginate() → DataTables gère côté client
        $declarations = $query->get();

        /* ============================================================
     | Base pour les KPI (filtres partiels)
     ============================================================ */
        $base = Declaration::query();
        if ($request->filled('direction')) $base->where('direction', $request->input('direction'));
        if ($request->filled('du'))        $base->whereDate('date_soumission', '>=', $request->input('du'));
        if ($request->filled('au'))        $base->whereDate('date_soumission', '<=', $request->input('au'));

        /* ============================================================
     | KPI globaux
     ============================================================ */
        $kpis = [
            'total'     => (clone $base)->count(),
            'soumises'  => (clone $base)->where('statut', 'SOUMISE')->count(),
            'modifiees' => (clone $base)->where('statut', 'MODIFIEE')->count(),
            'validees'  => (clone $base)->where('statut', 'VALIDEE')->count(),
            'rejetees'  => (clone $base)->where('statut', 'REJETEE')->count(),
        ];

        /* ============================================================
     | KPI par réponse Q1
     ============================================================ */
        $kpisParReponse = [
            [
                'key'   => 'OUI',
                'label' => 'Conflits déclarés',
                'total' => (clone $base)->where('reponse_q1', 'OUI')->count(),
                'icon'  => 'bx-error-circle',
            ],
            [
                'key'   => 'NON',
                'label' => 'Aucun conflit',
                'total' => (clone $base)->where('reponse_q1', 'NON')->count(),
                'icon'  => 'bx-check-circle',
            ],
        ];

        /* ============================================================
     | KPI par nature de conflit (parmi les OUI)
     ============================================================ */
        $labels = [
            'PARTICIPATION_FINANCIERE' => ['label' => 'Participation financière', 'icon' => 'bx-coins'],
            'LIEN_FAMILIAL'            => ['label' => 'Lien familial',            'icon' => 'bx-group'],
            'ACTIVITE_EXTERNE'         => ['label' => 'Activité externe',         'icon' => 'bx-briefcase'],
            'FONCTION_ADMINISTRATEUR'  => ['label' => 'Fonction administrateur',  'icon' => 'bx-crown'],
            'RELATION_PERSONNELLE'     => ['label' => 'Relation personnelle',     'icon' => 'bx-user-voice'],
            'CADEAUX_AVANTAGES'        => ['label' => 'Cadeaux / avantages',      'icon' => 'bx-gift'],
            'AUTRE'                    => ['label' => 'Autre',                    'icon' => 'bx-dots-horizontal-rounded'],
        ];

        $idsOui = (clone $base)->where('reponse_q1', 'OUI')->pluck('id');
        $toutesLesNatures = DeclarationConflit::whereIn('id_declaration', $idsOui)->pluck('nature_conflit');

        $compteur = array_fill_keys(array_keys($labels), 0);
        foreach ($toutesLesNatures as $naturesBrutes) {
            $natures = is_array($naturesBrutes)
                ? $naturesBrutes
                : (json_decode((string) $naturesBrutes, true) ?? []);
            foreach ($natures as $n) {
                if (isset($compteur[$n])) $compteur[$n]++;
            }
        }
        arsort($compteur);

        $kpisParNature = collect($compteur)
            ->map(fn($total, $key) => [
                'key'   => $key,
                'label' => $labels[$key]['label'],
                'icon'  => $labels[$key]['icon'],
                'total' => $total,
            ])
            ->values();

        // Filtre par type (AUTO / DEN)
        if ($request->filled('type')) {
            $type = $request->input('type');
            if ($type === 'DEN') {
                $query->where('reference', 'LIKE', 'DEN-%');
            } elseif ($type === 'AUTO') {
                $query->where('reference', 'LIKE', 'DECL-%');
            }
        }

        // ...

        $kpisParType = [
            [
                'key'   => 'AUTO',
                'label' => 'Auto-déclarations',
                'icon'  => 'bx-user-check',
                'total' => (clone $base)->where('reference', 'LIKE', 'DECL-%')->count(),
            ],
            [
                'key'   => 'DEN',
                'label' => 'Dénonciations',
                'icon'  => 'bx-error-alt',
                'total' => (clone $base)->where('reference', 'LIKE', 'DEN-%')->count(),
            ],
        ];

        /* ============================================================
     | Liste des directions
     ============================================================ */
        $directions = Declaration::query()
            ->whereNotNull('direction')
            ->distinct()
            ->orderBy('direction')
            ->pluck('direction');

        return view('conformites.conflits-interets.admins.index', [
            'declarations'   => $declarations,
            'kpis'           => $kpis,
            'kpisParReponse' => $kpisParReponse,
            'kpisParNature'  => $kpisParNature,
            'directions'     => $directions,
            'kpisParType'  => $kpisParType,
            // 'filters'        => $request->only(['q', 'statut', 'direction', 'du', 'au', 'nature', 'reponse_q1']),
            // Et dans le return view :
            'filters'      => $request->only(['q', 'reponse_q1', 'type', 'statut', 'direction', 'du', 'au', 'nature']),

        ]);
    }

    /**
     * Détail d'une déclaration positive.
     */
    public function show(Request $request, Declaration $declaration)
    {
        // ⚠️ SUPPRIMER cette ligne (ou la commenter) :
        // abort_unless($declaration->est_positive, 404);

        $declaration->load([
            'collaborateur.affectationActuelle',
            'conflit',
            'declarationHonneur',
            'consentement',
            'historiques.user',
            'valideePar',
            'rejeteePar',
            'campagne',
        ]);

        if ($request->expectsJson()) {
            return response()->json($declaration);
        }

        return view('conformites.conflits-interets.admins.show', [
            'declaration' => $declaration,
        ]);
    }

    /**
     * Valider une déclaration.
     */
    public function valider(Request $request, Declaration $declaration)
    {
        abort_unless($declaration->est_positive, 404);
        abort_unless($declaration->statut === 'SOUMISE', 409, 'Déclaration déjà traitée.');

        DB::transaction(function () use ($declaration) {
            $declaration->update([
                'statut'          => 'VALIDEE',
                'date_validation' => now(),
                'validee_par'     => auth()->id(),
                'rejetee_par'     => null,
                'date_rejet'      => null,
                'motif_rejet'     => null,
            ]);

            DeclarationHistorique::create([
                'id_declaration' => $declaration->id,
                'user_id'        => auth()->id(),
                'action'         => 'VALIDATION',
                'commentaire'    => 'Déclaration validée par un administrateur habilité.',
            ]);
        });

        $this->notifierDeclarant($declaration, 'validee');

        if ($request->expectsJson()) {
            return response()->json([
                'message'     => 'Déclaration validée avec succès.',
                'declaration' => $declaration->fresh(),
            ]);
        }
        // dd($declaration);

        return redirect()
            ->route('admin.conflits.show', $declaration)
            ->with('success', 'Déclaration validée avec succès.');
    }

    /**
     * Rejeter une déclaration.
     */
    public function rejeter(Request $request, Declaration $declaration)
    {
        abort_unless($declaration->est_positive, 404);
        abort_unless($declaration->statut === 'SOUMISE', 409, 'Déclaration déjà traitée.');

        $motif = $request->motif_rejet;

        DB::transaction(function () use ($declaration, $motif) {
            $declaration->update([
                'statut'          => 'REJETEE',
                'date_rejet'      => now(),
                'rejetee_par'     => auth()->id(),
                'motif_rejet'     => $motif,
                'validee_par'     => null,
                'date_validation' => null,
            ]);

            DeclarationHistorique::create([
                'id_declaration' => $declaration->id,
                'user_id'        => auth()->id(),
                'action'         => 'REJET',
                'commentaire'    => $motif,
            ]);
        });

        $this->notifierDeclarant($declaration, 'rejetee');

        if ($request->expectsJson()) {
            return response()->json([
                'message'     => 'Déclaration rejetée.',
                'declaration' => $declaration->fresh(),
            ]);
        }

        return redirect()
            ->route('admin.conflits.show', $declaration)
            ->with('success', 'Déclaration rejetée.');
    }

    public function exportPdf(Request $request)
    {
        // 1. Requête de base
        $query = Declaration::query()
            ->with(['campagne', 'conflit'])
            ->positives()
            ->orderByDesc('date_soumission');

        // 2. Filtres (identiques à l'index)
        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('reference', 'like', "%{$q}%")
                    ->orWhere('matricule', 'like', "%{$q}%")
                    ->orWhere('nom_prenoms', 'like', "%{$q}%");
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }

        if ($request->filled('direction')) {
            $query->where('direction', $request->input('direction'));
        }

        if ($request->filled('type')) {
            // AUTO ou DEN selon ton champ
        }

        if ($request->filled('du')) {
            $query->whereDate('date_soumission', '>=', $request->input('du'));
        }

        if ($request->filled('au')) {
            $query->whereDate('date_soumission', '<=', $request->input('au'));
        }

        $declarations = $query->get();

        // 3. Génération du PDF
        $pdf = Pdf::loadView('conformites.conflits-interets.admins.exportpdf', [
            'declarations' => $declarations,
            'filtres'      => $request->only(['q', 'statut', 'direction', 'type', 'du', 'au']),
        ])->setPaper('A4', 'landscape');

        // 4. Nom de fichier avec date
        $filename = 'conformites_declaration_' . now()->format('Y_m_d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Informe le déclarant de la décision (applicatif + email).
     */
    private function notifierDeclarant(Declaration $declaration, string $evenement): void
    {
        $declaration->refresh()->loadMissing(['collaborateur.user', 'conflit']);

        app(NotificationService::class)->envoyer(
            $declaration->collaborateur ?? $declaration->email_pro,
            new DeclarationConflitNotification($declaration, $evenement)
        );
    }
}
