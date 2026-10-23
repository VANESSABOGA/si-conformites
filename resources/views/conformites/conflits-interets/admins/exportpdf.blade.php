<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Conformités — Déclarations de conflits d'intérêts</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 12mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #1e293b;
            line-height: 1.4;
        }

        /* ============================================================
           EN-TÊTE
        ============================================================ */
        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #033f1f;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .header-left,
        .header-right {
            display: table-cell;
            vertical-align: middle;
        }

        .header-left { width: 70%; }
        .header-right { width: 30%; text-align: right; }

        .header-left h1 {
            color: #033f1f;
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 2px;
        }

        .header-left p {
            color: #64748b;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .header-right .doc-label {
            display: inline-block;
            padding: 4px 8px;
            border: 1.5px solid #033f1f;
            color: #033f1f;
            font-size: 7.5px;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
            line-height: 1.3;
            text-align: center;
        }

        /* ============================================================
           MÉTADONNÉES DU RAPPORT
        ============================================================ */
        .meta-box {
            display: table;
            width: 100%;
            background: #f8fafc;
            border: 1px solid #e5e9ef;
            border-left: 3px solid #F9B233;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 12px;
        }

        .meta-item {
            display: table-cell;
            vertical-align: middle;
            padding-right: 15px;
        }

        .meta-label {
            color: #64748b;
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
            display: block;
            margin-bottom: 1px;
        }

        .meta-value {
            color: #033f1f;
            font-size: 9.5px;
            font-weight: 800;
        }

        /* ============================================================
           FILTRES APPLIQUÉS (badges)
        ============================================================ */
        .filtres-appliques {
            margin-bottom: 12px;
        }

        .filtre-titre {
            font-size: 8px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 4px;
        }

        .filtre-badge {
            display: inline-block;
            background: rgba(3, 63, 31, .06);
            color: #033f1f;
            border: 1px solid rgba(3, 63, 31, .2);
            font-size: 7.5px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 20px;
            margin-right: 4px;
            margin-bottom: 3px;
        }

        /* ============================================================
           TABLEAU
        ============================================================ */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin-bottom: 12px;
        }

        thead {
            background: #033f1f;
            color: #fff;
        }

        thead th {
            color: #fff;
            font-weight: 700;
            font-size: 7px;
            text-transform: uppercase;
            letter-spacing: .4px;
            padding: 6px 5px;
            text-align: left;
            border: none;
            border-bottom: 2px solid #F9B233;
        }

        tbody tr {
            border-bottom: 1px solid #e5e9ef;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody td {
            padding: 5px;
            vertical-align: middle;
            font-size: 7.8px;
            color: #1e293b;
        }

        .td-ref {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #033f1f;
            font-size: 7.5px;
        }

        .td-main {
            font-weight: 600;
            color: #0f172a;
        }

        .td-sub {
            font-size: 6.8px;
            color: #64748b;
            margin-top: 1px;
        }

        /* Badges de statut */
        .badge {
            display: inline-block;
            padding: 1.5px 5px;
            border-radius: 10px;
            font-size: 6.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .2px;
        }

        .badge-soumise  { background: #dbeafe; color: #1e40af; }
        .badge-modifiee { background: #fef3c7; color: #92400e; }
        .badge-validee  { background: #d1fae5; color: #065f46; }
        .badge-rejetee  { background: #fee2e2; color: #991b1b; }

        .badge-oui { background: #fee2e2; color: #991b1b; }
        .badge-non { background: #d1fae5; color: #065f46; }

        .tag-nature {
            display: inline-block;
            background: rgba(3, 63, 31, .06);
            color: #033f1f;
            border: 1px solid rgba(3, 63, 31, .15);
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 6.5px;
            font-weight: 600;
            margin-right: 2px;
            margin-bottom: 1px;
        }

        /* ============================================================
           FOOTER
        ============================================================ */
        .footer {
            position: fixed;
            bottom: 5mm;
            left: 12mm;
            right: 12mm;
            border-top: 1px solid #e5e9ef;
            padding-top: 4px;
            font-size: 7px;
            color: #94a3b8;
            text-align: center;
            line-height: 1.4;
        }

        .footer strong { color: #033f1f; }

        /* ============================================================
           ÉTAT VIDE
        ============================================================ */
        .empty {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }

        .empty h3 {
            color: #033f1f;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .empty p {
            font-size: 9px;
        }
    </style>
</head>

<body>

    {{-- ============================================================
         EN-TÊTE
    ============================================================ --}}
    <div class="header">
        <div class="header-left">
            <h1>YAKO AFRICA ASSURANCES VIE</h1>
            <p>Conformité — Déclarations de conflits d'intérêts</p>
        </div>
        <div class="header-right">
            <div class="doc-label">
                RAPPORT<br>CONFIDENTIEL
            </div>
        </div>
    </div>

    {{-- ============================================================
         MÉTADONNÉES
    ============================================================ --}}
    <div class="meta-box">
        <div class="meta-item">
            <span class="meta-label">Date du rapport</span>
            <span class="meta-value">{{ now()->format('d/m/Y à H:i') }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Total déclarations</span>
            <span class="meta-value">{{ $declarations->count() }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Généré par</span>
            <span class="meta-value">{{ auth()->user()?->name ?? 'Administrateur' }}</span>
        </div>
    </div>

    {{-- ============================================================
         FILTRES APPLIQUÉS
    ============================================================ --}}
    @if (!empty(array_filter($filtres ?? [])))
        <div class="filtres-appliques">
            <div class="filtre-titre">Filtres appliqués :</div>
            @if (!empty($filtres['q']))
                <span class="filtre-badge">🔍 "{{ $filtres['q'] }}"</span>
            @endif
            @if (!empty($filtres['statut']))
                <span class="filtre-badge">Statut : {{ $filtres['statut'] }}</span>
            @endif
            @if (!empty($filtres['type']))
                <span class="filtre-badge">Type : {{ $filtres['type'] }}</span>
            @endif
            @if (!empty($filtres['reponse_q1']))
                <span class="filtre-badge">Réponse : {{ $filtres['reponse_q1'] }}</span>
            @endif
            @if (!empty($filtres['direction']))
                <span class="filtre-badge">Direction : {{ $filtres['direction'] }}</span>
            @endif
            @if (!empty($filtres['nature']))
                <span class="filtre-badge">Nature : {{ $filtres['nature'] }}</span>
            @endif
            @if (!empty($filtres['du']))
                <span class="filtre-badge">Du : {{ $filtres['du'] }}</span>
            @endif
            @if (!empty($filtres['au']))
                <span class="filtre-badge">Au : {{ $filtres['au'] }}</span>
            @endif
        </div>
    @endif

    {{-- ============================================================
         TABLEAU
    ============================================================ --}}
    @if ($declarations->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Type / Réf.</th>
                    <th style="width: 18%;">Collaborateur</th>
                    <th style="width: 16%;">Direction</th>
                    <th style="width: 8%;">Réponse</th>
                    <th style="width: 20%;">Nature</th>
                    <th style="width: 10%;">Statut</th>
                    <th style="width: 16%;">Soumis le</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($declarations as $d)
                    @php
                        $reftype = $d->reference ? strtoupper(explode('-', $d->reference)[0] ?? '') : '';
                        $isDen   = $reftype === 'DEN';
                    @endphp
                    <tr>
                        <td>
                            @if ($isDen)
                                <span class="badge badge-rejetee">DÉNONCIATION</span>
                            @else
                                <span class="badge badge-soumise">AUTO</span>
                            @endif
                            <div class="td-ref" style="margin-top: 2px;">{{ $d->reference }}</div>
                        </td>
                        <td>
                            <div class="td-main">{{ $d->nom_prenoms }}</div>
                            <div class="td-sub">{{ $d->matricule }}</div>
                        </td>
                        <td>
                            <div class="td-main" style="font-size: 7.5px;">{{ $d->direction ?? '—' }}</div>
                            @if ($d->departement)
                                <div class="td-sub">{{ $d->departement }}</div>
                            @endif
                        </td>
                        <td>
                            @if ($d->reponse_q1 === 'OUI')
                                <span class="badge badge-oui">OUI</span>
                            @else
                                <span class="badge badge-non">NON</span>
                            @endif
                        </td>
                        <td>
                            @if ($d->reponse_q1 === 'OUI' && count($d->nature_labels ?? []) > 0)
                                @foreach (array_slice($d->nature_labels, 0, 3) as $label)
                                    <span class="tag-nature">{{ $label }}</span>
                                @endforeach
                                @if (count($d->nature_labels) > 3)
                                    <span class="tag-nature">+{{ count($d->nature_labels) - 3 }}</span>
                                @endif
                            @else
                                <span class="td-sub">—</span>
                            @endif
                        </td>
                        <td>
                            @switch($d->statut)
                                @case('SOUMISE')
                                    <span class="badge badge-soumise">Soumise</span>
                                    @break
                                @case('MODIFIEE')
                                    <span class="badge badge-modifiee">Modifiée</span>
                                    @break
                                @case('VALIDEE')
                                    <span class="badge badge-validee">Validée</span>
                                    @break
                                @case('REJETEE')
                                    <span class="badge badge-rejetee">Rejetée</span>
                                    @break
                                @default
                                    <span class="badge">{{ $d->statut }}</span>
                            @endswitch
                        </td>
                        <td>
                            <div class="td-main" style="font-size: 7.5px;">
                                {{ $d->date_soumission?->format('d/m/Y') ?? '—' }}
                            </div>
                            <div class="td-sub">
                                {{ $d->date_soumission?->format('H:i') ?? '' }}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty">
            <h3>Aucune déclaration</h3>
            <p>Aucune déclaration ne correspond aux critères sélectionnés.</p>
        </div>
    @endif

    {{-- ============================================================
         FOOTER
    ============================================================ --}}
    <div class="footer">
        <strong>YAKO AFRICA ASSURANCES VIE</strong> — Rapport généré le
        {{ now()->format('d/m/Y à H:i') }}
        &nbsp;|&nbsp;
        {{ $declarations->count() }} déclaration(s)
        <br>
        Document strictement confidentiel — Usage interne uniquement
    </div>

</body>
</html>
