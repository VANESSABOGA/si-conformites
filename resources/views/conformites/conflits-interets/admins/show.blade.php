@extends('layouts.main')

@section('content')

    <style>
        :root {
            --rh-primary: #033f1f;
            --rh-primary-dark: #012411;
            --rh-primary-light: rgba(3, 63, 31, 0.06);
            --rh-gold: #F9B233;
            --rh-gold-hover: #e09d20;
            --rh-gold-light: rgba(249, 178, 51, 0.12);
            --rh-danger: #dc3545;

            --rh-success: #198754;
            --rh-info: #0d6efd;
            --rh-border: #e5e9ef;
            --rh-muted: #64748b;
            --rh-radius: 14px;
        }

        .ci-page-title {
            color: var(--rh-primary);
            font-weight: 700;
        }

        /* HERO */
        .ci-hero {
            border-radius: var(--rh-radius);
            padding: 1.75rem 2rem;
            color: #fff;
            background: radial-gradient(circle at 92% 0%, rgba(249, 178, 51, .25) 0, transparent 38%),
                linear-gradient(135deg, var(--rh-primary) 0%, var(--rh-primary-dark) 100%);
            border-bottom: 4px solid var(--rh-gold);
            box-shadow: 0 10px 30px rgba(3, 63, 31, .12);
        }

        .ci-hero h1 {
            color: #fff;
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0 0 .35rem;
        }

        .ci-hero p {
            margin: 0;
            color: rgba(255, 255, 255, .82);
            font-size: .88rem;
        }

        /* CARD */
        .ci-card {
            background: #fff;
            border: 1px solid var(--rh-border);
            border-radius: var(--rh-radius);
            box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
            overflow: hidden;
            margin-bottom: 1.25rem;
        }

        .ci-card-header {
            background: linear-gradient(90deg, #f8fafc 0%, #fff 100%);
            border-bottom: 1px solid var(--rh-border);
            padding: .95rem 1.35rem;
        }

        .ci-card-header h6 {
            color: var(--rh-primary);
            font-weight: 700;
            text-transform: uppercase;
            font-size: .78rem;
            letter-spacing: .06em;
            display: flex;
            align-items: center;
            margin: 0;
        }

        .ci-card-header h6 i {
            color: var(--rh-gold);
            font-size: 1.2rem;
            margin-right: .55rem;
        }

        .ci-card-body {
            padding: 1.35rem 1.5rem;
        }

        /* INFO ROW */
        .ci-info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: .7rem 0;
            border-bottom: 1px dashed var(--rh-border);
            gap: 1rem;
        }

        .ci-info-row:last-child {
            border-bottom: none;
        }

        .ci-info-label {
            font-size: .78rem;
            color: var(--rh-muted);
            font-weight: 600;
            flex-shrink: 0;
        }

        .ci-info-value {
            font-size: .85rem;
            color: #1e293b;
            font-weight: 600;
            text-align: right;
            word-break: break-word;
        }

        /* BADGES */
        .ci-badge {
            font-size: .72rem;
            font-weight: 600;
            padding: .3rem .7rem;
            border-radius: 50rem;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            white-space: nowrap;
        }

        .ci-badge-ok {
            background: rgba(25, 135, 84, .1);
            color: var(--rh-success);
            border: 1px solid rgba(25, 135, 84, .25);
        }

        .ci-badge-ko {
            background: rgba(220, 53, 69, .08);
            color: var(--rh-danger);
            border: 1px solid rgba(220, 53, 69, .2);
        }

        .ci-badge-warn {
            background: rgba(249, 178, 51, .15);
            color: #b97d0c;
            border: 1px solid rgba(249, 178, 51, .4);
        }

        .ci-badge-info {
            background: rgba(13, 110, 253, .08);
            color: var(--rh-info);
            border: 1px solid rgba(13, 110, 253, .2);
        }

        .ci-tag {
            display: inline-block;
            background: var(--rh-primary-light);
            color: var(--rh-primary);
            border: 1px solid rgba(3, 63, 31, .15);
            font-size: .75rem;
            font-weight: 600;
            padding: .25rem .65rem;
            border-radius: 6px;
            margin: .15rem .25rem .15rem 0;
        }

        /* HONNEUR */
        .ci-honneur {
            background: linear-gradient(135deg, var(--rh-primary-light) 0%, #fff 100%);
            border-left: 4px solid var(--rh-primary);
            border-radius: 12px;
            padding: 1.15rem 1.35rem;
            font-size: .86rem;
            color: #334155;
            line-height: 1.7;
            font-style: italic;
        }

        .ci-honneur strong {
            font-style: normal;
            color: var(--rh-primary);
            display: block;
            margin-bottom: .35rem;
        }

        /* BOUTONS */
        .ci-btn-success {
            background: var(--rh-success);
            color: #fff;
            border: 1px solid var(--rh-success);
            font-weight: 700;
            padding: .75rem 1.5rem;
            border-radius: 10px;
            transition: all .2s ease;
        }

        .ci-btn-success:hover {
            background: #146c43;
            color: #fff;
            transform: translateY(-1px);
        }

        .ci-btn-danger {
            background: var(--rh-danger);
            color: #fff;
            border: 1px solid var(--rh-danger-l);
            font-weight: 700;
            padding: .75rem 1.5rem;
            border-radius: 10px;
            transition: all .2s ease;
        }

        .ci-btn-danger:hover {
            background: #b02a37;
            color: #fff;
            transform: translateY(-1px);
        }

        .ci-btn-outline {
            background: #fff;
            color: var(--rh-muted);
            border: 1px solid var(--rh-border);
            font-weight: 500;
            padding: .65rem 1.15rem;
            border-radius: 10px;
            transition: all .2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .ci-btn-outline:hover {
            background: #f8fafc;
            color: #334155;
        }

        .ci-btn-gold {
            background: var(--rh-gold);
            color: var(--rh-primary);
            border: 1px solid var(--rh-gold);
            font-weight: 700;
            padding: .65rem 1.15rem;
            border-radius: 10px;
            transition: all .2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .ci-btn-gold:hover {
            background: var(--rh-gold-hover);
            color: var(--rh-primary);
            transform: translateY(-1px);
        }

        .ci-btn-primary {
            background: var(--rh-primary);
            color: #fff;
            border: 1px solid var(--rh-primary);
            font-weight: 600;
            padding: .65rem 1.15rem;
            border-radius: 10px;
            transition: all .2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .ci-btn-primary:hover {
            background: var(--rh-primary-dark);
            color: #fff;
            transform: translateY(-1px);
        }

        /* TIMELINE */
        .ci-timeline {
            position: relative;
            padding-left: 24px;
        }

        .ci-timeline::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 4px;
            bottom: 4px;
            width: 2px;
            background: var(--rh-border);
        }

        .ci-timeline-item {
            position: relative;
            padding-bottom: 1rem;
        }

        .ci-timeline-item:last-child {
            padding-bottom: 0;
        }

        .ci-timeline-item::before {
            content: '';
            position: absolute;
            left: -22px;
            top: 4px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid var(--rh-gold-light);
        }

        .ci-timeline-action {
            font-size: .82rem;
            font-weight: 700;
            color: var(--rh-primary);
        }

        .ci-timeline-meta {
            font-size: .72rem;
            color: var(--rh-muted);
            margin-top: .15rem;
        }

        .ci-timeline-comment {
            font-size: .78rem;
            color: #334155;
            margin-top: .25rem;
            font-style: italic;
        }

        /* INFO ALERT */
        .ci-alert {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            font-size: .85rem;
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            line-height: 1.6;
        }

        .ci-alert i {
            font-size: 1.2rem;
            flex-shrink: 0;
            margin-top: .1rem;
        }

        .ci-alert-info {
            background: rgba(13, 110, 253, .08);
            color: #0a58ca;
            border-left: 4px solid rgba(13, 110, 253, .08);
        }

        .ci-alert-success {
            background: rgba(25, 135, 84, .1);
            color: #146c43;
            border-left: 4px solid var(--rh-success);
        }

        .ci-alert-warning {
            background: rgba(249, 178, 51, .15);
            color: #856404;
            border-left: 4px solid var(--rh-gold);
        }

        .ci-alert-danger {
            background: rgba(220, 53, 69, .08);
            color: #b02a37;
            border-left: 4px solid rgba(220, 53, 69, .08);
        }

        /* PRINT */
        @media print {
            .ci-no-print {
                display: none !important;
            }

            .ci-hero {
                background: #033f1f !important;
                color: #fff !important;
                box-shadow: none !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .ci-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
        }
    </style>

    @php
        use Carbon\Carbon;

        $formaterDate = fn($d) => $d ? Carbon::parse($d)->format('d/m/Y') : '—';
        $formaterDateHeure = fn($d) => $d ? Carbon::parse($d)->format('d/m/Y à H:i') : '—';

        $naturesLabels = [
            'PARTICIPATION_FINANCIERE' => 'Participation financière',
            'LIEN_FAMILIAL' => 'Lien familial',
            'ACTIVITE_EXTERNE' => 'Activité externe',
            'FONCTION_ADMINISTRATEUR' => "Fonction d'administrateur",
            'RELATION_PERSONNELLE' => 'Relation personnelle',
            'CADEAUX_AVANTAGES' => 'Cadeaux / avantages',
            'AUTRE' => 'Autre',
        ];

        // ⚠️ Sécurisation : le conflit peut être null (Q1 = NON)
        $conflit = $declaration->conflit;

        $natures = [];
        if ($conflit?->nature_conflit) {
            $natures = is_string($conflit->nature_conflit)
                ? json_decode($conflit->nature_conflit, true) ?? []
                : $conflit->nature_conflit;
        }
        $natures = is_array($natures) ? $natures : [];

        $isOui = $declaration->reponse_q1 === 'OUI';
        $reftype = $declaration->reference ? strtoupper(explode('-', $declaration->reference)[0] ?? '') : '';
        $isDen = $reftype === 'DEN';
        $isAuto = !$isDen;

        // ⚠️ Peut-on traiter la déclaration ? (toutes les déclarations non traitées)
        $peutTraiter = in_array($declaration->statut, ['SOUMISE', 'MODIFIEE']);

        // ⚠️ Y a-t-il des données de conflit à afficher ?
        $hasConflitData = $isOui && $conflit !== null;
    @endphp

    <div class="page-content">

        {{-- BREADCRUMB --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4 ci-no-print">
            <div class="breadcrumb-title pe-3 ci-page-title">CONFORMITÉ — ADMIN</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}" class="text-decoration-none" style="color: var(--rh-primary);">
                                <i class="bx bx-home-alt fs-6"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.conflits.index') }}" class="text-decoration-none"
                                style="color: var(--rh-primary);">
                                Déclarations
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-muted" style="font-size: .85rem;">
                            {{ $declaration->reference }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- HERO --}}
        <div class="ci-hero mb-4">
            <div
                class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                <div>
                    <h1 class="d-flex align-items-center gap-2">
                        <i class="bx {{ $isDen ? 'bx-error-alt' : 'bx-file' }}" style="color: var(--rh-gold);"></i>
                        {{ $isDen ? 'Dénonciation' : 'Déclaration' }} {{ $declaration->reference }}
                    </h1>
                    <p>
                        Soumise le {{ $formaterDateHeure($declaration->date_soumission) }}
                        @if ($declaration->date_derniere_modif)
                            — Modifiée le {{ $formaterDateHeure($declaration->date_derniere_modif) }}
                        @endif
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="ci-badge {{ $isDen ? 'ci-badge-ko' : 'ci-badge-info' }}">
                        <i class="bx {{ $isDen ? 'bx-error' : 'bx-user-check' }}"></i>
                        {{ $isDen ? 'Dénonciation' : 'Auto-déclaration' }}
                    </span>
                    @if ($isOui)
                        <span class="ci-badge ci-badge-ko">
                            <i class="bx bx-error-circle"></i> Conflit déclaré
                        </span>
                    @else
                        <span class="ci-badge ci-badge-ok">
                            <i class="bx bx-check-circle"></i> Absence de conflit
                        </span>
                    @endif
                    @switch($declaration->statut)
                        @case('SOUMISE')
                            <span class="ci-badge ci-badge-info"><i class="bx bx-send"></i> Soumise</span>
                        @break

                        @case('MODIFIEE')
                            <span class="ci-badge ci-badge-warn"><i class="bx bx-edit"></i> Modifiée</span>
                        @break

                        @case('VALIDEE')
                            <span class="ci-badge ci-badge-ok"><i class="bx bx-check-double"></i> Validée</span>
                        @break

                        @case('REJETEE')
                            <span class="ci-badge ci-badge-ko"><i class="bx bx-x"></i> Rejetée</span>
                        @break
                    @endswitch
                </div>
            </div>
        </div>

        {{-- MESSAGES --}}
        @if (session('success'))
            <div class="alert alert-success mb-4 border-0 rounded-3 ci-no-print">
                <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mb-4 border-0 rounded-3 ci-no-print">
                <i class="bx bx-error-circle me-1"></i> {{ session('error') }}
            </div>
        @endif

        <div class="row g-4">

            {{-- ============================================================
             COLONNE GAUCHE — CONTENU
        ============================================================ --}}
            <div class="col-lg-8">

                {{-- IDENTIFICATION --}}
                <div class="ci-card">
                    <div class="ci-card-header">
                        <h6><i class="bx bx-user-circle"></i> Identification du
                            {{ $isDen ? 'dénonciateur' : 'collaborateur' }}</h6>
                    </div>
                    <div class="ci-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Nom et Prénoms</span>
                                    <span class="ci-info-value">{{ $declaration->nom_prenoms }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Matricule</span>
                                    <span class="ci-info-value font-monospace">{{ $declaration->matricule }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Fonction</span>
                                    <span class="ci-info-value">{{ $declaration->fonction ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Direction</span>
                                    <span class="ci-info-value">{{ $declaration->direction ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
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
                            <div class="col-md-4">
                                <div class="ci-info-row">
                                    <span class="ci-info-label">N+1</span>
                                    <span class="ci-info-value">{{ $declaration->nom_n1 ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CONTENU DE LA DÉCLARATION --}}
                <div class="ci-card">
                    <div class="ci-card-header">
                        <h6><i class="bx bx-help-circle"></i> Contenu de la déclaration</h6>
                    </div>
                    <div class="ci-card-body">

                        @if (!$isOui)
                            {{-- ============================================
                             CAS Q1 = NON — aucun conflit
                        ============================================ --}}
                            <div class="ci-alert ci-alert-success mb-3">
                                <i class="bx bx-check-circle"></i>
                                <div>
                                    <strong>Aucun conflit d'intérêts déclaré</strong>
                                    <div style="font-size:.82rem;margin-top:.25rem;">
                                        Le collaborateur a certifié sur l'honneur ne pas être en situation de conflit
                                        d'intérêts.
                                    </div>
                                </div>
                            </div>

                            @if ($declaration->declarationHonneur)
                                <div class="ci-honneur">
                                    <strong>Déclaration sur l'honneur</strong>
                                    Je certifie sur l'honneur ne pas être en situation de conflit d'intérêts réel,
                                    potentiel ou apparent à la date de la présente déclaration et m'engage à signaler
                                    toute situation future pouvant constituer un conflit d'intérêts.
                                    <div class="mt-2 text-muted" style="font-size:.78rem;font-style:normal;">
                                        <i class="bx bx-calendar-check"></i>
                                        Certifiée le
                                        {{ $formaterDateHeure($declaration->declarationHonneur->date_certification) }}
                                    </div>
                                </div>
                            @else
                                <div class="ci-alert ci-alert-warning">
                                    <i class="bx bx-info-circle"></i>
                                    <div>
                                        <strong>Déclaration sur l'honneur non signée</strong>
                                        <div style="font-size:.82rem;margin-top:.25rem;">
                                            Le collaborateur n'a pas encore complété sa déclaration sur l'honneur.
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @elseif ($hasConflitData)
                            {{-- ============================================
                             CAS Q1 = OUI + conflit présent
                        ============================================ --}}
                            <div class="ci-alert ci-alert-danger mb-3">
                                <i class="bx bx-error-circle"></i>
                                <div>
                                    <strong>Conflit d'intérêts déclaré</strong>
                                    <div style="font-size:.82rem;margin-top:.25rem;">
                                        Consultez le détail ci-dessous et traitez la déclaration (valider ou rejeter).
                                    </div>
                                </div>
                            </div>

                            {{-- Nature --}}
                            <div class="ci-info-row">
                                <span class="ci-info-label">Nature du conflit</span>
                                <span class="ci-info-value" style="max-width:60%;">
                                    @forelse ($natures as $n)
                                        <span class="ci-tag">{{ $naturesLabels[$n] ?? $n }}</span>
                                    @empty
                                        <span class="text-muted">—</span>
                                    @endforelse
                                </span>
                            </div>

                            {{-- Description --}}
                            <div class="ci-info-row">
                                <span class="ci-info-label">Description détaillée</span>
                                <span class="ci-info-value" style="max-width:60%;text-align:right;white-space:pre-line;">
                                    {{ $conflit->description_detaillee }}
                                </span>
                            </div>

                            @if ($conflit->personne_societe_concernee)
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Société concernée</span>
                                    <span class="ci-info-value">{{ $conflit->personne_societe_concernee }}</span>
                                </div>
                            @endif

                            @if ($conflit->client_fournisseur_partenaire)
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Client / fournisseur</span>
                                    <span class="ci-info-value">{{ $conflit->client_fournisseur_partenaire }}</span>
                                </div>
                            @endif

                            @if ($conflit->relation_type)
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Relation personnelle</span>
                                    <span class="ci-info-value">
                                        <span
                                            class="ci-badge {{ $conflit->relation_type === 'INTERNE' ? 'ci-badge-info' : 'ci-badge-warn' }}">
                                            {{ $conflit->relation_type === 'INTERNE' ? 'Interne' : 'Externe' }}
                                        </span>
                                        @if ($conflit->relation_type === 'INTERNE' && $conflit->relationAgent)
                                            — {{ $conflit->relationAgent->nom }} {{ $conflit->relationAgent->prenom }}
                                        @elseif ($conflit->relation_nom_externe)
                                            — {{ $conflit->relation_nom_externe }}
                                        @endif
                                    </span>
                                </div>
                            @endif

                            @if ($conflit->cadeau_type)
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Cadeaux / avantages</span>
                                    <span class="ci-info-value">
                                        <span
                                            class="ci-badge {{ $conflit->cadeau_type === 'INTERNE' ? 'ci-badge-info' : 'ci-badge-warn' }}">
                                            {{ $conflit->cadeau_type === 'INTERNE' ? 'Interne' : 'Externe' }}
                                        </span>
                                        @if ($conflit->cadeau_type === 'INTERNE' && $conflit->cadeauAgent)
                                            — {{ $conflit->cadeauAgent->nom }} {{ $conflit->cadeauAgent->prenom }}
                                        @elseif ($conflit->cadeau_nom_externe)
                                            — {{ $conflit->cadeau_nom_externe }}
                                        @endif
                                    </span>
                                </div>
                            @endif

                            <div class="ci-info-row">
                                <span class="ci-info-label">Date de début</span>
                                <span class="ci-info-value">{{ $formaterDate($conflit->date_debut_situation) }}</span>
                            </div>

                            <div class="ci-info-row">
                                <span class="ci-info-label">Influence sur décisions</span>
                                <span class="ci-info-value">
                                    @if ($conflit->influence_decisions === 'OUI')
                                        <span class="ci-badge ci-badge-ko"><i class="bx bx-error"></i> Oui</span>
                                    @else
                                        <span class="ci-badge ci-badge-ok"><i class="bx bx-check"></i> Non</span>
                                    @endif
                                </span>
                            </div>

                            @if ($conflit->mesures_prises)
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Mesures déjà prises</span>
                                    <span class="ci-info-value" style="max-width:60%;text-align:right;">
                                        {{ $conflit->mesures_prises }}
                                    </span>
                                </div>
                            @endif
                        @else
                            {{-- ============================================
                             CAS ANORMAL : Q1 = OUI mais pas de conflit
                        ============================================ --}}
                            <div class="ci-alert ci-alert-warning">
                                <i class="bx bx-info-circle"></i>
                                <div>
                                    <strong>Données de conflit manquantes</strong>
                                    <div style="font-size:.82rem;margin-top:.25rem;">
                                        La déclaration indique un conflit (Q1 = OUI) mais le détail n'a pas été enregistré.
                                        Vérifiez la base de données ou contactez le collaborateur.
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>



            </div>

            {{-- ============================================================
             COLONNE DROITE — MÉTADONNÉES + HISTORIQUE + ACTIONS
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
                            <span class="ci-info-value font-monospace">{{ $declaration->reference }}</span>
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
                                @switch($declaration->statut)
                                    @case('SOUMISE')
                                        <span class="ci-badge ci-badge-info"><i class="bx bx-send"></i> Soumise</span>
                                    @break

                                    @case('MODIFIEE')
                                        <span class="ci-badge ci-badge-warn"><i class="bx bx-edit"></i> Modifiée</span>
                                    @break

                                    @case('VALIDEE')
                                        <span class="ci-badge ci-badge-ok"><i class="bx bx-check-double"></i> Validée</span>
                                    @break

                                    @case('REJETEE')
                                        <span class="ci-badge ci-badge-ko"><i class="bx bx-x"></i> Rejetée</span>
                                    @break
                                @endswitch
                            </span>
                        </div>
                        <div class="ci-info-row">
                            <span class="ci-info-label">Soumission</span>
                            <span class="ci-info-value">{{ $formaterDateHeure($declaration->date_soumission) }}</span>
                        </div>
                        @if ($declaration->date_derniere_modif)
                            <div class="ci-info-row">
                                <span class="ci-info-label">Modification</span>
                                <span
                                    class="ci-info-value">{{ $formaterDateHeure($declaration->date_derniere_modif) }}</span>
                            </div>
                        @endif
                        @if ($declaration->date_validation)
                            <div class="ci-info-row">
                                <span class="ci-info-label">Validation</span>
                                <span class="ci-info-value">
                                    {{ $formaterDateHeure($declaration->date_validation) }}
                                    @if ($declaration->valideePar)
                                        <br><small class="text-muted">par {{ $declaration->valideePar->name }}</small>
                                    @endif
                                </span>
                            </div>
                        @endif
                        @if ($declaration->date_rejet)
                            <div class="ci-info-row">
                                <span class="ci-info-label">Rejet</span>
                                <span class="ci-info-value">
                                    {{ $formaterDateHeure($declaration->date_rejet) }}
                                    @if ($declaration->rejeteePar)
                                        <br><small class="text-muted">par {{ $declaration->rejeteePar->name }}</small>
                                    @endif
                                </span>
                            </div>
                            @if ($declaration->motif_rejet)
                                <div class="ci-info-row">
                                    <span class="ci-info-label">Motif</span>
                                    <span class="ci-info-value"
                                        style="max-width:60%;text-align:right;color:var(--rh-danger);">
                                        {{ $declaration->motif_rejet }}
                                    </span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- HISTORIQUE --}}
                @if ($declaration->historiques && $declaration->historiques->count() > 0)
                    <div class="ci-card">
                        <div class="ci-card-header">
                            <h6><i class="bx bx-history"></i> Historique</h6>
                        </div>
                        <div class="ci-card-body">
                            <div class="ci-timeline">
                                @foreach ($declaration->historiques as $h)
                                    <div class="ci-timeline-item">
                                        <div class="ci-timeline-action">
                                            {{ $h->action ?? 'Action' }}
                                            @if ($h->ancien_statut || $h->nouveau_statut)
                                                <span class="text-muted" style="font-weight:400;">
                                                    ({{ $h->ancien_statut ?? '—' }} → {{ $h->nouveau_statut ?? '—' }})
                                                </span>
                                            @endif
                                        </div>
                                        <div class="ci-timeline-meta">
                                            {{ $formaterDateHeure($h->created_at) }}
                                            @if ($h->user)
                                                — par {{ $h->user->name ?? 'utilisateur' }}
                                            @endif
                                        </div>
                                        @if ($h->commentaire)
                                            <div class="ci-timeline-comment">{{ $h->commentaire }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ============================================================
                 ACTIONS ADMIN — VALIDER / REJETER
                 ⚠️ Affichées pour TOUTES les déclarations traitables,
                    qu'elles soient AUTO, DEN, Q1=OUI ou Q1=NON
            ============================================================ --}}
                {{-- ✅ APRÈS — uniquement pour les dénonciations --}}
                @if ($peutTraiter && $isDen)
                    <div class="ci-card ci-no-print">
                        <div class="ci-card-header">
                            <h6><i class="bx bx-cog"></i> Traiter cette dénonciation</h6>
                        </div>
                        <div class="ci-card-body">

                            {{-- Info contextuelle --}}
                            <div class="ci-alert ci-alert-info mb-3">
                                <i class="bx bx-info-circle"></i>
                                <div>
                                    <strong>Action requise</strong>
                                    <div style="font-size:.82rem;margin-top:.25rem;">
                                        Vous pouvez <strong>valider</strong> ou <strong>rejeter</strong> cette dénonciation.
                                        Une fois traitée, elle ne pourra plus être modifiée.
                                    </div>
                                </div>
                            </div>

                            {{-- Valider --}}
                            <form method="POST" action="{{ route('admin.conflits.valider', $declaration) }}"
                                class="mb-3">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="ci-btn-success w-100"
                                    onclick="return confirm('Confirmer la validation de cette dénonciation ?')">
                                    <i class="bx bx-check-double me-1"></i> Valider la dénonciation
                                </button>
                            </form>

                            {{-- Rejeter --}}
                            <form method="POST" action="{{ route('admin.conflits.rejeter', $declaration) }}">
                                @csrf
                                @method('PATCH')
                                <label class="ci-info-label d-block mb-2" style="text-align:left;">
                                    Motif du rejet <span style="color:var(--rh-danger);">*</span>
                                </label>
                                <textarea name="motif_rejet" class="form-control mb-2 @error('motif_rejet') is-invalid @enderror" rows="3"
                                    minlength="10" maxlength="2000" placeholder="Expliquez la raison du rejet (minimum 10 caractères)...">{{ old('motif_rejet') }}</textarea>
                                @error('motif_rejet')
                                    <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
                                @enderror
                                <button type="submit" class="ci-btn-danger w-100"
                                    onclick="return confirm('Confirmer le rejet de cette dénonciation ?')">
                                    <i class="bx bx-x-circle me-1"></i> Rejeter la dénonciation
                                </button>
                            </form>

                        </div>
                    </div>
                @endif

                {{-- AUTRES ACTIONS --}}


                <div class="ci-card ci-no-print">
                    <div class="ci-card-header">
                        <h6><i class="bx bx-dots-horizontal-rounded"></i> Autres actions</h6>
                    </div>
                    <div class="ci-card-body">
                        <div class="d-grid gap-2">

                            {{-- Accusé de réception (à activer quand la route existera) --}}
                            {{-- <a href="{{ route('admin.conflits.accuse.pdf', $declaration) }}"
                                class="ci-btn ci-btn-gold text-center">
                                <i class="bx bx-receipt me-1"></i> Accusé de réception
                            </a> --}}

                            {{-- Déclaration intégrale PDF --}}
                            <a href="{{ route('conflits.accuse.declarationPdf', $declaration) }}"
                                class="ci-btn ci-btn-primary text-center">
                                <i class="bx bx-file me-1"></i> Déclaration intégrale (PDF)
                            </a>

                            {{-- Retour à la liste --}}
                            <a href="{{ route('admin.conflits.index') }}" class="ci-btn-outline text-center">
                                <i class="bx bx-list-ul me-1"></i> Retour à la liste
                            </a>

                        </div>
                    </div>
                </div>


            </div>

        </div>

    </div>

@endsection
