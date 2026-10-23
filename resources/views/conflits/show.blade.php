@extends('layouts.main')

@section('content')

    @include('conflits.partials._styles')

    @php
        use Carbon\Carbon;

        /* ================================================================
     | HELPERS
     ================================================================ */
        $formatDate = fn($d) => $d ? Carbon::parse($d)->format('d/m/Y') : '—';
        $formatDateTime = fn($d) => $d ? Carbon::parse($d)->format('d/m/Y à H:i') : '—';

        /* ================================================================
     | TYPE (via référence)
     ================================================================ */
        $reftype = $declaration->reference ? strtoupper(explode('-', $declaration->reference)[0] ?? '') : '';

        $isDen = $reftype === 'DEN';
        $isAuto = !$isDen;
        $isOui = $declaration->reponse_q1 === 'OUI';
        $heroClass = $isDen ? 'danger' : '';

        /* ================================================================
     | NATURES
     ================================================================ */
        $naturesLabels = [
            'PARTICIPATION_FINANCIERE' => 'Participation financière',
            'LIEN_FAMILIAL' => 'Lien familial',
            'ACTIVITE_EXTERNE' => 'Activité externe',
            'FONCTION_ADMINISTRATEUR' => "Fonction d'administrateur",
            'RELATION_PERSONNELLE' => 'Relation personnelle',
            'CADEAUX_AVANTAGES' => 'Cadeaux / avantages',
            'AUTRE' => 'Autre',
        ];

        $conflit = $declaration->conflit;
        $honneur = $declaration->declarationHonneur;

        $natures = [];
        if ($conflit?->nature_conflit) {
            $natures = is_string($conflit->nature_conflit)
                ? json_decode($conflit->nature_conflit, true) ?? []
                : $conflit->nature_conflit;
        }
        $natures = is_array($natures) ? $natures : [];

        /* ================================================================
     | AGENTS LIÉS
     ================================================================ */
        $relationAgent = $conflit?->relationAgent;
        $cadeauAgent = $conflit?->cadeauAgent;
        $agentDenonce = $conflit?->agentDenonce;

        /* ================================================================
     | CHAMPS DÉNONCIATION
     ================================================================ */
        $denonceNom = $conflit?->agent_denonce_nom;
        $denonceFonction = $conflit?->agent_denonce_fonction;
        $niveauUrgence = $conflit?->niveau_urgence ?? null;
        $dateFaits = $conflit?->date_faits ?? null;
        $lieuFaits = $conflit?->lieu_faits ?? null;
        $impactEstime = $conflit?->impact_estime ?? null;
        $dejaSignale = $conflit?->deja_signale ?? false;
        $signaleA = $conflit?->signale_a ?? null;
        $protectionDen = $conflit?->protection_denonciateur ?? false;
        $souhaiteAnonyme = $conflit?->souhaite_anonyme ?? false;

        $temoins = $conflit?->temoins ?? [];
        if (is_string($temoins)) {
            $temoins = json_decode($temoins, true) ?? [];
        }
        $temoins = is_array($temoins) ? $temoins : [];

        /* ================================================================
     | BADGES
     ================================================================ */
        $urgenceClass =
            [
                'FAIBLE' => 'ci-badge-ok',
                'MOYEN' => 'ci-badge-info',
                'ELEVE' => 'ci-badge-warn',
                'CRITIQUE' => 'ci-badge-ko',
            ][$niveauUrgence ?? 'MOYEN'] ?? 'ci-badge-info';

        $statutMap = [
            'SOUMISE' => ['label' => 'Soumise', 'icon' => 'bx-send', 'badge' => 'ci-badge-info'],
            'MODIFIEE' => ['label' => 'Modifiée', 'icon' => 'bx-edit', 'badge' => 'ci-badge-warn'],
            'VALIDEE' => ['label' => 'Validée', 'icon' => 'bx-check-double', 'badge' => 'ci-badge-ok'],
            'REJETEE' => ['label' => 'Rejetée', 'icon' => 'bx-x', 'badge' => 'ci-badge-ko'],
        ];
        $statut = $statutMap[$declaration->statut] ?? [
            'label' => $declaration->statut,
            'icon' => 'bx-info-circle',
            'badge' => 'ci-badge-info',
        ];
    @endphp

    <div class="page-content">

        {{-- ================================================================
         BREADCRUMB
    ================================================================ --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
            <div class="breadcrumb-title pe-3" style="color:var(--rh-primary);font-weight:700;">CONFORMITÉ</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}" class="text-decoration-none" style="color: var(--rh-primary);">
                                <i class="bx bx-home-alt fs-6"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('conflits.index') }}" class="text-decoration-none"
                                style="color: var(--rh-primary);">
                                Mes déclarations
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-muted" style="font-size: .85rem;">
                            {{ $declaration->reference }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- ================================================================
         HERO
    ================================================================ --}}
        <div class="ci-hero {{ $heroClass }} mb-4">
            <div
                class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                <div>
                    <h1>
                        <i class="bx {{ $isDen ? 'bx-error-alt' : 'bx-check-shield' }}"></i>
                        {{ $isDen ? "Dénonciation d'un agent" : "Ma déclaration de conflit d'intérêts" }}
                    </h1>
                    <p>
                        Référence : <strong>{{ $declaration->reference ?? 'DECL-' . $declaration->id }}</strong> —
                        Soumise le {{ $formatDateTime($declaration->date_soumission) }}
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="ci-badge {{ $isDen ? 'ci-badge-ko' : 'ci-badge-info' }}">
                        <i class="bx {{ $isDen ? 'bx-error' : 'bx-user-check' }}"></i>
                        {{ $isDen ? 'Dénonciation' : 'Auto-déclaration' }}
                    </span>
                    <span class="ci-badge {{ $statut['badge'] }}">
                        <i class="bx {{ $statut['icon'] }}"></i> {{ $statut['label'] }}
                    </span>
                    @if ($isOui)
                        <span class="ci-badge ci-badge-ko">
                            <i class="bx bx-error-circle"></i> Conflit déclaré
                        </span>
                    @else
                        <span class="ci-badge ci-badge-ok">
                            <i class="bx bx-check-circle"></i> Aucun conflit
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ================================================================
         MESSAGES
    ================================================================ --}}
        @if (session('success'))
            <div class="alert alert-success mb-4 border-0 rounded-3">
                <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mb-4 border-0 rounded-3">
                <i class="bx bx-error-circle me-1"></i> {{ session('error') }}
            </div>
        @endif

        <div class="row g-4">

            {{-- ============================================================
             COLONNE GAUCHE — CONTENU PRINCIPAL
        ============================================================ --}}
            <div class="col-lg-8">

                {{-- ================================================================
                 1. IDENTIFICATION
            ================================================================ --}}
                <div class="ci-card">
                    <div class="ci-card-header">
                        <h6>
                            <i class="bx bx-user-circle"></i>
                            {{ $isDen ? 'Dénonciateur' : 'Collaborateur' }}
                        </h6>
                    </div>
                    <div class="ci-card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Nom et Prénoms</span>
                                    <span class="ci-info-value">{{ $declaration->nom_prenoms }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Matricule</span>
                                    <span class="ci-info-value font-monospace">{{ $declaration->matricule }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Fonction</span>
                                    <span class="ci-info-value">{{ $declaration->fonction ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Direction</span>
                                    <span class="ci-info-value">{{ $declaration->direction ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Département</span>
                                    <span class="ci-info-value">{{ $declaration->departement ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="ci-info-row">
                                    <span class="ci-info-label">E-mail professionnel</span>
                                    <span class="ci-info-value">{{ $declaration->email_pro ?? '—' }}</span>
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="ci-info-row">
                                    <span class="ci-info-label">N+1</span>
                                    <span class="ci-info-value">{{ $declaration->nom_n1 ?? '—' }}</span>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                {{-- ================================================================
                 2. BLOC DÉNONCIATION (uniquement si DEN)
            ================================================================ --}}
                @if ($isDen)
                    <div class="ci-card">
                        <div class="ci-card-header">
                            <h6><i class="bx bx-user-x"></i> Agent concerné par la dénonciation</h6>
                            @if ($niveauUrgence)
                                <span class="ci-badge {{ $urgenceClass }}">
                                    <i class="bx bx-error"></i> {{ $niveauUrgence }}
                                </span>
                            @endif
                        </div>
                        <div class="ci-card-body">

                            {{-- Cible --}}
                            <div class="ci-info-row">
                                <span class="ci-info-label">Agent dénoncé</span>
                                <span class="ci-info-value">
                                    @if ($agentDenonce)
                                        <strong>{{ $agentDenonce->nom }} {{ $agentDenonce->prenom }}</strong>
                                        <span class="text-muted" style="font-weight:400;">—
                                            {{ $agentDenonce->matricule }}</span>
                                    @elseif ($denonceNom)
                                        <strong>{{ $denonceNom }}</strong>
                                        @if ($denonceFonction)
                                            <span class="text-muted" style="font-weight:400;">—
                                                {{ $denonceFonction }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </span>
                            </div>

                            {{-- Contexte des faits --}}
                            @if ($dateFaits || $lieuFaits)
                                <div class="row g-3 mt-2">
                                    @if ($dateFaits)
                                        <div class="col-md-6">
                                            <div class="ci-info-row">
                                                <span class="ci-info-label">Date des faits</span>
                                                <span class="ci-info-value">{{ $formatDate($dateFaits) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($lieuFaits)
                                        <div class="col-md-6">
                                            <div class="ci-info-row">
                                                <span class="ci-info-label">Lieu des faits</span>
                                                <span class="ci-info-value">{{ $lieuFaits }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Déjà signalé --}}
                            @if ($dejaSignale)
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Déjà signalé</span>
                                    <span class="ci-info-value">
                                        <span class="ci-badge ci-badge-warn">Oui</span>
                                        @if ($signaleA)
                                            <span class="text-muted" style="font-weight:400;">— {{ $signaleA }}</span>
                                        @endif
                                    </span>
                                </div>
                            @endif

                            {{-- Témoins --}}
                            @if (count($temoins) > 0)
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Témoins</span>
                                    <span class="ci-info-value">
                                        @foreach ($temoins as $t)
                                            <span class="ci-tag">{{ $t }}</span>
                                        @endforeach
                                    </span>
                                </div>
                            @endif

                            {{-- Impact estimé --}}
                            @if ($impactEstime)
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Impact estimé</span>
                                    <span class="ci-info-value" style="text-align:left; white-space:pre-line;">
                                        {{ $impactEstime }}
                                    </span>
                                </div>
                            @endif

                        </div>
                    </div>
                @endif

                {{-- ================================================================
                 3. CONTENU DU CONFLIT
            ================================================================ --}}
                @if ($isOui && $conflit)
                    <div class="ci-card">
                        <div class="ci-card-header">
                            <h6>
                                <i class="bx bx-help-circle"></i>
                                {{ $isDen ? 'Faits constatés' : 'Conflit déclaré' }}
                            </h6>
                        </div>
                        <div class="ci-card-body">

                            {{-- Natures --}}
                            <div class="mb-3">
                                <span class="ci-info-label d-block mb-2">Nature(s) du conflit</span>
                                @forelse ($natures as $n)
                                    <span class="ci-tag" style="font-size:.78rem; padding:.3rem .7rem;">
                                        {{ $naturesLabels[$n] ?? $n }}
                                    </span>
                                @empty
                                    <span class="text-muted">—</span>
                                @endforelse
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <span class="ci-info-label d-block mb-2">
                                    {{ $isDen ? 'Description des faits' : 'Description' }}
                                </span>
                                <div class="ci-honneur" style="font-style:normal;">
                                    <div style="white-space:pre-line;">{{ $conflit->description_detaillee }}</div>
                                </div>
                            </div>

                            {{-- Champs complémentaires --}}
                            <div class="row g-3">
                                @if ($conflit->personne_societe_concernee)
                                    <div class="col-md-6">
                                        <div class="ci-info-row">
                                            <span class="ci-info-label">Société concernée</span>
                                            <span class="ci-info-value">{{ $conflit->personne_societe_concernee }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if ($conflit->client_fournisseur_partenaire)
                                    <div class="col-md-6">
                                        <div class="ci-info-row">
                                            <span class="ci-info-label">Client / fournisseur</span>
                                            <span
                                                class="ci-info-value">{{ $conflit->client_fournisseur_partenaire }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if ($conflit->relation_type)
                                    <div class="col-md-6">
                                        <div class="ci-info-row">
                                            <span class="ci-info-label">Relation personnelle</span>
                                            <span class="ci-info-value">
                                                <span
                                                    class="ci-badge {{ $conflit->relation_type === 'INTERNE' ? 'ci-badge-info' : 'ci-badge-warn' }}">
                                                    {{ $conflit->relation_type === 'INTERNE' ? 'Interne' : 'Externe' }}
                                                </span>
                                                @if ($conflit->relation_type === 'INTERNE' && $relationAgent)
                                                    — {{ $relationAgent->nom }} {{ $relationAgent->prenom }}
                                                @elseif ($conflit->relation_nom_externe)
                                                    — {{ $conflit->relation_nom_externe }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                @if ($conflit->cadeau_type)
                                    <div class="col-md-6">
                                        <div class="ci-info-row">
                                            <span class="ci-info-label">Cadeaux / avantages</span>
                                            <span class="ci-info-value">
                                                <span
                                                    class="ci-badge {{ $conflit->cadeau_type === 'INTERNE' ? 'ci-badge-info' : 'ci-badge-warn' }}">
                                                    {{ $conflit->cadeau_type === 'INTERNE' ? 'Interne' : 'Externe' }}
                                                </span>
                                                @if ($conflit->cadeau_type === 'INTERNE' && $cadeauAgent)
                                                    — {{ $cadeauAgent->nom }} {{ $cadeauAgent->prenom }}
                                                @elseif ($conflit->cadeau_nom_externe)
                                                    — {{ $conflit->cadeau_nom_externe }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                @if ($conflit->date_debut_situation)
                                    <div class="col-md-6">
                                        <div class="ci-info-row">
                                            <span class="ci-info-label">Début de la situation</span>
                                            <span
                                                class="ci-info-value">{{ $formatDate($conflit->date_debut_situation) }}</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-6">
                                    <div class="ci-info-row">
                                        <span class="ci-info-label">Influence sur les décisions</span>
                                        <span class="ci-info-value">
                                            @if ($conflit->influence_decisions === 'OUI')
                                                <span class="ci-badge ci-badge-ko">Oui</span>
                                            @else
                                                <span class="ci-badge ci-badge-ok">Non</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                @if ($conflit->mesures_prises)
                                    <div class="col-12">
                                        <div class="ci-info-row">
                                            <span class="ci-info-label">Mesures déjà prises</span>
                                            <span class="ci-info-value" style="text-align:left; white-space:pre-line;">
                                                {{ $conflit->mesures_prises }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ================================================================
                 4. DÉCLARATION SUR L'HONNEUR (uniquement si NON)
            ================================================================ --}}
                @if (!$isOui && $honneur)
                    <div class="ci-card">
                        <div class="ci-card-header">
                            <h6><i class="bx bx-check-shield"></i> Déclaration sur l'honneur</h6>
                        </div>
                        <div class="ci-card-body">
                            <div class="ci-honneur">
                                <strong>Engagement du déclarant</strong>
                                Je certifie sur l'honneur ne pas être en situation de conflit d'intérêts réel,
                                potentiel ou apparent à la date de la présente déclaration et m'engage à signaler
                                toute situation future pouvant constituer un conflit d'intérêts.
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <div class="ci-info-row">
                                        <span class="ci-info-label">Certification</span>
                                        <span class="ci-info-value">
                                            @if ($honneur->certification_honneur)
                                                <span class="ci-badge ci-badge-ok"><i class="bx bx-check"></i>
                                                    Signée</span>
                                            @else
                                                <span class="ci-badge ci-badge-ko"><i class="bx bx-x"></i> Non
                                                    signée</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="ci-info-row">
                                        <span class="ci-info-label">Engagement signalement</span>
                                        <span class="ci-info-value">
                                            @if ($honneur->engagement_signalement)
                                                <span class="ci-badge ci-badge-ok"><i class="bx bx-check"></i>
                                                    Signé</span>
                                            @else
                                                <span class="ci-badge ci-badge-ko"><i class="bx bx-x"></i> Non
                                                    signé</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="ci-info-row">
                                        <span class="ci-info-label">Date certification</span>
                                        <span
                                            class="ci-info-value">{{ $formatDateTime($honneur->date_certification) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- ============================================================
             COLONNE DROITE — MÉTADONNÉES + ACTIONS
        ============================================================ --}}
            <div class="col-lg-4">

                {{-- MÉTADONNÉES --}}
                <div class="ci-card">
                    <div class="ci-card-header">
                        <h6><i class="bx bx-info-circle"></i> Informations</h6>
                    </div>
                    <div class="ci-card-body">
                        <div class="ci-info-row">
                            <span class="ci-info-label">Référence</span>
                            <span class="ci-info-value font-monospace">
                                {{ $declaration->reference ?? 'DECL-' . $declaration->id }}
                            </span>
                        </div>
                        <div class="ci-info-row">
                            <span class="ci-info-label">Type</span>
                            <span class="ci-info-value">
                                <span class="ci-badge {{ $isDen ? 'ci-badge-ko' : 'ci-badge-info' }}">
                                    {{ $isDen ? 'Dénonciation' : 'Auto-déclaration' }}
                                </span>
                            </span>
                        </div>
                        <div class="ci-info-row">
                            <span class="ci-info-label">Statut</span>
                            <span class="ci-info-value">
                                <span class="ci-badge {{ $statut['badge'] }}">
                                    <i class="bx {{ $statut['icon'] }}"></i> {{ $statut['label'] }}
                                </span>
                            </span>
                        </div>
                        <div class="ci-info-row">
                            <span class="ci-info-label">Soumission</span>
                            <span class="ci-info-value">{{ $formatDateTime($declaration->date_soumission) }}</span>
                        </div>
                        @if ($declaration->date_derniere_modif)
                            <div class="ci-info-row">
                                <span class="ci-info-label">Modification</span>
                                <span
                                    class="ci-info-value">{{ $formatDateTime($declaration->date_derniere_modif) }}</span>
                            </div>
                        @endif
                        @if ($declaration->date_validation)
                            <div class="ci-info-row">
                                <span class="ci-info-label">Validation</span>
                                <span class="ci-info-value">{{ $formatDateTime($declaration->date_validation) }}</span>
                            </div>
                        @endif
                        @if ($declaration->date_rejet)
                            <div class="ci-info-row">
                                <span class="ci-info-label">Rejet</span>
                                <span class="ci-info-value">{{ $formatDateTime($declaration->date_rejet) }}</span>
                            </div>
                            @if ($declaration->motif_rejet)
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Motif</span>
                                    <span class="ci-info-value" style="color:var(--rh-danger); text-align:left;">
                                        {{ $declaration->motif_rejet }}
                                    </span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- PROTECTION / ANONYMAT (DEN) --}}
                @if ($isDen && ($protectionDen || $souhaiteAnonyme))
                    <div class="ci-card">
                        <div class="ci-card-header">
                            <h6><i class="bx bx-shield-quarter"></i> Protection</h6>
                        </div>
                        <div class="ci-card-body">
                            @if ($souhaiteAnonyme)
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Anonymat</span>
                                    <span class="ci-info-value">
                                        <span class="ci-badge ci-badge-warn"><i class="bx bx-hide"></i> Demandé</span>
                                    </span>
                                </div>
                            @endif
                            @if ($protectionDen)
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Lanceur d'alerte</span>
                                    <span class="ci-info-value">
                                        <span class="ci-badge ci-badge-ok"><i class="bx bx-shield-quarter"></i>
                                            Demandée</span>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- ACTIONS --}}
                <div class="ci-card">
                    <div class="ci-card-header">
                        <h6><i class="bx bx-cog"></i> Actions</h6>
                    </div>
                    <div class="ci-card-body">
                        <div class="d-grid gap-2">
                            {{-- <a href="{{ route('conflits.accuse.pdf', $declaration) }}"
                                class="ci-btn ci-btn-gold text-center">
                                <i class="bx bx-download me-1"></i> Accusé de réception
                            </a> --}}
                            {{-- <a href="{{ route('conflits.accuse.declarationPdf', $declaration) }}"
                                class="ci-btn ci-btn-primary text-center">
                                <i class="bx bx-file me-1"></i> Déclaration intégrale (PDF)
                            </a> --}}

                            @if ($isAuto && in_array($declaration->statut, ['SOUMISE', 'MODIFIEE']))
                                <a href="{{ route('conflits.edit', $declaration) }}"
                                    class="ci-btn ci-btn-primary text-center">
                                    <i class="bx bx-edit me-1"></i> Modifier
                                </a>
                            @endif

                            <a href="{{ route('conflits.index') }}" class="ci-btn ci-btn-outline text-center">
                                <i class="bx bx-list-ul me-1"></i> Mes déclarations
                            </a>

                            <a href="{{ route('conflits.create') }}" class="ci-btn ci-btn-outline text-center">
                                <i class="bx bx-plus me-1"></i> Nouvelle déclaration
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection
