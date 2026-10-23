@extends('layouts.main')

@section('content')

    @include('conflits.partials._styles')

    {{-- @php
        $formaterDateHeure = fn($d) => $d ? \Carbon\Carbon::parse($d)->format('d/m/Y à H:i') : '—';

        $total = $declarations->total();
        $attente = 0;
        $validees = 0;
        $rejetees = 0;

        foreach ($declarations as $d) {
            if (in_array($d->statut, ['SOUMISE', 'MODIFIEE'])) {
                $attente++;
            } elseif ($d->statut === 'VALIDEE') {
                $validees++;
            } elseif ($d->statut === 'REJETEE') {
                $rejetees++;
            }
        }
    @endphp --}}

    @php
        /* ---------------------------------------------------------------
     | Helper de formatage
     --------------------------------------------------------------- */
        $formaterDateHeure = fn($d) => $d ? \Carbon\Carbon::parse($d)->format('d/m/Y à H:i') : '—';

        /* ---------------------------------------------------------------
     | Compteurs globaux
     --------------------------------------------------------------- */
        $total = $declarations->total();
        $attente = 0;
        $validees = 0;
        $rejetees = 0;

        /* ---------------------------------------------------------------
     | Compteurs par type (via référence)
     --------------------------------------------------------------- */
        $totalAuto = 0;
        $totalDen = 0;

        $autoAttente = 0;
        $autoValidees = 0;
        $autoRejetees = 0;

        $denAttente = 0;
        $denValidees = 0;
        $denRejetees = 0;

        /* ---------------------------------------------------------------
     | Boucle sur les déclarations de la page courante
     --------------------------------------------------------------- */
        foreach ($declarations as $d) {
            // Détermination du type depuis la référence
            $reftype = $d->reference ? strtoupper(explode('-', $d->reference)[0] ?? '') : '';
            $isDen = $reftype === 'DEN';
            $isAuto = !$isDen;

            // Compteurs globaux
            if (in_array($d->statut, ['SOUMISE', 'MODIFIEE'])) {
                $attente++;
            } elseif ($d->statut === 'VALIDEE') {
                $validees++;
            } elseif ($d->statut === 'REJETEE') {
                $rejetees++;
            }

            // Compteurs par type
            if ($isDen) {
                $totalDen++;
                if (in_array($d->statut, ['SOUMISE', 'MODIFIEE'])) {
                    $denAttente++;
                } elseif ($d->statut === 'VALIDEE') {
                    $denValidees++;
                } elseif ($d->statut === 'REJETEE') {
                    $denRejetees++;
                }
            } else {
                $totalAuto++;
                if (in_array($d->statut, ['SOUMISE', 'MODIFIEE'])) {
                    $autoAttente++;
                } elseif ($d->statut === 'VALIDEE') {
                    $autoValidees++;
                } elseif ($d->statut === 'REJETEE') {
                    $autoRejetees++;
                }
            }
        }
    @endphp


    <div class="page-content">

        {{-- BREADCRUMB --}}
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
                        <li class="breadcrumb-item active text-muted" style="font-size: .85rem;">
                            Mes déclarations de conflits d'intérêts
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
                    <h1><i class="bx bx-shield-quarter"></i> Mes déclarations de conflits d'intérêts</h1>
                    <p>Retrouvez ici l'historique de vos déclarations et leur statut de traitement.</p>
                </div>
                <div>
                    <a href="{{ route('conflits.create') }}" class="ci-btn ci-btn-gold">
                        <i class="bx bx-plus"></i> Nouvelle déclaration
                    </a>
                </div>
            </div>
        </div>

        {{-- MESSAGES --}}
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

        {{-- KPI --}}
        <div class="ci-kpi-grid"
            style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
            <div class="ci-kpi total"
                style="background:#fff;border:1px solid var(--rh-border);border-radius:var(--rh-radius);padding:1.1rem 1.25rem;display:flex;align-items:center;gap:.9rem;box-shadow:0 1px 3px rgba(15,23,42,.04);">
                <div class="ci-kpi-icon"
                    style="width:42px;height:42px;border-radius:10px;background:var(--rh-primary-light);color:var(--rh-primary);display:flex;align-items:center;justify-content:center;font-size:1.35rem;">
                    <i class="bx bx-list-ul"></i>
                </div>
                <div>
                    <div style="font-size:1.4rem;font-weight:800;color:var(--rh-primary);line-height:1.1;">
                        {{ $total }}</div>
                    <div
                        style="font-size:.72rem;color:var(--rh-muted);font-weight:600;text-transform:uppercase;letter-spacing:.04em;">
                        Total</div>
                </div>
            </div>

            {{-- <div class="ci-kpi attente"
                style="background:#fff;border:1px solid var(--rh-border);border-radius:var(--rh-radius);padding:1.1rem 1.25rem;display:flex;align-items:center;gap:.9rem;box-shadow:0 1px 3px rgba(15,23,42,.04);">
                <div class="ci-kpi-icon"
                    style="width:42px;height:42px;border-radius:10px;background:rgba(13,110,253,.08);color:#0d6efd;display:flex;align-items:center;justify-content:center;font-size:1.35rem;">
                    <i class="bx bx-time-five"></i>
                </div>
                <div>
                    <div style="font-size:1.4rem;font-weight:800;color:var(--rh-primary);line-height:1.1;">
                        {{ $attente }}</div>
                    <div
                        style="font-size:.72rem;color:var(--rh-muted);font-weight:600;text-transform:uppercase;letter-spacing:.04em;">
                        En attente</div>
                </div>
            </div> --}}

            {{-- <div class="ci-kpi validees"
                style="background:#fff;border:1px solid var(--rh-border);border-radius:var(--rh-radius);padding:1.1rem 1.25rem;display:flex;align-items:center;gap:.9rem;box-shadow:0 1px 3px rgba(15,23,42,.04);">
                <div class="ci-kpi-icon"
                    style="width:42px;height:42px;border-radius:10px;background:rgba(25,135,84,.1);color:#198754;display:flex;align-items:center;justify-content:center;font-size:1.35rem;">
                    <i class="bx bx-check-double"></i>
                </div>
                <div>
                    <div style="font-size:1.4rem;font-weight:800;color:var(--rh-primary);line-height:1.1;">
                        {{ $validees }}</div>
                    <div
                        style="font-size:.72rem;color:var(--rh-muted);font-weight:600;text-transform:uppercase;letter-spacing:.04em;">
                        Validées</div>
                </div>
            </div> --}}

            {{-- <div class="ci-kpi rejetees"
                style="background:#fff;border:1px solid var(--rh-border);border-radius:var(--rh-radius);padding:1.1rem 1.25rem;display:flex;align-items:center;gap:.9rem;box-shadow:0 1px 3px rgba(15,23,42,.04);">
                <div class="ci-kpi-icon"
                    style="width:42px;height:42px;border-radius:10px;background:rgba(220,53,69,.08);color:#dc3545;display:flex;align-items:center;justify-content:center;font-size:1.35rem;">
                    <i class="bx bx-x-circle"></i>
                </div>
                <div>
                    <div style="font-size:1.4rem;font-weight:800;color:var(--rh-primary);line-height:1.1;">
                        {{ $rejetees }}</div>
                    <div
                        style="font-size:.72rem;color:var(--rh-muted);font-weight:600;text-transform:uppercase;letter-spacing:.04em;">
                        Rejetées</div>
                </div>
            </div> --}}
            <div class="ci-kpi-type ci-kpi-type-auto">
                <div class="ci-kpi-type-header">
                    <div class="ci-kpi-type-icon">
                        <i class="bx bx-user-check"></i>
                    </div>
                    <div>
                        <div class="ci-kpi-type-title">Auto-déclarations</div>
                        <div class="ci-kpi-type-total">{{ $totalAuto }}</div>
                    </div>
                </div>
            </div>

            <div class="ci-kpi-type ci-kpi-type-den">
                <div class="ci-kpi-type-header">
                    <div class="ci-kpi-type-icon">
                        <i class="bx bx-error-alt"></i>
                    </div>
                    <div>
                        <div class="ci-kpi-type-title">Dénonciations</div>
                        <div class="ci-kpi-type-total">{{ $totalDen }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="ci-kpi-type-grid">


            <div class="ci-kpi-type ci-kpi-type-auto">
                <div class="ci-kpi-type-header">
                    <div class="ci-kpi-type-icon">
                        <i class="bx bx-user-check"></i>
                    </div>
                    <div>
                        <div class="ci-kpi-type-title">Auto-déclarations</div>
                        <div class="ci-kpi-type-total">{{ $totalAuto }} au total</div>
                    </div>
                </div>
                <div class="ci-kpi-type-body">
                    <div class="ci-kpi-type-stat">
                        <span class="ci-kpi-type-dot ci-dot-info"></span>
                        <span class="ci-kpi-type-label">En attente</span>
                        <strong>{{ $autoAttente }}</strong>
                    </div>
                    <div class="ci-kpi-type-stat">
                        <span class="ci-kpi-type-dot ci-dot-ok"></span>
                        <span class="ci-kpi-type-label">Validées</span>
                        <strong>{{ $autoValidees }}</strong>
                    </div>
                    <div class="ci-kpi-type-stat">
                        <span class="ci-kpi-type-dot ci-dot-ko"></span>
                        <span class="ci-kpi-type-label">Rejetées</span>
                        <strong>{{ $autoRejetees }}</strong>
                    </div>
                </div>
            </div>


            <div class="ci-kpi-type ci-kpi-type-den">
                <div class="ci-kpi-type-header">
                    <div class="ci-kpi-type-icon">
                        <i class="bx bx-error-alt"></i>
                    </div>
                    <div>
                        <div class="ci-kpi-type-title">Dénonciations</div>
                        <div class="ci-kpi-type-total">{{ $totalDen }} au total</div>
                    </div>
                </div>
                <div class="ci-kpi-type-body">
                    <div class="ci-kpi-type-stat">
                        <span class="ci-kpi-type-dot ci-dot-info"></span>
                        <span class="ci-kpi-type-label">En attente</span>
                        <strong>{{ $denAttente }}</strong>
                    </div>
                    <div class="ci-kpi-type-stat">
                        <span class="ci-kpi-type-dot ci-dot-ok"></span>
                        <span class="ci-kpi-type-label">Validées</span>
                        <strong>{{ $denValidees }}</strong>
                    </div>
                    <div class="ci-kpi-type-stat">
                        <span class="ci-kpi-type-dot ci-dot-ko"></span>
                        <span class="ci-kpi-type-label">Rejetées</span>
                        <strong>{{ $denRejetees }}</strong>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- TOOLBAR --}}
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3 flex-wrap">
            <div
                style="font-size:.9rem;font-weight:700;color:var(--rh-primary);text-transform:uppercase;letter-spacing:.04em;display:flex;align-items:center;gap:.5rem;">
                <i class="bx bx-table" style="color:var(--rh-gold);font-size:1.15rem;"></i>
                Mes déclarations
                <small
                    style="font-weight:500;color:var(--rh-muted);font-size:.75rem;text-transform:none;letter-spacing:0;">({{ $declarations->total() }}
                    au total)</small>
            </div>
            <button type="button" onclick="window.print()" class="ci-btn ci-btn-outline">
                <i class="bx bx-printer"></i> Imprimer
            </button>
        </div>

        {{-- TABLEAU --}}
        @if ($declarations->count() > 0)
            <div
                style="background:#fff;border:1px solid var(--rh-border);border-radius:var(--rh-radius);overflow:hidden;box-shadow:0 1px 3px rgba(15,23,42,.04);">
                <table style="width:100%;margin:0;border-collapse:collapse;font-size:.85rem;">
                    <thead
                        style="background:linear-gradient(90deg,#f8fafc 0%,#fff 100%);border-bottom:2px solid var(--rh-gold);">
                        <tr>
                            <th
                                style="color:var(--rh-primary);font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;padding:.85rem 1rem;text-align:left;">
                                Référence</th>
                            <th
                                style="color:var(--rh-primary);font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;padding:.85rem 1rem;text-align:left;">
                                Type</th>
                            <th
                                style="color:var(--rh-primary);font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;padding:.85rem 1rem;text-align:left;">
                                Date</th>
                            <th
                                style="color:var(--rh-primary);font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;padding:.85rem 1rem;text-align:left;">
                                Réponse</th>
                            <th
                                style="color:var(--rh-primary);font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;padding:.85rem 1rem;text-align:left;">
                                Statut</th>
                            <th
                                style="color:var(--rh-primary);font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;padding:.85rem 1rem;text-align:right;">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($declarations as $d)
                            {{-- @php
                                // Déterminer le type à partir de la référence (préfixe avant le tiret)
                                $reftype = $d->reference ? strtoupper(explode('-', $d->reference)[0] ?? '') : '';

                                // Fallback : si la colonne "type" existe en base, on la priorise
                                $type = $d->type ?? null;
                                if (!$type) {
                                    $type = $reftype === 'DEN' ? 'DENONCIATION' : 'AUTO';
                                }

                                $isDen = $type === 'DENONCIATION';

                                // Optionnel : ne pas polluer le modèle, on garde $type localement
                                // (si tu veux vraiment le passer à la vue, utilise $d->setAttribute('type', $type))

                            @endphp --}}

                            @php

                                $reftype = $d->reference ? strtoupper(explode('-', $d->reference)[0] ?? '') : '';

                                $isDen = $reftype === 'DEN';
                                $isAuto = !$isDen;

                                $typeLabel = $isDen ? 'Dénonciation' : 'Auto-déclaration';
                                $typeIcon = $isDen ? 'bx-error-alt' : 'bx-user-check';
                                $typeBadge = $isDen ? 'ci-badge-ko' : 'ci-badge-info';

                                $statutMap = [
                                    'SOUMISE' => [
                                        'label' => 'Soumise',
                                        'icon' => 'bx-send',
                                        'badge' => 'ci-badge-info',
                                    ],
                                    'MODIFIEE' => [
                                        'label' => 'Modifiée',
                                        'icon' => 'bx-edit',
                                        'badge' => 'ci-badge-warn',
                                    ],
                                    'VALIDEE' => [
                                        'label' => 'Validée',
                                        'icon' => 'bx-check-double',
                                        'badge' => 'ci-badge-ok',
                                    ],
                                    'REJETEE' => ['label' => 'Rejetée', 'icon' => 'bx-x', 'badge' => 'ci-badge-ko'],
                                ];
                                $statut = $statutMap[$d->statut] ?? [
                                    'label' => $d->statut,
                                    'icon' => 'bx-info-circle',
                                    'badge' => 'ci-badge-info',
                                ];

                                $peutModifier = $isAuto && in_array($d->statut, ['SOUMISE', 'MODIFIEE']);
                            @endphp

                            <tr style="border-bottom:1px solid var(--rh-border);transition:background .15s ease;">
                                <td style="padding:.8rem 1rem;vertical-align:middle;">
                                    <span class="font-monospace"
                                        style="font-weight:700;color:var(--rh-primary);font-size:.82rem;">
                                        {{ $d->reference ?? '—' }}
                                    </span>
                                </td>
                                {{-- <td style="padding:.8rem 1rem;vertical-align:middle;">
                                    <span class="ci-badge {{ $isDen ? 'ci-badge-ko' : 'ci-badge-info' }}">
                                        <i class="bx {{ $isDen ? 'bx-error-alt' : 'bx-user-check' }}"></i>

                                        {{ $isDen ? 'AUTO' : 'Dénonciation' }}
                                    </span>
                                </td> --}}
                                <td style="padding:.8rem 1rem;vertical-align:middle;">
                                    <span class="ci-badge {{ $typeBadge }}">
                                        <i class="bx {{ $typeIcon }}"></i> {{ $typeLabel }}
                                    </span>
                                </td>

                                <td style="padding:.8rem 1rem;vertical-align:middle;color:#334155;">
                                    {{ $formaterDateHeure($d->date_soumission) }}
                                </td>
                                <td style="padding:.8rem 1rem;vertical-align:middle;">
                                    @if ($d->reponse_q1 === 'OUI')
                                        <span class="ci-badge ci-badge-ko"><i class="bx bx-error-circle"></i>
                                            Conflit</span>
                                    @else
                                        <span class="ci-badge ci-badge-ok"><i class="bx bx-check-circle"></i> Aucun</span>
                                    @endif
                                </td>
                                <td style="padding:.8rem 1rem;vertical-align:middle;">
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
                                <td style="padding:.8rem 1rem;vertical-align:middle;text-align:right;">
                                    <div class="d-flex justify-content-end gap-1 flex-wrap">

                                        {{-- PDF --}}
                                        {{-- <a href="{{ route('conflits.accuse.pdf', $d) }}" class="ci-btn ci-btn-outline"
                                            style="padding:.32rem .75rem;font-size:.75rem;"
                                            title="Accusé de réception PDF">
                                            <i class="bx bx-download"></i>
                                        </a> --}}

                                        {{-- Modifier (uniquement AUTO et statut éditable) --}}
                                        @if (!$isDen && in_array($d->statut, ['SOUMISE', 'MODIFIEE']))
                                            <a href="{{ route('conflits.edit', $d) }}" class="ci-btn ci-btn-outline"
                                                style="padding:.32rem .75rem;font-size:.75rem;" title="Modifier">
                                                <i class="bx bx-edit"></i> Modifier
                                            </a>
                                        @endif

                                        {{-- Voir --}}
                                        <a href="{{ route('conflits.show', $d) }}" class="ci-btn ci-btn-primary"
                                            style="padding:.32rem .75rem;font-size:.75rem;" title="Voir le détail">
                                            <i class="bx bx-show"></i> Voir
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                @if ($declarations->hasPages())
                    <div
                        style="padding:.85rem 1.15rem;border-top:1px solid var(--rh-border);display:flex;justify-content:space-between;align-items:center;gap:1rem;font-size:.78rem;color:var(--rh-muted);flex-wrap:wrap;">
                        <div>
                            Affichage de <strong>{{ $declarations->firstItem() }}</strong>
                            à <strong>{{ $declarations->lastItem() }}</strong>
                            sur <strong>{{ $declarations->total() }}</strong>
                        </div>
                        <div>{{ $declarations->links() }}</div>
                    </div>
                @endif
            </div>
        @else
            {{-- EMPTY STATE --}}
            <div
                style="padding:3rem 1rem;text-align:center;color:var(--rh-muted);background:#fff;border:1px solid var(--rh-border);border-radius:var(--rh-radius);">
                <i class="bx bx-file-blank" style="font-size:3rem;color:#cbd5e1;display:block;margin-bottom:.75rem;"></i>
                <h5 style="color:var(--rh-primary);font-weight:700;margin-bottom:.5rem;">Aucune déclaration pour le moment
                </h5>
                <p style="font-size:.88rem;margin-bottom:1.25rem;">
                    Vous n'avez encore soumis aucune déclaration de conflit d'intérêts.
                </p>
                <a href="{{ route('conflits.create') }}" class="ci-btn ci-btn-gold">
                    <i class="bx bx-plus"></i> Faire ma première déclaration
                </a>
            </div>
        @endif

    </div>

@endsection
