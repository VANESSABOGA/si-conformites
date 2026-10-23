<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Déclaration — {{ $declaration->reference }}</title>
    <style>
        /* ============================================================
           RESET & PAGE
        ============================================================ */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.5;
            background: #fff;
        }

        .content {
            padding: 15mm 12mm 22mm 12mm;
        }

        /* ============================================================
           EN-TÊTE
        ============================================================ */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px solid #F9B233;
            margin-bottom: 15px;
        }

        .header-table td {
            vertical-align: top;
            padding-bottom: 10px;
        }

        /* .header-logo-cell {
            width: 60px;
            padding-right: 10px;
        } */

        /* .header-logo {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            background: #033f1f;
            color: #F9B233;
            text-align: center;
            line-height: 48px;
            font-size: 20px;
            font-weight: 900;
        } */

        .header-logo-cell {
            width: 75px;
            padding-right: 12px;
            vertical-align: middle;
        }

        .logo-icon {
            display: block;
            max-width: 60px;
            height: auto;
        }

        /* Fallback si logo absent */
        .header-logo {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            background: #033f1f;
            color: #F9B233;
            text-align: center;
            line-height: 48px;
            font-size: 20px;
            font-weight: 900;
        }

        .header-company {
            font-size: 15pt;
            font-weight: 800;
            color: #033f1f;
        }

        .header-slogan {
            font-size: 8pt;
            color: #64748b;
            font-style: italic;
            margin-top: 2px;
        }

        .header-right {
            text-align: right;
        }

        .header-doc-label {
            font-size: 8pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 600;
        }

        .header-doc-ref {
            font-family: 'Courier New', monospace;
            font-size: 13pt;
            font-weight: 800;
            color: #033f1f;
            margin-top: 2px;
        }

        .header-doc-date {
            font-size: 8pt;
            color: #64748b;
            margin-top: 4px;
        }

        /* ============================================================
           TITRE
        ============================================================ */
        .doc-title-block {
            background: #033f1f;
            color: #fff;
            text-align: center;
            padding: 14px 20px;
            border-radius: 8px;
            margin-bottom: 18px;
            border-bottom: 4px solid #F9B233;
        }

        .doc-title-block h1 {
            font-size: 14pt;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: #fff;
        }

        .doc-title-block p {
            font-size: 8pt;
            color: #c9d8ce;
            margin-top: 4px;
        }

        /* ============================================================
           BADGES & TAGS
        ============================================================ */
        .badge {
            display: inline-block;
            font-size: 7.5pt;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
            line-height: 1.4;
            white-space: nowrap;
        }

        .badge-ok {
            background: #d4edda;
            color: #198754;
            border: 1px solid #b1d8b7;
        }

        .badge-ko {
            background: #f8d7da;
            color: #dc3545;
            border: 1px solid #f1b0b7;
        }

        .badge-warn {
            background: #fff3cd;
            color: #b97d0c;
            border: 1px solid #ffe69c;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0d6efd;
            border: 1px solid #bee5eb;
        }

        .tag {
            display: inline-block;
            background: #e8f0eb;
            color: #033f1f;
            border: 1px solid #c9d8ce;
            font-size: 7pt;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 4px;
            margin: 1px 3px 1px 0;
        }

        /* ============================================================
           SECTIONS
        ============================================================ */
        .section {
            margin-bottom: 14px;
            page-break-inside: avoid;
        }

        .section-header {
            background: #e8f0eb;
            border-left: 4px solid #F9B233;
            border-radius: 4px;
            padding: 7px 12px;
            margin-bottom: 8px;
        }

        .section-header h2 {
            font-size: 9pt;
            font-weight: 800;
            color: #033f1f;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        /* ============================================================
           INFO ROWS
        ============================================================ */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px 0;
            border-bottom: 1px dashed #d1d9e0;
            vertical-align: top;
        }

        .info-table tr:last-child td {
            border-bottom: none;
        }

        .info-label {
            font-size: 8pt;
            color: #64748b;
            font-weight: 600;
            width: 35%;
            padding-right: 10px;
        }

        .info-value {
            font-size: 8.5pt;
            color: #1e293b;
            font-weight: 600;
            text-align: right;
        }

        .info-value.left {
            text-align: left;
            font-weight: 500;
        }

        /* ============================================================
           BLOCS DESCRIPTION
        ============================================================ */
        .description-block {
            background: #f8fafc;
            border-left: 3px solid #033f1f;
            border-radius: 4px;
            padding: 10px 12px;
            font-size: 8.5pt;
            line-height: 1.6;
            color: #334155;
            margin-top: 4px;
            word-wrap: break-word;
        }

        .honneur-block {
            background: #e8f0eb;
            border: 1px solid #d1d9e0;
            border-left: 3px solid #033f1f;
            border-radius: 6px;
            padding: 12px 14px;
            font-size: 8.5pt;
            font-style: italic;
            color: #334155;
            line-height: 1.6;
        }

        .honneur-block strong {
            display: block;
            font-style: normal;
            color: #033f1f;
            font-weight: 700;
            margin-bottom: 6px;
        }

        /* ============================================================
           SIGNATURES
        ============================================================ */
        .signature-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px 0;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .signature-cell {
            width: 50%;
            border: 1px dashed #d1d9e0;
            border-radius: 6px;
            padding: 12px;
            vertical-align: top;
        }

        .signature-label {
            font-size: 7.5pt;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: .05em;
            margin-bottom: 8px;
        }

        .signature-name {
            font-size: 9pt;
            font-weight: 700;
            color: #033f1f;
        }

        .signature-date {
            font-size: 7.5pt;
            color: #64748b;
            margin-top: 2px;
        }

        /* ============================================================
           FOOTER
        ============================================================ */
        .footer {
            position: fixed;
            bottom: 6mm;
            left: 12mm;
            right: 12mm;
            font-size: 7pt;
            color: #64748b;
            border-top: 1px solid #d1d9e0;
            padding-top: 6px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            font-size: 7pt;
            color: #64748b;
        }

        .footer-page {
            text-align: center;
            font-size: 7pt;
            color: #64748b;
            margin-top: 3px;
        }

        /* ============================================================
           UTILITAIRES
        ============================================================ */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-mono {
            font-family: 'Courier New', monospace;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }
    </style>
</head>

<body>

    @php
        use Carbon\Carbon;

        $formatDate = fn($d) => $d ? Carbon::parse($d)->format('d/m/Y') : '—';
        $formatDateTime = fn($d) => $d ? Carbon::parse($d)->format('d/m/Y à H:i') : '—';

        $reftype = $declaration->reference ? strtoupper(explode('-', $declaration->reference)[0] ?? '') : '';
        $isDen = $reftype === 'DEN';
        $isOui = $declaration->reponse_q1 === 'OUI';

        $conflit = $declaration->conflit;
        $honneur = $declaration->declarationHonneur;

        $naturesLabels = [
            'PARTICIPATION_FINANCIERE' => 'Participation financière',
            'LIEN_FAMILIAL' => 'Lien familial',
            'ACTIVITE_EXTERNE' => 'Activité externe',
            'FONCTION_ADMINISTRATEUR' => "Fonction d'administrateur",
            'RELATION_PERSONNELLE' => 'Relation personnelle',
            'CADEAUX_AVANTAGES' => 'Cadeaux / avantages',
            'AUTRE' => 'Autre',
        ];

        $natures = [];
        if ($conflit?->nature_conflit) {
            $natures = is_string($conflit->nature_conflit)
                ? json_decode($conflit->nature_conflit, true) ?? []
                : $conflit->nature_conflit;
        }
        $natures = is_array($natures) ? $natures : [];

        $relationAgent = $conflit?->relationAgent;
        $cadeauAgent = $conflit?->cadeauAgent;
        $agentDenonce = $conflit?->agentDenonce;

        $denonceNom = $conflit?->agent_denonce_nom;
        $denonceFonction = $conflit?->agent_denonce_fonction;
        $niveauUrgence = $conflit?->niveau_urgence;
        $dateFaits = $conflit?->date_faits;
        $lieuFaits = $conflit?->lieu_faits;
        $impactEstime = $conflit?->impact_estime;
        $dejaSignale = $conflit?->deja_signale ?? false;
        $signaleA = $conflit?->signale_a;
        $protectionDen = $conflit?->protection_denonciateur ?? false;
        $souhaiteAnonyme = $conflit?->souhaite_anonyme ?? false;

        $temoins = $conflit?->temoins ?? [];
        if (is_string($temoins)) {
            $temoins = json_decode($temoins, true) ?? [];
        }
        $temoins = is_array($temoins) ? $temoins : [];

        $statutMap = [
            'SOUMISE' => ['label' => 'Soumise', 'badge' => 'badge-info'],
            'MODIFIEE' => ['label' => 'Modifiée', 'badge' => 'badge-warn'],
            'VALIDEE' => ['label' => 'Validée', 'badge' => 'badge-ok'],
            'REJETEE' => ['label' => 'Rejetée', 'badge' => 'badge-ko'],
        ];
        $statut = $statutMap[$declaration->statut] ?? ['label' => $declaration->statut, 'badge' => 'badge-info'];

        $urgenceClass =
            [
                'FAIBLE' => 'badge-ok',
                'MOYEN' => 'badge-info',
                'ELEVE' => 'badge-warn',
                'CRITIQUE' => 'badge-ko',
            ][$niveauUrgence ?? 'MOYEN'] ?? 'badge-info';

        $dateEmission = now();
    @endphp

    <div class="content">

        {{-- ============================================================
         EN-TÊTE
    ============================================================ --}}
        <table class="header-table">
    <tr>
        {{-- Logo en base64 (fiable DomPDF) --}}
        <td class="header-logo-cell">
            @php
                $logoPath = public_path('assets/images/logo-yako.png');
                $logoBase64 = file_exists($logoPath)
                    ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
                    : null;
            @endphp

            @if ($logoBase64)
                <img src="{{ $logoBase64 }}" width="60" class="logo-icon" alt="Logo Yako">
            @else
                <div class="header-logo">YA</div>
            @endif
        </td>

        {{-- Nom entreprise --}}
        <td>
            {{-- <div class="header-company">YAKO AFRICA</div> --}}
            <div class="header-slogan">YAKO AFRICA Assurance Vie — Conformité &amp; Éthique</div>
        </td>

        {{-- Référence + date --}}
        <td class="header-right">
            <div class="header-doc-label">Référence</div>
            <div class="header-doc-ref">{{ $declaration->reference }}</div>
            <div class="header-doc-date">Édité le {{ $formatDateTime($dateEmission) }}</div>
        </td>
    </tr>
</table>

        {{-- ============================================================
         TITRE
    ============================================================ --}}
        <div class="doc-title-block">
            <h1>
                {{ $isDen ? 'Déclaration de dénonciation' : "Déclaration de conflit d'intérêts" }}
            </h1>
            <p>Document intégral — Traitement confidentiel</p>
        </div>

        {{-- ============================================================
         RÉSUMÉ
    ============================================================ --}}
        <div class="section">
            <div class="section-header">
                <h2>Résumé de la déclaration</h2>
            </div>
            <table class="info-table">
                <tr>
                    <td class="info-label">Type</td>
                    <td class="info-value">
                        <span class="badge {{ $isDen ? 'badge-ko' : 'badge-info' }}">
                            {{ $isDen ? 'Dénonciation' : 'Auto-déclaration' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Statut</td>
                    <td class="info-value">
                        <span class="badge {{ $statut['badge'] }}">{{ $statut['label'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Réponse principale</td>
                    <td class="info-value">
                        @if ($isOui)
                            <span class="badge badge-ko">Conflit d'intérêts déclaré</span>
                        @else
                            <span class="badge badge-ok">Aucun conflit d'intérêts déclaré</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Date de soumission</td>
                    <td class="info-value">{{ $formatDateTime($declaration->date_soumission) }}</td>
                </tr>
                @if ($declaration->date_derniere_modif)
                    <tr>
                        <td class="info-label">Dernière modification</td>
                        <td class="info-value">{{ $formatDateTime($declaration->date_derniere_modif) }}</td>
                    </tr>
                @endif
                @if ($declaration->date_validation)
                    <tr>
                        <td class="info-label">Date de validation</td>
                        <td class="info-value">{{ $formatDateTime($declaration->date_validation) }}</td>
                    </tr>
                @endif
                @if ($declaration->date_rejet)
                    <tr>
                        <td class="info-label">Date de rejet</td>
                        <td class="info-value">{{ $formatDateTime($declaration->date_rejet) }}</td>
                    </tr>
                    @if ($declaration->motif_rejet)
                        <tr>
                            <td class="info-label">Motif du rejet</td>
                            <td class="info-value" style="color:#dc3545;">{{ $declaration->motif_rejet }}</td>
                        </tr>
                    @endif
                @endif
            </table>
        </div>

        {{-- ============================================================
         IDENTIFICATION
    ============================================================ --}}
        <div class="section">
            <div class="section-header">
                <h2>1. Identification du {{ $isDen ? 'dénonciateur' : 'collaborateur' }}</h2>
            </div>
            <table class="info-table">
                <tr>
                    <td class="info-label">Nom et Prénoms</td>
                    <td class="info-value">{{ $declaration->nom_prenoms }}</td>
                </tr>
                <tr>
                    <td class="info-label">Matricule</td>
                    <td class="info-value font-mono">{{ $declaration->matricule }}</td>
                </tr>
                <tr>
                    <td class="info-label">Fonction</td>
                    <td class="info-value">{{ $declaration->fonction ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Direction</td>
                    <td class="info-value">{{ $declaration->direction ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Département</td>
                    <td class="info-value">{{ $declaration->departement ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="info-label">E-mail professionnel</td>
                    <td class="info-value">{{ $declaration->email_pro ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="info-label">N+1</td>
                    <td class="info-value">{{ $declaration->nom_n1 ?? '—' }}</td>
                </tr>
            </table>
        </div>

        {{-- ============================================================
         AGENT DÉNONCÉ (DEN)
    ============================================================ --}}
        @if ($isDen)
            <div class="section">
                <div class="section-header">
                    <h2>2. Agent concerné par la dénonciation</h2>
                </div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Agent dénoncé</td>
                        <td class="info-value">
                            @if ($agentDenonce)
                                {{ $agentDenonce->nom }} {{ $agentDenonce->prenom }} ({{ $agentDenonce->matricule }})
                            @elseif ($denonceNom)
                                {{ $denonceNom }}
                                @if ($denonceFonction)
                                    — {{ $denonceFonction }}
                                @endif
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    @if ($niveauUrgence)
                        <tr>
                            <td class="info-label">Niveau d'urgence</td>
                            <td class="info-value">
                                <span class="badge {{ $urgenceClass }}">{{ $niveauUrgence }}</span>
                            </td>
                        </tr>
                    @endif
                    @if ($dateFaits)
                        <tr>
                            <td class="info-label">Date des faits</td>
                            <td class="info-value">{{ $formatDate($dateFaits) }}</td>
                        </tr>
                    @endif
                    @if ($lieuFaits)
                        <tr>
                            <td class="info-label">Lieu des faits</td>
                            <td class="info-value">{{ $lieuFaits }}</td>
                        </tr>
                    @endif
                    @if ($dejaSignale)
                        <tr>
                            <td class="info-label">Déjà signalé</td>
                            <td class="info-value">
                                Oui @if ($signaleA)
                                    — {{ $signaleA }}
                                @endif
                            </td>
                        </tr>
                    @endif
                    @if (count($temoins) > 0)
                        <tr>
                            <td class="info-label">Témoins</td>
                            <td class="info-value">
                                @foreach ($temoins as $t)
                                    <span class="tag">{{ $t }}</span>
                                @endforeach
                            </td>
                        </tr>
                    @endif
                    @if ($impactEstime)
                        <tr>
                            <td class="info-label">Impact estimé</td>
                            <td class="info-value left">{{ $impactEstime }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        @endif

        {{-- ============================================================
         CONTENU DE LA DÉCLARATION
    ============================================================ --}}
        @if (!$isOui)
            {{-- CAS NON : DÉCLARATION SUR L'HONNEUR --}}
            <div class="section">
                <div class="section-header">
                    <h2>{{ $isDen ? '3' : '2' }}. Déclaration sur l'honneur</h2>
                </div>
                <div class="honneur-block">
                    <strong>Engagement du déclarant</strong>
                    Je certifie sur l'honneur ne pas être en situation de conflit d'intérêts réel, potentiel
                    ou apparent à la date de la présente déclaration et m'engage à signaler toute situation
                    future pouvant constituer un conflit d'intérêts.
                </div>
                <table class="info-table mt-2">
                    <tr>
                        <td class="info-label">Certification</td>
                        <td class="info-value">
                            @if ($honneur?->certification_honneur)
                                <span class="badge badge-ok">Signée</span>
                            @else
                                <span class="badge badge-ko">Non signée</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Engagement de signalement</td>
                        <td class="info-value">
                            @if ($honneur?->engagement_signalement)
                                <span class="badge badge-ok">Signé</span>
                            @else
                                <span class="badge badge-ko">Non signé</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Date de certification</td>
                        <td class="info-value">{{ $formatDateTime($honneur?->date_certification) }}</td>
                    </tr>
                </table>
            </div>
        @elseif ($conflit)
            {{-- CAS OUI : DÉTAIL DU CONFLIT --}}
            <div class="section">
                <div class="section-header">
                    <h2>{{ $isDen ? '3' : '2' }}. {{ $isDen ? 'Faits constatés' : 'Conflit déclaré' }}</h2>
                </div>

                <table class="info-table">
                    <tr>
                        <td class="info-label">Nature(s) du conflit</td>
                        <td class="info-value">
                            @forelse ($natures as $n)
                                <span class="tag">{{ $naturesLabels[$n] ?? $n }}</span>
                            @empty
                                —
                            @endforelse
                        </td>
                    </tr>
                </table>

                <div style="margin-top:8px;">
                    <div style="font-size:8pt; color:#64748b; font-weight:600; margin-bottom:4px;">
                        {{ $isDen ? 'Description des faits' : 'Description détaillée' }}
                    </div>
                    <div class="description-block">{{ $conflit->description_detaillee }}</div>
                </div>

                <table class="info-table mt-2">
                    @if ($conflit->personne_societe_concernee)
                        <tr>
                            <td class="info-label">Société concernée</td>
                            <td class="info-value">{{ $conflit->personne_societe_concernee }}</td>
                        </tr>
                    @endif
                    @if ($conflit->client_fournisseur_partenaire)
                        <tr>
                            <td class="info-label">Client / fournisseur</td>
                            <td class="info-value">{{ $conflit->client_fournisseur_partenaire }}</td>
                        </tr>
                    @endif
                    @if ($conflit->relation_type)
                        <tr>
                            <td class="info-label">Relation personnelle</td>
                            <td class="info-value">
                                {{ $conflit->relation_type === 'INTERNE' ? 'Interne' : 'Externe' }}
                                @if ($conflit->relation_type === 'INTERNE' && $relationAgent)
                                    — {{ $relationAgent->nom }} {{ $relationAgent->prenom }}
                                @elseif ($conflit->relation_nom_externe)
                                    — {{ $conflit->relation_nom_externe }}
                                @endif
                            </td>
                        </tr>
                    @endif
                    @if ($conflit->cadeau_type)
                        <tr>
                            <td class="info-label">Cadeaux / avantages</td>
                            <td class="info-value">
                                {{ $conflit->cadeau_type === 'INTERNE' ? 'Interne' : 'Externe' }}
                                @if ($conflit->cadeau_type === 'INTERNE' && $cadeauAgent)
                                    — {{ $cadeauAgent->nom }} {{ $cadeauAgent->prenom }}
                                @elseif ($conflit->cadeau_nom_externe)
                                    — {{ $conflit->cadeau_nom_externe }}
                                @endif
                            </td>
                        </tr>
                    @endif
                    @if ($conflit->date_debut_situation)
                        <tr>
                            <td class="info-label">Date de début</td>
                            <td class="info-value">{{ $formatDate($conflit->date_debut_situation) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="info-label">Influence sur décisions</td>
                        <td class="info-value">
                            @if ($conflit->influence_decisions === 'OUI')
                                <span class="badge badge-ko">Oui</span>
                            @else
                                <span class="badge badge-ok">Non</span>
                            @endif
                        </td>
                    </tr>
                </table>

                @if ($conflit->mesures_prises)
                    <div style="margin-top:8px;">
                        <div style="font-size:8pt; color:#64748b; font-weight:600; margin-bottom:4px;">
                            Mesures déjà prises
                        </div>
                        <div class="description-block">{{ $conflit->mesures_prises }}</div>
                    </div>
                @endif
            </div>
        @endif

        {{-- ============================================================
         PROTECTION DU DÉCLARANT (DEN)
    ============================================================ --}}
        @if ($isDen && ($protectionDen || $souhaiteAnonyme))
            <div class="section">
                <div class="section-header">
                    <h2>{{ $isOui ? '4' : '3' }}. Protection du déclarant</h2>
                </div>
                <table class="info-table">
                    @if ($souhaiteAnonyme)
                        <tr>
                            <td class="info-label">Anonymat demandé</td>
                            <td class="info-value"><span class="badge badge-warn">Oui</span></td>
                        </tr>
                    @endif
                    @if ($protectionDen)
                        <tr>
                            <td class="info-label">Protection lanceur d'alerte</td>
                            <td class="info-value"><span class="badge badge-ok">Demandée</span></td>
                        </tr>
                    @endif
                </table>
            </div>
        @endif

        {{-- ============================================================
         MENTION LÉGALE
    ============================================================ --}}
        <div class="section">
            <div class="section-header">
                <h2>Mentions légales</h2>
            </div>
            <div style="font-size:8pt; color:#64748b; line-height:1.7; padding:0 4px;">
                Cette déclaration est établie conformément à la politique interne de gestion des conflits
                d'intérêts de YAKO AFRICA ASSURANCE VIE. Les informations qu'elle contient sont traitées
                de manière confidentielle par la cellule Conformité et ne sont communiquées qu'aux personnes
                habilitées, dans le strict respect de la réglementation en vigueur.
                <br><br>
                Toute fausse déclaration intentionnelle pourrait entraîner des sanctions disciplinaires,
                conformément au règlement intérieur de l'entreprise.
            </div>
        </div>

        {{-- ============================================================
         SIGNATURES
    ============================================================ --}}
        <table class="signature-table">
            <tr>
                <td class="signature-cell">
                    <div class="signature-label">Le déclarant</div>
                    <div class="signature-name">{{ $declaration->nom_prenoms }}</div>
                    <div class="signature-date">
                        {{ $isOui
                            ? ($honneur?->date_certification
                                ? $formatDateTime($honneur->date_certification)
                                : '—')
                            : 'Déclaration signée électroniquement' }}
                    </div>
                </td>
                <td class="signature-cell">
                    <div class="signature-label">La cellule Conformité</div>
                    <div class="signature-name">YAKO AFRICA</div>
                    <div class="signature-date">Reçu le {{ $formatDateTime($declaration->date_soumission) }}</div>
                </td>
            </tr>
        </table>

    </div>

    {{-- ============================================================
     FOOTER
============================================================ --}}
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td style="text-align:left; font-style:italic;">
                    Déclaration intégrale — Document confidentiel
                </td>
                <td style="text-align:right; font-family:'Courier New', monospace;">
                    {{ $declaration->reference }} • {{ $formatDate($dateEmission) }}
                </td>
            </tr>
        </table>
        <div class="footer-page">
            YAKO AFRICA ASSURANCE VIE — Cellule Conformité • Ne pas diffuser
        </div>
    </div>

</body>

</html>
