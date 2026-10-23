<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAutoDeclarationRequest;
use App\Http\Requests\StoreDenonciationRequest;
use App\Models\Agent;
use App\Models\Campagne;
use App\Models\Declaration;
use App\Models\DeclarationConflit;
use App\Models\DeclarationHonneur;
use App\Notifications\DeclarationConflitNotification;
use App\Services\DeclarationReferenceGenerator;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Espace collaborateur : déclarations de conflits d'intérêts et dénonciations.
 */
class DeclarationController extends Controller
{
    public function __construct(
        protected DeclarationReferenceGenerator $referenceGenerator
    ) {}

    /* ==================================================================
     |  LISTE — Mes déclarations
     ================================================================== */
    public function index()
    {
        $agent = auth()->user()->agent;

        $declarations = Declaration::with(['conflit'])
            ->where('id_collaborateur', $agent?->id)
            ->orderByDesc('date_soumission')
            ->paginate(15);

        return view('conflits.index', compact('declarations'));
    }

    /* ==================================================================
     |  ÉCRAN 0 — Choix du type
     ================================================================== */
    public function create()
    {

        $campagneActive = Campagne::active()->latest('date_ouverture')->first();
        // dd($campagneActive);

        if (! $campagneActive) {
            // return redirect()
            //     ->route('conflits.index')
            //     ->with('error', "Aucune campagne active. Les déclarations sont fermées.");

            // Récupérer la dernière campagne clôturée pour l'afficher
            $derniereCampagne = Campagne::where('statut', 'CLOTUREE')
                ->latest('date_cloture')
                ->first();

            return view('conformites.conflits-interets.campagne-cloturee', [
                'agentPrenom'     => auth()->user()?->agent?->prenom,
                'campagneLibelle' => $derniereCampagne?->libelle,
                'dateOuverture'   => $derniereCampagne?->date_ouverture,
                'dateCloture'     => $derniereCampagne?->date_cloture,
            ]);
        }

        return view('conflits.create');
    }

    public function createAuto()
    {
        $campagneActive = Campagne::active()->latest('date_ouverture')->first();
        // dd($campagneActive);

        if (! $campagneActive) {
            $derniereCampagne = Campagne::where('statut', 'CLOTUREE')
                ->latest('date_cloture')
                ->first();

            return view('conformites.conflits-interets.campagne-cloturee', [
                'agentPrenom'     => auth()->user()?->agent?->prenom,
                'campagneLibelle' => $derniereCampagne?->libelle,
                'dateOuverture'   => $derniereCampagne?->date_ouverture,
                'dateCloture'     => $derniereCampagne?->date_cloture,
            ]);
        }

        // return view('conformites.conflits-interets.formulaire', compact('campagneActive'));
        return view('conflits.auto.create', compact('campagneActive'));
    }

    /* ==================================================================
     |  FLUX DEN — Formulaire
     ================================================================== */
    public function createDenonciation()
    {

        $campagneActive = Campagne::active()->latest('date_ouverture')->first();
        // dd($campagneActive);

        if (! $campagneActive) {
            $derniereCampagne = Campagne::where('statut', 'CLOTUREE')
                ->latest('date_cloture')
                ->first();

            return view('conformites.conflits-interets.campagne-cloturee', [
                'agentPrenom'     => auth()->user()?->agent?->prenom,
                'campagneLibelle' => $derniereCampagne?->libelle,
                'dateOuverture'   => $derniereCampagne?->date_ouverture,
                'dateCloture'     => $derniereCampagne?->date_cloture,
            ]);
        }

        $agent = auth()->user()->agent;

        $agents = Agent::where('statut', 'actif')
            ->where('id', '!=', $agent?->id)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return view('conflits.denonciation.create', compact('agent', 'agents'));
    }

    /* ==================================================================
     |  FLUX A — AUTO-DÉCLARATION (store)
     ================================================================== */

    public function storeAuto(StoreAutoDeclarationRequest $request)
    {
        $agent = auth()->user()->agent;
        $data  = $request->validated();
        $isOui = $data['reponse_q1'] === 'OUI';

        // ============================================================
        // 1. Récupérer la campagne active
        // ============================================================
        $campagneActive = Campagne::active()->latest('date_ouverture')->first();

        if (! $campagneActive) {
            return back()
                ->withInput()
                ->with('error', "Aucune campagne de déclaration n'est active actuellement. Veuillez contacter les RH.");
        }

        try {
            DB::beginTransaction();

            // ============================================================
            // 2. Vérifier si l'agent a DÉJÀ une déclaration pour cette campagne
            // ============================================================
            $declarationExistante = Declaration::with(['conflit', 'declarationHonneur'])
                ->where('id_collaborateur', $agent?->id)
                ->where('id_campagne', $campagneActive->id)
                ->first();

            // ============================================================
            // CAS A : La déclaration existe → on la MET À JOUR
            // ============================================================
            if ($declarationExistante) {

                $declaration = $declarationExistante;

                // Purge des anciens blocs
                // $declaration->conflit?->delete();
                // $declaration->declarationHonneur?->delete();

                // Mise à jour de la déclaration principale
                $declaration->update([
                    'reponse_q1'          => $data['reponse_q1'],
                    'statut'              => 'MODIFIEE',
                    'date_derniere_modif' => now(),
                    'ip_soumission'       => $request->ip(),
                    'user_agent'          => Str::limit($request->userAgent() ?? '', 500),
                ]);

                // Recréer le bloc adapté
                if ($isOui) {
                    DeclarationConflit::create([
                        'id_declaration'                => $declaration->id,
                        'nature_conflit'                => $data['nature_conflit'],
                        'description_detaillee'         => $data['description_detaillee'],
                        'personne_societe_concernee'    => $data['personne_societe_concernee'] ?? null,
                        'client_fournisseur_partenaire' => $data['client_fournisseur_partenaire'] ?? null,
                        'date_debut_situation'          => $data['date_debut_situation'] ?? null,
                        'influence_decisions'           => $data['influence_decisions'],
                        'mesures_prises'                => $data['mesures_prises'] ?? null,
                        'relation_type'                 => $data['relation_type'] ?? null,
                        'relation_agent_id'             => $data['relation_agent_id'] ?? null,
                        'relation_nom_externe'          => $data['relation_nom_externe'] ?? null,
                        'cadeau_type'                   => $data['cadeau_type'] ?? null,
                        'cadeau_agent_id'               => $data['cadeau_agent_id'] ?? null,
                        'cadeau_nom_externe'            => $data['cadeau_nom_externe'] ?? null,
                    ]);
                } else {
                    DeclarationHonneur::create([
                        'id_declaration'         => $declaration->id,
                        'certification_honneur'  => true,
                        'engagement_signalement' => true,
                        'date_certification'     => now(),
                    ]);
                }

                DB::commit();

                return redirect()
                    ->route('conflits.show', $declaration)
                    ->with('success', "Votre déclaration a été mise à jour. Référence : {$declaration->reference}");
            }

            // ============================================================
            // CAS B : Pas de déclaration → on la CRÉE
            // ============================================================
            $declaration = Declaration::create([
                'uuid'             => (string) Str::uuid(),
                'reference'        => $this->referenceGenerator->generate('DECL'),
                'id_campagne'      => $campagneActive->id,
                'id_collaborateur' => $agent?->id,
                'matricule'        => $agent?->matricule ?? '—',
                'nom_prenoms'      => trim(($agent?->nom ?? '') . ' ' . ($agent?->prenom ?? '')),
                'direction'        => $agent?->affectationActuelle?->direction?->libelle,
                'departement'      => $agent?->affectationActuelle?->departement?->libelle,
                'fonction'         => $agent?->affectationActuelle?->poste?->libelle,
                'email_pro'        => $agent?->contacts?->email_pro,
                'nom_n1'           => $agent?->affectationActuelle?->superviseur
                    ? trim($agent->affectationActuelle->superviseur->nom . ' ' .
                        $agent->affectationActuelle->superviseur->prenom)
                    : null,
                'reponse_q1'       => $data['reponse_q1'],
                'statut'           => 'SOUMISE',
                'date_soumission'  => now(),
                'ip_soumission'    => $request->ip(),
                'user_agent'       => Str::limit($request->userAgent() ?? '', 500),
            ]);

            // Bloc conflit (si OUI)
            if ($isOui) {
                DeclarationConflit::create([
                    'id_declaration'                => $declaration->id,
                    'nature_conflit'                => $data['nature_conflit'],
                    'description_detaillee'         => $data['description_detaillee'],
                    'personne_societe_concernee'    => $data['personne_societe_concernee'] ?? null,
                    'client_fournisseur_partenaire' => $data['client_fournisseur_partenaire'] ?? null,
                    'date_debut_situation'          => $data['date_debut_situation'] ?? null,
                    'influence_decisions'           => $data['influence_decisions'],
                    'mesures_prises'                => $data['mesures_prises'] ?? null,
                    'relation_type'                 => $data['relation_type'] ?? null,
                    'relation_agent_id'             => $data['relation_agent_id'] ?? null,
                    'relation_nom_externe'          => $data['relation_nom_externe'] ?? null,
                    'cadeau_type'                   => $data['cadeau_type'] ?? null,
                    'cadeau_agent_id'               => $data['cadeau_agent_id'] ?? null,
                    'cadeau_nom_externe'            => $data['cadeau_nom_externe'] ?? null,
                ]);
            }

            // Bloc honneur (si NON)
            if (! $isOui) {
                DeclarationHonneur::create([
                    'id_declaration'         => $declaration->id,
                    'certification_honneur'  => true,
                    'engagement_signalement' => true,
                    'date_certification'     => now(),
                ]);
            }

            DB::commit();

            // ============================================================
            // 3. Notifications (uniquement à la création)
            $this->envoyerNotification($declaration, 'soumission');

            return redirect()
                ->route('conflits.show', $declaration)
                ->with('success', "Votre déclaration a été enregistrée. Référence : {$declaration->reference}");
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()->with('error', "Une erreur est survenue : {$e->getMessage()}");
        }
    }

    /* ==================================================================
     |  FLUX B — DÉNONCIATION (store)
     ================================================================== */
    public function storeDenonciation(StoreDenonciationRequest $request)
    {
        $agent = auth()->user()->agent;
        $data  = $request->validated();

        /*Initialisation des variables de cible */
        $agentDenonceId       = null;
        $agentDenonceNom      = null;
        $agentDenonceFonction = null;

        try {
            DB::beginTransaction();

            /*  1. Déclaration principale */
            $declaration = Declaration::create([
                'uuid'             => (string) Str::uuid(),
                'reference'        => $this->referenceGenerator->generate('DEN'),
                'id_campagne'      => null,
                'id_collaborateur' => $agent?->id,
                'matricule'        => $agent?->matricule ?? '—',
                'nom_prenoms'      => trim(($agent?->nom ?? '') . ' ' . ($agent?->prenom ?? '')),
                'direction'        => $agent?->affectationActuelle?->direction?->libelle,
                'departement'      => $agent?->affectationActuelle?->departement?->libelle,
                'fonction'         => $agent?->affectationActuelle?->poste?->libelle,
                'email_pro'        => $agent?->contacts?->email_pro,
                'nom_n1'           => $agent?->affectationActuelle?->superviseur
                    ? trim($agent->affectationActuelle->superviseur->nom . ' ' .
                        $agent->affectationActuelle->superviseur->prenom)
                    : null,
                'reponse_q1'       => 'OUI',
                'statut'           => 'SOUMISE',
                'date_soumission'  => now(),
                'ip_soumission'    => $request->ip(),
                'user_agent'       => Str::limit($request->userAgent() ?? '', 500),
            ]);

            /*  2. Détermination de la cible (agent dénoncé)*/
            $cibleTexte = null;

            // Cas 1 : agent externe (nom + fonction libres)
            if (! empty($data['agent_denonce_nom'])) {
                $agentDenonceNom      = $data['agent_denonce_nom'];
                $agentDenonceFonction = $data['agent_denonce_fonction'] ?? null;

                $cibleTexte = "Personne concernée : {$agentDenonceNom}";
                if (! empty($agentDenonceFonction)) {
                    $cibleTexte .= " ({$agentDenonceFonction})";
                }
            }

            // Cas 2 : agent interne (sélection dans la liste)
            if (! empty($data['agent_denonce_id'])) {
                $agentDenonceId = (int) $data['agent_denonce_id'];

                $agentDenonceInterne = Agent::find($agentDenonceId);

                if ($agentDenonceInterne) {
                    $agentDenonceNom      = trim($agentDenonceInterne->nom . ' ' . $agentDenonceInterne->prenom);
                    $agentDenonceFonction = $agentDenonceInterne->affectationActuelle?->poste?->libelle ?? 'interne';

                    $cibleTexte = "Personne concernée (interne) : {$agentDenonceNom}";
                }
            }

            // Cas 3 : aucune cible précisée
            if (empty($cibleTexte)) {
                $cibleTexte = "Personne concernée : non précisée";
            }

            /*  3. Construction de la description détaillée   |    (on garde un résumé lisible dans description_detaillee) */
            $description = $data['faits_constates'] ?? '';

            $prefixes = [];

            if (! empty($data['date_faits'])) {
                $prefixes[] = "Date des faits : {$data['date_faits']}";
            }
            if (! empty($data['lieu_faits'])) {
                $prefixes[] = "Lieu des faits : {$data['lieu_faits']}";
            }

            // $description = implode("\n", $prefixes) . "\n\n" . $cibleTexte . "\n\n" . $description;
            $description = implode("\n", $prefixes) . "\n\n" . $description;

            if (! empty($data['impact_estime'])) {
                $description .= "\n\nImpact estimé : {$data['impact_estime']}";
            }
            if (! empty($data['niveau_urgence'])) {
                $description .= "\nNiveau d'urgence : {$data['niveau_urgence']}";
            }
            if (! empty($data['temoins'])) {
                $description .= "\nTémoins : " . implode(', ', $data['temoins']);
            }
            if (! empty($data['deja_signale'])) {
                $description .= "\nDéjà signalé à : " . ($data['signale_a'] ?? '—');
            }

            /*  4. Détail du conflit  */
            DeclarationConflit::create([
                'id_declaration'         => $declaration->id,
                'nature_conflit'         => $data['nature_conflit'],
                'description_detaillee'  => $description,
                'influence_decisions'    => 'OUI',

                /* ---------- Champs dénonciation ---------- */
                'agent_denonce_id'       => $agentDenonceId,
                'agent_denonce_nom'      => $agentDenonceNom,
                'agent_denonce_fonction' => $agentDenonceFonction,

                /* ---------- Champs spécifiques DEN ---------- */
                'niveau_urgence'         => $data['niveau_urgence'] ?? null,
                'date_faits'             => $data['date_faits'] ?? null,
                'lieu_faits'             => $data['lieu_faits'] ?? null,
                'impact_estime'          => $data['impact_estime'] ?? null,
                'deja_signale'           => ! empty($data['deja_signale']) ? 1 : 0,
                'signale_a'              => $data['signale_a'] ?? null,
                'temoins'                => $data['temoins'] ?? null,
                'protection_denonciateur' => ! empty($data['protection_denonciateur']) ? 1 : 0,
                'souhaite_anonyme'       => ! empty($data['souhaite_anonyme']) ? 1 : 0,
            ]);

            /*  5. Fichiers joints (à activer si tu ajoutes la table dédiée)  */
            // if ($request->hasFile('fichiers')) {
            //     foreach ($request->file('fichiers') as $file) {
            //         $safeName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            //         $path     = $file->storeAs("declarations/{$declaration->id}", $safeName, 'local');
            //         Log::info("Fichier joint pour déclaration #{$declaration->id}", [
            //             'original' => $file->getClientOriginalName(),
            //             'path'     => $path,
            //             'size'     => $file->getSize(),
            //         ]);
            //     }
            // }

            DB::commit();

            $this->envoyerNotification($declaration, 'denonciation');

            return redirect()
                ->route('conflits.show', $declaration)
                ->with('success', "Votre dénonciation a été transmise. Référence : {$declaration->reference}");
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->withInput()
                ->with('error', "Une erreur est survenue : {$e->getMessage()}");
        }
    }

    /* ==================================================================
     |  DÉTAIL
     ================================================================== */
    public function show(Declaration $declaration)
    {
        $this->authorizeAccess($declaration);
        // $declaration->load(['conflit', 'declarationHonneur']);
        // Dans le contrôleur show()
        $declaration->load([
            'conflit.relationAgent',
            'conflit.cadeauAgent',
            'conflit.agentDenonce',
            'declarationHonneur',
        ]);

        // dd($declaration);
        return view('conflits.show', compact('declaration'));
    }

    /* ==================================================================
     |  ÉDITION
     ================================================================== */
    public function edit(Declaration $declaration)
    {

        $campagneActive = Campagne::active()->latest('date_ouverture')->first();
        // dd($campagneActive);

        if (! $campagneActive) {
            $derniereCampagne = Campagne::where('statut', 'CLOTUREE')
                ->latest('date_cloture')
                ->first();

            return view('conformites.conflits-interets.campagne-cloturee', [
                'agentPrenom'     => auth()->user()?->agent?->prenom,
                'campagneLibelle' => $derniereCampagne?->libelle,
                'dateOuverture'   => $derniereCampagne?->date_ouverture,
                'dateCloture'     => $derniereCampagne?->date_cloture,
            ]);
        }

        $this->authorizeAccess($declaration);

        // 1. Détermination du type UNIQUEMENT depuis la référence
        $reftype = $declaration->reference
            ? strtoupper(explode('-', $declaration->reference)[0] ?? '')
            : '';

        // 2. Une dénonciation n'est jamais modifiable
        if ($reftype === 'DEN') {
            return redirect()
                ->route('conflits.show', $declaration)
                ->with('error', "Une dénonciation ne peut pas être modifiée.");
        }

        // 3. Statut éditable ?
        if (! in_array($declaration->statut, ['SOUMISE', 'MODIFIEE'])) {
            return redirect()
                ->route('conflits.show', $declaration)
                ->with('error', 'Cette déclaration ne peut plus être modifiée.');
        }

        // 4. Préparation des données pour la vue
        $agent  = auth()->user()->agent;
        $agents = Agent::where('statut', 'actif')
            ->where('id', '!=', $agent?->id)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        $declaration->load(['conflit', 'declarationHonneur']);

        return view('conflits.edit', compact('declaration', 'agent', 'agents'));
    }

    /* ==================================================================
     |  MISE À JOUR
     ================================================================== */
    public function update(StoreAutoDeclarationRequest $request, Declaration $declaration)
    {
        // $this->authorizeAccess($declaration);

        // if (! in_array($declaration->statut, ['SOUMISE', 'MODIFIEE'])) {
        //     return back()->with('error', 'Cette déclaration ne peut plus être modifiée.');
        // }
        // if (($declaration->type ?? 'AUTO') === 'DENONCIATION') {
        //     return back()->with('error', "Une dénonciation ne peut pas être modifiée.");
        // }

        $this->authorizeAccess($declaration);

        // 1. Type depuis la référence
        $reftype = $declaration->reference
            ? strtoupper(explode('-', $declaration->reference)[0] ?? '')
            : '';

        // 2. Une dénonciation n'est jamais modifiable
        if ($reftype === 'DEN') {
            return redirect()
                ->route('conflits.show', $declaration)
                ->with('error', "Une dénonciation ne peut pas être modifiée.");
        }

        // 3. Statut éditable ?
        if (! in_array($declaration->statut, ['SOUMISE', 'MODIFIEE'])) {
            return redirect()
                ->route('conflits.show', $declaration)
                ->with('error', 'Cette déclaration ne peut plus être modifiée.');
        }

        $data  = $request->validated();
        $isOui = $data['reponse_q1'] === 'OUI';

        // dd(__FUNCTION__);

        try {
            DB::beginTransaction();

            $declaration->update([
                'reponse_q1'          => $data['reponse_q1'],
                'statut'              => 'MODIFIEE',
                'date_derniere_modif' => now(),
            ]);

            if ($isOui) {
                $declaration->conflit()->updateOrCreate(
                    ['id_declaration' => $declaration->id],
                    [
                        'nature_conflit'                => $data['nature_conflit'],
                        'description_detaillee'         => $data['description_detaillee'],
                        'personne_societe_concernee'    => $data['personne_societe_concernee'] ?? null,
                        'client_fournisseur_partenaire' => $data['client_fournisseur_partenaire'] ?? null,
                        'date_debut_situation'          => $data['date_debut_situation'] ?? null,
                        'influence_decisions'           => $data['influence_decisions'],
                        'mesures_prises'                => $data['mesures_prises'] ?? null,
                        'relation_type'                 => $data['relation_type'] ?? null,
                        'relation_agent_id'             => $data['relation_agent_id'] ?? null,
                        'relation_nom_externe'          => $data['relation_nom_externe'] ?? null,
                        'cadeau_type'                   => $data['cadeau_type'] ?? null,
                        'cadeau_agent_id'               => $data['cadeau_agent_id'] ?? null,
                        'cadeau_nom_externe'            => $data['cadeau_nom_externe'] ?? null,
                    ]
                );
                $declaration->declarationHonneur()->delete();
            } else {
                $declaration->conflit()->delete();
                $declaration->declarationHonneur()->updateOrCreate(
                    ['id_declaration' => $declaration->id],
                    [
                        'certification_honneur'  => true,
                        'engagement_signalement' => true,
                        'date_certification'     => now(),
                    ]
                );
            }

            DB::commit();

            $this->envoyerNotification($declaration->fresh(), 'modification');

            return redirect()->route('conflits.show', $declaration)
                ->with('success', 'Votre déclaration a été mise à jour.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->with('error', "Erreur : {$e->getMessage()}");
        }
    }

    /* ==================================================================
     |  ACCUSÉ PDF
     ================================================================== */
    public function declarationPdf(Declaration $declaration)
    {
        $this->authorizeAccess($declaration);

        $declaration->load([
            'conflit.relationAgent',
            'conflit.cadeauAgent',
            'conflit.agentDenonce',
            'declarationHonneur',
        ]);

        $pdf = Pdf::loadView('conflits.pdf.declaration', compact('declaration'))
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'defaultFont'            => 'DejaVu Sans',
                'isHtml5ParserEnabled'   => true,
                'isRemoteEnabled'        => false,
                'isFontSubsettingEnabled' => true,
                'dpi'                    => 150,
            ]);

        return $pdf->download("declaration-{$declaration->reference}.pdf");
    }

    /**
     * Notifications d'une déclaration (applicatives + email).
     *
     *  - soumission   : accusé au déclarant ; Conformité alertée si conflit déclaré (OUI)
     *  - modification : Conformité alertée si conflit déclaré (OUI)
     *  - denonciation : accusé au lanceur d'alerte ; Conformité alertée (anonymat respecté)
     *
     * Ne bloque jamais l'enregistrement : les erreurs sont journalisées.
     */
    private function envoyerNotification(Declaration $declaration, string $contexte = 'soumission'): void
    {
        try {
            $service = app(NotificationService::class);
            $declaration->loadMissing(['conflit', 'collaborateur.user']);

            $declarant = $declaration->collaborateur ?? $declaration->email_pro;

            // Conformité : équipe habilitée, à défaut équipe RH.
            $conformite = $service->equipeConformite();
            if ($conformite->isEmpty()) {
                $conformite = $service->destinatairesRh();
            }
            $exclure = array_filter([auth()->id()]);

            switch ($contexte) {
                case 'denonciation':
                    $service->envoyer($declarant, new DeclarationConflitNotification($declaration, 'denonciation_declarant'));
                    $service->envoyer($conformite, new DeclarationConflitNotification($declaration, 'denonciation_conformite'), $exclure);
                    break;

                case 'modification':
                    if ($declaration->est_positive) {
                        $service->envoyer($conformite, new DeclarationConflitNotification($declaration, 'modifiee_conformite'), $exclure);
                    }
                    break;

                default: // soumission
                    $service->envoyer($declarant, new DeclarationConflitNotification($declaration, 'soumise_declarant'));
                    if ($declaration->est_positive) {
                        $service->envoyer($conformite, new DeclarationConflitNotification($declaration, 'soumise_conformite'), $exclure);
                    }
            }
        } catch (\Throwable $e) {
            Log::warning('Échec notification déclaration conflit', [
                'declaration_id' => $declaration->id,
                'contexte'       => $contexte,
                'erreur'         => $e->getMessage(),
            ]);
        }
    }

    /* ==================================================================
     |  AUTORISATION D'ACCÈS
     ================================================================== */
    protected function authorizeAccess(Declaration $declaration): void
    {
        $user  = auth()->user();
        $agent = $user?->agent;

        abort_unless($user, 403, 'Utilisateur non authentifié.');

        // 1. Propriétaire
        if ($agent && (int) $declaration->id_collaborateur === (int) $agent->id) {
            return;
        }

        // 2. Back-office Conformité (rôles + matricules habilités)
        if ($user->can('administrer-conformite')) {
            return;
        }

        // 3. Une dénonciation n'est JAMAIS visible par la hiérarchie
        //    (protection du lanceur d'alerte) : propriétaire + Conformité uniquement.
        if (str_starts_with((string) $declaration->reference, 'DEN-')) {
            abort(403, 'Accès non autorisé.');
        }

        // 4. N+1 du déclarant (auto-déclarations uniquement)
        if ($agent && $this->isN1Of($agent, $declaration)) {
            return;
        }

        abort(403, 'Accès non autorisé à cette déclaration.');
    }

    protected function isN1Of(Agent $agent, Declaration $declaration): bool
    {
        $declarant = Agent::find($declaration->id_collaborateur);
        if (! $declarant) return false;

        $affectation = $declarant->affectations()->where('statut', 'active')->first();
        return $affectation && (int) $affectation->superviseur_id === (int) $agent->id;
    }

    public function accusePdf(Declaration $declaration)
    {
        $this->authorizeAccess($declaration);

        $declaration->load([
            'conflit.relationAgent',
            'conflit.cadeauAgent',
            'conflit.agentDenonce',
            'declarationHonneur',
        ]);

        $pdf = Pdf::loadView('conflits.pdf.accuse', compact('declaration'))
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'defaultFont'      => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'  => false,
                'dpi'              => 150,
            ]);

        return $pdf->download("accuse-reception-{$declaration->reference}.pdf");
    }
}
