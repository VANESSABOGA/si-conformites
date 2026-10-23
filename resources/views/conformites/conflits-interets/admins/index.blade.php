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
            --rh-danger-light: rgba(220, 53, 69, 0.08);
            --rh-success: #198754;
            --rh-success-light: rgba(25, 135, 84, 0.1);
            --rh-info: #0d6efd;
            --rh-info-light: rgba(13, 110, 253, 0.08);
            --rh-border: #e5e9ef;
            --rh-muted: #64748b;
            --rh-radius: 14px;
        }

        .ci-page-title {
            color: var(--rh-primary);
            font-weight: 700;
        }

        /* ============================================================
               HERO COMPACT
            ============================================================ */
        .ci-hero {
            border-radius: var(--rh-radius);
            padding: 1.25rem 1.75rem;
            color: #fff;
            background: radial-gradient(circle at 92% 0%, rgba(249, 178, 51, .25) 0, transparent 38%),
                linear-gradient(135deg, var(--rh-primary) 0%, var(--rh-primary-dark) 100%);
            border-bottom: 4px solid var(--rh-gold);
            box-shadow: 0 10px 30px rgba(3, 63, 31, .12);
        }

        .ci-hero h1 {
            color: #fff;
            font-size: 1.15rem;
            font-weight: 700;
            margin: 0 0 .25rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .ci-hero p {
            margin: 0;
            color: rgba(255, 255, 255, .82);
            font-size: .82rem;
        }

        /* ============================================================
               BARRE DE STATUT (bandeau résumé compact)
            ============================================================ */
        .ci-status-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0;
            background: #fff;
            border: 1px solid var(--rh-border);
            border-radius: var(--rh-radius);
            box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .ci-stat {
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            border-right: 1px solid var(--rh-border);
            transition: background .15s ease;
            text-decoration: none;
            color: inherit;
            position: relative;
        }

        .ci-stat:last-child {
            border-right: none;
        }

        .ci-stat:hover {
            background: var(--rh-primary-light);
        }

        .ci-stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .ci-stat-body {
            flex: 1;
            min-width: 0;
        }

        .ci-stat-value {
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1.1;
            color: var(--rh-primary);
        }

        .ci-stat-label {
            font-size: .68rem;
            color: var(--rh-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-top: 2px;
        }

        .ci-stat.total .ci-stat-icon {
            background: var(--rh-primary-light);
            color: var(--rh-primary);
        }

        .ci-stat.attente .ci-stat-icon {
            background: var(--rh-info-light);
            color: var(--rh-info);
        }

        .ci-stat.validee .ci-stat-icon {
            background: var(--rh-success-light);
            color: var(--rh-success);
        }

        .ci-stat.rejetee .ci-stat-icon {
            background: var(--rh-danger-light);
            color: var(--rh-danger);
        }

        /* ============================================================
               BARRE DE FILTRES + RECHERCHE + EXPORT
            ============================================================ */
        .ci-toolbar {
            background: #fff;
            border: 1px solid var(--rh-border);
            border-radius: var(--rh-radius);
            padding: .85rem 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
        }

        .ci-toolbar-row {
            display: flex;
            align-items: center;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .ci-toolbar-row+.ci-toolbar-row {
            margin-top: .6rem;
            padding-top: .6rem;
            border-top: 1px dashed var(--rh-border);
        }

        .ci-toolbar-search {
            flex: 1;
            min-width: 240px;
        }

        .ci-toolbar-search input {
            width: 100%;
            border: 1px solid var(--rh-border);
            border-radius: 8px;
            padding: .5rem .85rem .5rem 2.2rem;
            font-size: .85rem;
            background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.3-4.3'/%3E%3C/svg%3E") no-repeat .65rem center;
            background-size: 16px;
            transition: all .2s ease;
        }

        .ci-toolbar-search input:focus {
            border-color: var(--rh-primary);
            box-shadow: 0 0 0 .2rem var(--rh-primary-light);
            outline: none;
        }

        .ci-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .4rem .75rem;
            border-radius: 8px;
            border: 1px solid var(--rh-border);
            background: #fff;
            font-size: .8rem;
            color: var(--rh-muted);
            cursor: pointer;
            transition: all .2s ease;
            white-space: nowrap;
        }

        .ci-filter-chip:hover {
            border-color: var(--rh-primary);
            color: var(--rh-primary);
        }

        .ci-filter-chip.active {
            background: var(--rh-primary-light);
            border-color: var(--rh-primary);
            color: var(--rh-primary);
            font-weight: 600;
        }

        .ci-filter-chip .ci-chip-count {
            background: var(--rh-primary);
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 20px;
            min-width: 20px;
            text-align: center;
        }

        .ci-filter-clear {
            margin-left: auto;
            font-size: .78rem;
            color: var(--rh-danger);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .25rem;
        }

        .ci-filter-clear:hover {
            color: var(--rh-danger);
            text-decoration: underline;
        }

        /* ============================================================
               SECTION TITLE (discret)
            ============================================================ */
        .ci-section-title {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .75rem;
            font-weight: 700;
            color: var(--rh-muted);
            text-transform: uppercase;
            letter-spacing: .06em;
            margin: 0 0 .6rem;
        }

        .ci-section-title i {
            color: var(--rh-gold);
            font-size: 1rem;
        }

        /* ============================================================
               KPI CARTES COMPACTES
            ============================================================ */
        .ci-kpi-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: .6rem;
            margin-bottom: 1rem;
        }

        .ci-kpi-mini {
            background: #fff;
            border: 1px solid var(--rh-border);
            border-radius: 10px;
            padding: .6rem .75rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            text-decoration: none;
            color: inherit;
            transition: all .15s ease;
            position: relative;
            cursor: pointer;
        }

        .ci-kpi-mini:hover {
            border-color: var(--rh-primary);
            box-shadow: 0 2px 8px rgba(15, 23, 42, .08);
            transform: translateY(-1px);
        }

        .ci-kpi-mini.is-active {
            background: var(--rh-primary-light);
            border-color: var(--rh-primary);
            box-shadow: 0 0 0 2px rgba(3, 63, 31, .08);
        }

        .ci-kpi-mini-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .ci-dot-oui {
            background: var(--rh-danger);
        }

        .ci-dot-non {
            background: var(--rh-success);
        }

        .ci-dot-auto {
            background: var(--rh-info);
        }

        .ci-dot-den {
            background: var(--rh-danger);
        }

        .ci-kpi-mini-label {
            font-size: .75rem;
            color: var(--rh-muted);
            font-weight: 600;
            flex: 1;
            min-width: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ci-kpi-mini-value {
            font-size: .9rem;
            font-weight: 800;
            color: var(--rh-primary);
        }

        .ci-kpi-mini-pct {
            font-size: .65rem;
            color: var(--rh-muted);
            margin-left: .25rem;
        }

        /* ============================================================
               TABLEAU
            ============================================================ */
        .ci-table-wrapper {
            background: #fff;
            border: 1px solid var(--rh-border);
            border-radius: var(--rh-radius);
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
            padding: .75rem;
        }

        .ci-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
            font-size: .82rem;
        }

        .ci-table thead {
            background: #f8fafc;
            border-bottom: 2px solid var(--rh-gold);
        }

        .ci-table thead th {
            color: var(--rh-primary);
            font-weight: 700;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            padding: .7rem .85rem;
            text-align: left;
            white-space: nowrap;
        }

        .ci-table tbody tr {
            border-bottom: 1px solid var(--rh-border);
            transition: background .15s ease;
        }

        .ci-table tbody tr:hover {
            background: var(--rh-primary-light);
        }

        .ci-table tbody tr:last-child {
            border-bottom: none;
        }

        .ci-table tbody td {
            padding: .65rem .85rem;
            vertical-align: middle;
            color: #334155;
        }

        .ci-table .ref {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: var(--rh-primary);
            font-size: .78rem;
        }

        .ci-table .cell-main {
            font-weight: 600;
            color: #1e293b;
            font-size: .82rem;
        }

        .ci-table .cell-sub {
            font-size: .72rem;
            color: var(--rh-muted);
            margin-top: 2px;
        }

        /* Badges compacts */
        .ci-badge {
            font-size: .68rem;
            font-weight: 600;
            padding: .2rem .55rem;
            border-radius: 50rem;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            white-space: nowrap;
            line-height: 1.3;
        }

        .ci-badge-ok {
            background: var(--rh-success-light);
            color: var(--rh-success);
            border: 1px solid rgba(25, 135, 84, .25);
        }

        .ci-badge-ko {
            background: var(--rh-danger-light);
            color: var(--rh-danger);
            border: 1px solid rgba(220, 53, 69, .2);
        }

        .ci-badge-warn {
            background: rgba(249, 178, 51, .15);
            color: #b97d0c;
            border: 1px solid rgba(249, 178, 51, .4);
        }

        .ci-badge-info {
            background: var(--rh-info-light);
            color: var(--rh-info);
            border: 1px solid rgba(13, 110, 253, .2);
        }

        .ci-tag {
            display: inline-block;
            background: var(--rh-primary-light);
            color: var(--rh-primary);
            border: 1px solid rgba(3, 63, 31, .15);
            font-size: .65rem;
            font-weight: 600;
            padding: .12rem .45rem;
            border-radius: 4px;
            margin: .1rem .15rem .1rem 0;
        }

        .ci-action-btn {
            background: var(--rh-primary);
            color: #fff;
            border: 1px solid var(--rh-primary);
            font-weight: 600;
            font-size: .72rem;
            padding: .3rem .65rem;
            border-radius: 7px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .25rem;
            transition: all .15s ease;
        }

        .ci-action-btn:hover {
            background: var(--rh-primary-dark);
            color: #fff;
        }

        /* DataTables */
        div.dataTables_wrapper div.dataTables_filter,
        div.dataTables_wrapper div.dataTables_length,
        div.dataTables_wrapper div.dataTables_info,
        div.dataTables_wrapper div.dataTables_paginate {
            display: none;
        }

        /* masqué — on utilise notre toolbar */

        .dt-buttons {
            display: none;
        }

        /* masqué — boutons custom */

        .ci-export-btn {
            background: #fff;
            color: var(--rh-muted);
            border: 1px solid var(--rh-border);
            font-size: .78rem;
            padding: .4rem .75rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            text-decoration: none;
            cursor: pointer;
            transition: all .15s ease;
            white-space: nowrap;
        }

        .ci-export-btn:hover {
            background: var(--rh-primary-light);
            color: var(--rh-primary);
            border-color: var(--rh-primary);
        }

        table.dataTable thead th {
            position: relative;
            padding-right: 1.5rem;
        }

        table.dataTable thead .sorting::after,
        table.dataTable thead .sorting_asc::after,
        table.dataTable thead .sorting_desc::after {
            content: '';
            position: absolute;
            right: .5rem;
            top: 50%;
            transform: translateY(-50%);
            border: 4px solid transparent;
            opacity: .3;
        }

        table.dataTable thead .sorting::after {
            border-top-color: var(--rh-primary);
            margin-top: 3px;
        }

        table.dataTable thead .sorting_asc::after {
            border-bottom-color: var(--rh-primary);
            margin-top: -3px;
        }

        table.dataTable thead .sorting_desc::after {
            border-top-color: var(--rh-primary);
            margin-top: 3px;
        }

        @media print {
            .ci-no-print {
                display: none !important;
            }

            body,
            html {
                background: #fff !important;
            }

            .ci-hero {
                background: #033f1f !important;
                color: #fff !important;
                box-shadow: none !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .ci-stat,
            .ci-kpi-mini {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .ci-table {
                font-size: 10px;
            }

            .ci-table thead th {
                background: #f0f0f0 !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .ci-action-btn {
                display: none !important;
            }

            a {
                text-decoration: none !important;
                color: #000 !important;
            }

            @page {
                size: A4 landscape;
                margin: 10mm;
            }
        }
    </style>

    <div class="page-content">

        {{-- BREADCRUMB --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 ci-no-print">
            <div class="breadcrumb-title pe-3 ci-page-title">CONFORMITÉ</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}" class="text-decoration-none" style="color: var(--rh-primary);">
                                <i class="bx bx-home-alt fs-6"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-muted" style="font-size: .85rem;">
                            Déclarations de conflits d'intérêts
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- HERO COMPACT --}}
        <div class="ci-hero mb-3">
            <div
                class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2">
                <div>
                    <h1>
                        <i class="bx bx-shield-quarter" style="color: var(--rh-gold);"></i>
                        Conflits d'intérêt — Déclarations
                    </h1>
                    <p>Suivi et traitement des déclarations de conflits d'intérêts</p>
                </div>
                <div class="ci-no-print d-flex align-items-center gap-2">
                    <span class="badge"
                        style="background: rgba(249, 178, 51, .2); color: var(--rh-gold); border: 1px solid rgba(249, 178, 51, .5); padding: .3rem .75rem; border-radius: 50rem; font-size: .7rem; font-weight: 700;">
                        <i class="bx bx-time-five"></i>
                        {{ now()->format('d/m/Y à H:i') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- MESSAGES --}}
        @if (session('success'))
            <div class="alert alert-success mb-3 border-0 rounded-3 ci-no-print py-2" style="font-size:.85rem;">
                <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mb-3 border-0 rounded-3 ci-no-print py-2" style="font-size:.85rem;">
                <i class="bx bx-error-circle me-1"></i> {{ session('error') }}
            </div>
        @endif

        {{-- ============================================================
         BARRE DE STATUTS (4 chiffres clés en ligne)
    ============================================================ --}}
        <div class="ci-status-bar ci-no-print">
            <a href="{{ route('admin.conflits.index') }}" class="ci-stat total">
                <div class="ci-stat-icon"><i class="bx bx-list-ul"></i></div>
                <div class="ci-stat-body">
                    <div class="ci-stat-value">{{ $kpis['total'] }}</div>
                    <div class="ci-stat-label">Total</div>
                </div>
            </a>
            <a href="{{ route('admin.conflits.index', array_merge($filters, ['statut' => 'SOUMISE'])) }}"
                class="ci-stat attente">
                <div class="ci-stat-icon"><i class="bx bx-time-five"></i></div>
                <div class="ci-stat-body">
                    <div class="ci-stat-value">{{ $kpis['soumises'] + $kpis['modifiees'] }}</div>
                    <div class="ci-stat-label">En attente</div>
                </div>
            </a>
            <a href="{{ route('admin.conflits.index', array_merge($filters, ['statut' => 'VALIDEE'])) }}"
                class="ci-stat validee">
                <div class="ci-stat-icon"><i class="bx bx-check-double"></i></div>
                <div class="ci-stat-body">
                    <div class="ci-stat-value">{{ $kpis['validees'] }}</div>
                    <div class="ci-stat-label">Validées</div>
                </div>
            </a>
            <a href="{{ route('admin.conflits.index', array_merge($filters, ['statut' => 'REJETEE'])) }}"
                class="ci-stat rejetee">
                <div class="ci-stat-icon"><i class="bx bx-x-circle"></i></div>
                <div class="ci-stat-body">
                    <div class="ci-stat-value">{{ $kpis['rejetees'] }}</div>
                    <div class="ci-stat-label">Rejetées</div>
                </div>
            </a>
        </div>

        {{-- ============================================================
         KPI : RÉPARTITION RÉPONSE + TYPE
    ============================================================ --}}
        <div class="row g-3 mb-3 ci-no-print">

            {{-- Répartition par réponse --}}
            <div class="col-md-6">
                <div class="ci-section-title">
                    <i class="bx bx-pie-chart-alt-2"></i>
                    Par réponse
                    @if (!empty($filters['reponse_q1']))
                        <a href="{{ route('admin.conflits.index', array_filter($filters, fn($k) => $k !== 'reponse_q1', ARRAY_FILTER_USE_KEY)) }}"
                            class="ci-filter-clear">
                            <i class="bx bx-x"></i> Retirer
                        </a>
                    @endif
                </div>
                <div class="ci-kpi-row">
                    @foreach ($kpisParReponse as $r)
                        @php
                            $isActive = ($filters['reponse_q1'] ?? null) === $r['key'];
                            $link = $isActive
                                ? route(
                                    'admin.conflits.index',
                                    array_filter($filters, fn($k) => $k !== 'reponse_q1', ARRAY_FILTER_USE_KEY),
                                )
                                : route('admin.conflits.index', array_merge($filters, ['reponse_q1' => $r['key']]));
                            $dotClass = $r['key'] === 'OUI' ? 'ci-dot-oui' : 'ci-dot-non';
                        @endphp
                        <a href="{{ $link }}" class="ci-kpi-mini {{ $isActive ? 'is-active' : '' }}">
                            <span class="ci-kpi-mini-dot {{ $dotClass }}"></span>
                            <span class="ci-kpi-mini-label">{{ $r['label'] }}</span>
                            <span class="ci-kpi-mini-value">{{ $r['total'] }}</span>
                            @if ($r['total'] > 0 && ($kpis['total'] ?? 0) > 0)
                                <span
                                    class="ci-kpi-mini-pct">{{ round(($r['total'] / max($kpis['total'], 1)) * 100) }}%</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Répartition par type --}}
            <div class="col-md-6">
                <div class="ci-section-title">
                    <i class="bx bx-category-alt"></i>
                    Par type
                    @if (!empty($filters['type']))
                        <a href="{{ route('admin.conflits.index', array_filter($filters, fn($k) => $k !== 'type', ARRAY_FILTER_USE_KEY)) }}"
                            class="ci-filter-clear">
                            <i class="bx bx-x"></i> Retirer
                        </a>
                    @endif
                </div>
                <div class="ci-kpi-row">
                    @foreach ($kpisParType as $t)
                        @php
                            $isActive = ($filters['type'] ?? null) === $t['key'];
                            $link = $isActive
                                ? route(
                                    'admin.conflits.index',
                                    array_filter($filters, fn($k) => $k !== 'type', ARRAY_FILTER_USE_KEY),
                                )
                                : route('admin.conflits.index', array_merge($filters, ['type' => $t['key']]));
                            $dotClass = $t['key'] === 'DEN' ? 'ci-dot-den' : 'ci-dot-auto';
                        @endphp
                        <a href="{{ $link }}" class="ci-kpi-mini {{ $isActive ? 'is-active' : '' }}">
                            <span class="ci-kpi-mini-dot {{ $dotClass }}"></span>
                            <span class="ci-kpi-mini-label">{{ $t['label'] }}</span>
                            <span class="ci-kpi-mini-value">{{ $t['total'] }}</span>
                            @if ($t['total'] > 0 && ($kpis['total'] ?? 0) > 0)
                                <span
                                    class="ci-kpi-mini-pct">{{ round(($t['total'] / max($kpis['total'], 1)) * 100) }}%</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ============================================================
         KPI : RÉPARTITION PAR NATURE (compact, horizontal scroll)
    ============================================================ --}}
        <div class="ci-section-title ci-no-print">
            <i class="bx bx-category"></i>
            Par nature
            @if (!empty($filters['nature']))
                <a href="{{ route('admin.conflits.index', array_filter($filters, fn($k) => $k !== 'nature', ARRAY_FILTER_USE_KEY)) }}"
                    class="ci-filter-clear">
                    <i class="bx bx-x"></i> Retirer
                </a>
            @endif
        </div>

        <div class="ci-kpi-row mb-3 ci-no-print" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));">
            @forelse ($kpisParNature as $n)
                @php
                    $isActive = ($filters['nature'] ?? null) === $n['key'];
                    $link = $isActive
                        ? route(
                            'admin.conflits.index',
                            array_filter($filters, fn($k) => $k !== 'nature', ARRAY_FILTER_USE_KEY),
                        )
                        : route('admin.conflits.index', array_merge($filters, ['nature' => $n['key']]));
                @endphp
                <a href="{{ $link }}"
                    class="ci-kpi-mini {{ $isActive ? 'is-active' : '' }} {{ $n['total'] === 0 ? 'opacity-50' : '' }}">
                    <span class="ci-kpi-mini-label">{{ $n['label'] }}</span>
                    <span class="ci-kpi-mini-value">{{ $n['total'] }}</span>
                </a>
            @empty
                <div class="text-muted small">Aucune nature enregistrée.</div>
            @endforelse
        </div>

        {{-- ============================================================
         TOOLBAR : RECHERCHE + FILTRES + EXPORT
    ============================================================ --}}
        <form method="GET" class="ci-toolbar ci-no-print" id="filterForm">

            {{-- Ligne 1 : Recherche + Export + Reset --}}
            <div class="ci-toolbar-row">
                <div class="ci-toolbar-search">
                    <input type="text" name="q" placeholder="Rechercher par référence, matricule, nom…"
                        value="{{ $filters['q'] ?? '' }}">
                </div>

                <button type="submit" class="ci-export-btn" title="Appliquer les filtres">
                    <i class="bx bx-filter-alt"></i> Filtrer
                </button>

                @if (array_filter($filters))
                    <a href="{{ route('admin.conflits.index') }}" class="ci-export-btn"
                        style="color:var(--rh-danger);border-color:rgba(220,53,69,.25);">
                        <i class="bx bx-x"></i> Réinitialiser
                    </a>
                @endif

                <div style="margin-left:auto; display:flex; gap:.35rem;">
                    {{-- <button type="button" class="ci-export-btn" onclick="exportTable('excel')">
                    <i class="bx bx-file"></i> Excel
                </button> --}}
                    <button type="button" class="ci-export-btn" onclick="exportTable('csv')">
                        <i class="bx bx-file-blank"></i> CSV
                    </button>
                    {{-- <button type="button" class="ci-export-btn" onclick="exportTable('pdf')">
                        <i class="bx bx-file-pdf"></i> PDF
                    </button> --}}
                    <button type="button" class="ci-export-btn"
                        onclick="window.location='{{ route('admin.conflits.export.pdf', $filters) }}'">
                        <i class="bx bx-file-pdf"></i> PDF
                    </button>

                    <button type="button" class="ci-export-btn" onclick="window.print()">
                        <i class="bx bx-printer"></i> Imprimer
                    </button>
                </div>
            </div>

            {{-- Ligne 2 : Filtres compacts --}}
            <div class="ci-toolbar-row">
                <span class="text-muted"
                    style="font-size:.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.04em;">Filtres
                    :</span>

                <select name="type" class="ci-filter-chip {{ !empty($filters['type']) ? 'active' : '' }}">
                    <option value="">Type</option>
                    <option value="AUTO" @selected(($filters['type'] ?? '') === 'AUTO')>Auto-déclaration</option>
                    <option value="DEN" @selected(($filters['type'] ?? '') === 'DEN')>Dénonciation</option>
                </select>

                <select name="reponse_q1" class="ci-filter-chip {{ !empty($filters['reponse_q1']) ? 'active' : '' }}">
                    <option value="">Réponse</option>
                    <option value="OUI" @selected(($filters['reponse_q1'] ?? '') === 'OUI')>OUI — Conflit</option>
                    <option value="NON" @selected(($filters['reponse_q1'] ?? '') === 'NON')>NON — Aucun</option>
                </select>

                <select name="statut" class="ci-filter-chip {{ !empty($filters['statut']) ? 'active' : '' }}">
                    <option value="">Statut</option>
                    @foreach (['SOUMISE' => 'Soumise', 'MODIFIEE' => 'Modifiée', 'VALIDEE' => 'Validée', 'REJETEE' => 'Rejetée'] as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['statut'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>

                <select name="direction" class="ci-filter-chip {{ !empty($filters['direction']) ? 'active' : '' }}">
                    <option value="">Direction</option>
                    @foreach ($directions as $dir)
                        <option value="{{ $dir }}" @selected(($filters['direction'] ?? '') === $dir)>{{ $dir }}</option>
                    @endforeach
                </select>

                <input type="date" name="du" class="ci-filter-chip {{ !empty($filters['du']) ? 'active' : '' }}"
                    value="{{ $filters['du'] ?? '' }}" title="Date de début">

                <input type="date" name="au" class="ci-filter-chip {{ !empty($filters['au']) ? 'active' : '' }}"
                    value="{{ $filters['au'] ?? '' }}" title="Date de fin">

                <button type="submit" class="ci-filter-chip"
                    style="background:var(--rh-primary);color:#fff;border-color:var(--rh-primary);">
                    <i class="bx bx-check"></i> Appliquer
                </button>
            </div>

            {{-- Filtres actifs --}}
            @if (array_filter($filters))
                <div class="ci-toolbar-row" style="border-top:none;padding-top:.4rem;">
                    <span class="text-muted" style="font-size:.72rem;">Filtres actifs :</span>
                    @if (!empty($filters['type']))
                        <a href="{{ route('admin.conflits.index', array_filter($filters, fn($k) => $k !== 'type', ARRAY_FILTER_USE_KEY)) }}"
                            class="ci-filter-chip active" style="font-size:.72rem;">
                            <i class="bx bx-category"></i> {{ $filters['type'] }}
                            <i class="bx bx-x" style="font-size:.85rem;"></i>
                        </a>
                    @endif
                    @if (!empty($filters['reponse_q1']))
                        <a href="{{ route('admin.conflits.index', array_filter($filters, fn($k) => $k !== 'reponse_q1', ARRAY_FILTER_USE_KEY)) }}"
                            class="ci-filter-chip active" style="font-size:.72rem;">
                            <i class="bx bx-check-circle"></i> {{ $filters['reponse_q1'] }}
                            <i class="bx bx-x" style="font-size:.85rem;"></i>
                        </a>
                    @endif
                    @if (!empty($filters['statut']))
                        <a href="{{ route('admin.conflits.index', array_filter($filters, fn($k) => $k !== 'statut', ARRAY_FILTER_USE_KEY)) }}"
                            class="ci-filter-chip active" style="font-size:.72rem;">
                            <i class="bx bx-info-circle"></i> {{ $filters['statut'] }}
                            <i class="bx bx-x" style="font-size:.85rem;"></i>
                        </a>
                    @endif
                    @if (!empty($filters['direction']))
                        <a href="{{ route('admin.conflits.index', array_filter($filters, fn($k) => $k !== 'direction', ARRAY_FILTER_USE_KEY)) }}"
                            class="ci-filter-chip active" style="font-size:.72rem;">
                            <i class="bx bx-building"></i> {{ Str::limit($filters['direction'], 20) }}
                            <i class="bx bx-x" style="font-size:.85rem;"></i>
                        </a>
                    @endif
                    @if (!empty($filters['nature']))
                        <a href="{{ route('admin.conflits.index', array_filter($filters, fn($k) => $k !== 'nature', ARRAY_FILTER_USE_KEY)) }}"
                            class="ci-filter-chip active" style="font-size:.72rem;">
                            <i class="bx bx-tag"></i> {{ $filters['nature'] }}
                            <i class="bx bx-x" style="font-size:.85rem;"></i>
                        </a>
                    @endif
                    @if (!empty($filters['du']))
                        <a href="{{ route('admin.conflits.index', array_filter($filters, fn($k) => $k !== 'du', ARRAY_FILTER_USE_KEY)) }}"
                            class="ci-filter-chip active" style="font-size:.72rem;">
                            <i class="bx bx-calendar"></i> ≥ {{ $filters['du'] }}
                            <i class="bx bx-x" style="font-size:.85rem;"></i>
                        </a>
                    @endif
                    @if (!empty($filters['au']))
                        <a href="{{ route('admin.conflits.index', array_filter($filters, fn($k) => $k !== 'au', ARRAY_FILTER_USE_KEY)) }}"
                            class="ci-filter-chip active" style="font-size:.72rem;">
                            <i class="bx bx-calendar"></i> ≤ {{ $filters['au'] }}
                            <i class="bx bx-x" style="font-size:.85rem;"></i>
                        </a>
                    @endif
                    @if (!empty($filters['q']))
                        <a href="{{ route('admin.conflits.index', array_filter($filters, fn($k) => $k !== 'q', ARRAY_FILTER_USE_KEY)) }}"
                            class="ci-filter-chip active" style="font-size:.72rem;">
                            <i class="bx bx-search"></i> "{{ Str::limit($filters['q'], 20) }}"
                            <i class="bx bx-x" style="font-size:.85rem;"></i>
                        </a>
                    @endif
                </div>
            @endif
        </form>

        {{-- ============================================================
         TABLEAU
    ============================================================ --}}
        <div class="ci-table-wrapper">
            <table id="declarationsTable" class="ci-table" style="width:100%">
                <thead>
                    <tr>
                        <th>Type / Réf.</th>
                        <th>Collaborateur</th>
                        <th>Direction</th>
                        <th>Réponse</th>
                        <th>Nature</th>
                        <th>Statut</th>
                        <th>Soumis le</th>
                        <th class="ci-no-print text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($declarations as $d)
                        @php
                            $reftype = $d->reference ? strtoupper(explode('-', $d->reference)[0] ?? '') : '';
                            $isDen = $reftype === 'DEN';
                        @endphp
                        <tr>
                            {{-- Type / Référence --}}
                            <td data-order="{{ $isDen ? 'DEN' : 'AUTO' }} {{ $d->reference }}">
                                <div class="d-flex flex-column gap-1">
                                    @if ($isDen)
                                        <span class="ci-badge ci-badge-ko" style="width:fit-content;">
                                            <i class="bx bx-error-alt"></i> Dénonciation
                                        </span>
                                    @else
                                        <span class="ci-badge ci-badge-info" style="width:fit-content;">
                                            <i class="bx bx-user-check"></i> Auto
                                        </span>
                                    @endif
                                    <span class="ref">{{ $d->reference }}</span>
                                </div>
                            </td>

                            {{-- Collaborateur --}}
                            <td>
                                <div class="cell-main">{{ $d->nom_prenoms }}</div>
                                <div class="cell-sub">
                                    <i class="bx bx-id-card" style="font-size:.7rem;"></i>
                                    {{ $d->matricule }}
                                </div>
                            </td>

                            {{-- Direction --}}
                            <td>
                                <div class="cell-main" style="font-weight:500;font-size:.78rem;">
                                    {{ $d->direction ?? '—' }}
                                </div>
                                @if ($d->departement)
                                    <div class="cell-sub">{{ $d->departement }}</div>
                                @endif
                            </td>

                            {{-- Réponse --}}
                            <td data-order="{{ $d->reponse_q1 }}">
                                @if ($d->reponse_q1 === 'OUI')
                                    <span class="ci-badge ci-badge-ko">
                                        <i class="bx bx-error-circle"></i> Conflit
                                    </span>
                                @else
                                    <span class="ci-badge ci-badge-ok">
                                        <i class="bx bx-check-circle"></i> Aucun
                                    </span>
                                @endif
                            </td>

                            {{-- Nature --}}
                            <td>
                                @if ($d->reponse_q1 === 'OUI' && count($d->nature_labels ?? []) > 0)
                                    @foreach (array_slice($d->nature_labels, 0, 2) as $label)
                                        <span class="ci-tag">{{ $label }}</span>
                                    @endforeach
                                    @if (count($d->nature_labels) > 2)
                                        <span class="ci-tag"
                                            title="{{ implode(', ', array_slice($d->nature_labels, 2)) }}">
                                            +{{ count($d->nature_labels) - 2 }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-muted" style="font-size:.72rem;">—</span>
                                @endif
                            </td>

                            {{-- Statut --}}
                            <td data-order="{{ $d->statut }}">
                                @switch($d->statut)
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

                                    @default
                                        <span class="ci-badge ci-badge-info">{{ $d->statut }}</span>
                                @endswitch
                            </td>

                            {{-- Date --}}
                            <td data-order="{{ $d->date_soumission?->timestamp }}">
                                <div class="cell-main" style="font-size:.78rem;">
                                    {{ $d->date_soumission?->format('d/m/Y') }}
                                </div>
                                <div class="cell-sub">
                                    {{ $d->date_soumission?->format('H:i') }}
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="ci-no-print text-end">
                                <a href="{{ route('admin.conflits.show', $d) }}" class="ci-action-btn"
                                    title="Voir le détail">
                                    <i class="bx bx-show"></i> Voir
                                </a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div style="padding:3rem 1rem;text-align:center;color:var(--rh-muted);">
                                        <i class="bx bx-folder-open"
                                            style="font-size:3rem;color:#cbd5e1;display:block;margin-bottom:.75rem;"></i>
                                        <div style="font-weight:600;color:var(--rh-primary);margin-bottom:.5rem;">Aucune
                                            déclaration trouvée</div>
                                        @if (array_filter($filters))
                                            <div style="font-size:.85rem;margin-bottom:.75rem;">Aucun résultat ne correspond à
                                                vos filtres.</div>
                                            <a href="{{ route('admin.conflits.index') }}" class="ci-export-btn">
                                                <i class="bx bx-x"></i> Réinitialiser les filtres
                                            </a>
                                        @else
                                            <div style="font-size:.85rem;">Aucune déclaration n'a encore été soumise.</div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    @endsection

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    @endpush

    {{-- @push('scripts') --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof jQuery === 'undefined' || !jQuery.fn.DataTable) {
                console.warn('DataTables non chargé');
                return;
            }

            const dt = jQuery('#declarationsTable').DataTable({
                language: {
                    processing:     "Traitement en cours...",
                    search:         "Rechercher&nbsp;:",
                    lengthMenu:     "Afficher _MENU_ éléments",
                    info:           "Affichage de _START_ à _END_ sur _TOTAL_",
                    infoEmpty:      "0 élément",
                    infoFiltered:   "(sur _MAX_)",
                    zeroRecords:    "Aucun résultat",
                    emptyTable:     "Aucune donnée",
                    paginate: {
                        first: "«", previous: "‹",
                        next: "›", last: "»"
                    }
                },
                pageLength: 20,
                lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, "Tout"]],
                order: [[6, 'desc']],
                responsive: true,
                autoWidth: false,
                stateSave: true,
                stateDuration: 60 * 60 * 24,
                columnDefs: [
                    { targets: [7], orderable: false, searchable: false }
                ],
                dom: 't',  // uniquement le tableau — les contrôles sont dans notre toolbar
                buttons: []  // pas de boutons DataTables, on gère les exports via JS
            });

            // Tri personnalisé par défaut sur la date (colonne 6 = Soumis le)
            dt.order([6, 'desc']).draw();

            // Fonction d'export
            window.exportTable = function (type) {
                const table = jQuery('#declarationsTable').DataTable();
                const data  = table.rows({ search: 'applied' }).data();
                const headers = ['Type / Réf.', 'Collaborateur', 'Direction', 'Réponse', 'Nature', 'Statut', 'Soumis le'];

                // Reconstruire les lignes depuis les données de la vue
                const rows = [];
                jQuery('#declarationsTable tbody tr').each(function () {
                    const $tr = jQuery(this);
                    if ($tr.find('td').length < 7) return;
                    const row = [];
                    $tr.find('td').each(function (i) {
                        if (i >= 7) return;
                        row.push(jQuery(this).text().trim().replace(/\s+/g, ' '));
                    });
                    rows.push(row);
                });

                if (type === 'csv') {
                    let csv = headers.join(';') + '\n';
                    rows.forEach(r => csv += r.map(c => `"${c}"`).join(';') + '\n');
                    const blob = new Blob(['\ufeff' + csv], { type: 'text/csv;charset=utf-8;' });
                    downloadBlob(blob, 'declarations.csv');
                } else if (type === 'excel') {
                    let html = '<table><thead><tr>' + headers.map(h => `<th>${h}</th>`).join('') + '</tr></thead><tbody>';
                    rows.forEach(r => html += '<tr>' + r.map(c => `<td>${c}</td>`).join('') + '</tr>');
                    html += '</tbody></table>';
                    const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
                    downloadBlob(blob, 'declarations.xls');
                } else if (type === 'pdf') {
                    // Ouvre la fenêtre d'impression — Ctrl+P → PDF
                    window.print();
                }
            };

            function downloadBlob(blob, filename) {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }
        });
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery === 'undefined' || !jQuery.fn.DataTable) {
                console.warn('DataTables non chargé');
                return;
            }

            const dt = jQuery('#declarationsTable').DataTable({
                language: {
                    processing: "Traitement en cours...",
                    search: "Rechercher&nbsp;:",
                    lengthMenu: "Afficher _MENU_ éléments",
                    info: "Affichage de _START_ à _END_ sur _TOTAL_",
                    infoEmpty: "0 élément",
                    infoFiltered: "(sur _MAX_)",
                    zeroRecords: "Aucun résultat",
                    emptyTable: "Aucune donnée",
                    paginate: {
                        first: "«",
                        previous: "‹",
                        next: "›",
                        last: "»"
                    }
                },
                pageLength: 20,
                lengthMenu: [
                    [10, 20, 50, 100, -1],
                    [10, 20, 50, 100, "Tout"]
                ],
                order: [
                    [6, 'desc']
                ],
                responsive: true,
                autoWidth: false,
                stateSave: true,
                stateDuration: 60 * 60 * 24,
                columnDefs: [{
                    targets: [7],
                    orderable: false,
                    searchable: false
                }],
                dom: 't',
                buttons: []
            });

            dt.order([6, 'desc']).draw();

            // ============================================================
            // NOM DE FICHIER COMMUN : conformites_declaration_AAAA_MM_JJ
            // ============================================================
            function buildFilename() {
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                return `conformites_declaration_${yyyy}_${mm}_${dd}`;
            }

            // ============================================================
            // EXPORT — CSV / EXCEL / PDF
            // ============================================================
            window.exportTable = function(type) {
                const headers = ['Type / Réf.', 'Collaborateur', 'Direction', 'Réponse', 'Nature', 'Statut',
                    'Soumis le'
                ];

                // On reconstruit les lignes visibles (filtres + tri appliqués)
                const rows = [];
                jQuery('#declarationsTable tbody tr').each(function() {
                    const $tr = jQuery(this);
                    if ($tr.find('td').length < 7) return;
                    const row = [];
                    $tr.find('td').each(function(i) {
                        if (i >= 7) return;
                        row.push(jQuery(this).text().trim().replace(/\s+/g, ' '));
                    });
                    rows.push(row);
                });

                const baseName = buildFilename(); // ex: conformites_declaration_2026_10_22

                if (type === 'csv') {
                    let csv = headers.join(';') + '\n';
                    rows.forEach(r => csv += r.map(c => `"${c}"`).join(';') + '\n');

                    const blob = new Blob(['\ufeff' + csv], {
                        type: 'text/csv;charset=utf-8;'
                    });
                    downloadBlob(blob, `${baseName}.csv`);

                } else if (type === 'excel') {
                    let html = '<table><thead><tr>' + headers.map(h => `<th>${h}</th>`).join('') +
                        '</tr></thead><tbody>';
                    rows.forEach(r => html += '<tr>' + r.map(c => `<td>${c}</td>`).join('') + '</tr>');
                    html += '</tbody></table>';

                    const blob = new Blob([html], {
                        type: 'application/vnd.ms-excel'
                    });
                    downloadBlob(blob, `${baseName}.xls`);

                } else if (type === 'pdf') {
                    // Impression → l'utilisateur choisit "Enregistrer en PDF"
                    // ⚠️ Le nom proposé par le navigateur sera basé sur le <title> de la page
                    // Pour forcer un nom, on modifie temporairement le <title>
                    const originalTitle = document.title;
                    document.title = baseName;

                    window.print();

                    // Restaure le titre après un court délai
                    setTimeout(() => {
                        document.title = originalTitle;
                    }, 500);
                }
            };

            // Téléchargement générique (blob → fichier)
            function downloadBlob(blob, filename) {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }
        });
    </script>
    {{-- @endpush --}}
