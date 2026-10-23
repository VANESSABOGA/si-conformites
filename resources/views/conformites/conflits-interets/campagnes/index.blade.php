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
            --rh-warning: #f59e0b;
            --rh-border: #e5e9ef;
            --rh-muted: #64748b;
            --rh-radius: 14px;
        }

        .ci-page-title { color: var(--rh-primary); font-weight: 700; }

        /* HERO */
        .ci-hero {
            border-radius: var(--rh-radius);
            padding: 1.5rem 2rem;
            color: #fff;
            background: radial-gradient(circle at 92% 0%, rgba(249, 178, 51, .25) 0, transparent 38%),
                        linear-gradient(135deg, var(--rh-primary) 0%, var(--rh-primary-dark) 100%);
            border-bottom: 4px solid var(--rh-gold);
            box-shadow: 0 10px 30px rgba(3, 63, 31, .12);
        }
        .ci-hero h1 { color: #fff; font-size: 1.25rem; font-weight: 700; margin: 0 0 .35rem; }
        .ci-hero p  { margin: 0; color: rgba(255, 255, 255, .82); font-size: .85rem; }

        .ci-btn-gold {
            background: var(--rh-gold); color: var(--rh-primary);
            border: 1px solid var(--rh-gold);
            font-weight: 700; padding: .6rem 1.35rem;
            border-radius: 10px; transition: all .2s ease;
            font-size: .85rem; text-decoration: none;
            display: inline-flex; align-items: center; gap: .4rem;
        }
        .ci-btn-gold:hover {
            background: var(--rh-gold-hover); color: var(--rh-primary);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(249, 178, 51, .3);
        }

        /* ALERTE CAMPAGNE ACTIVE */
        .ci-alert-success {
            background: linear-gradient(135deg, rgba(25, 135, 84, .08) 0%, #fff 100%);
            border-left: 4px solid var(--rh-success);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
        }
        .ci-alert-success > i { color: var(--rh-success); font-size: 1.5rem; }

        .ci-btn-outline {
            background: #fff; color: var(--rh-muted);
            border: 1px solid var(--rh-border);
            font-weight: 500; padding: .5rem 1rem;
            border-radius: 8px; transition: all .2s ease;
            text-decoration: none; display: inline-flex;
            align-items: center; gap: .35rem;
            font-size: .8rem;
        }
        .ci-btn-outline:hover { background: #f8fafc; color: #334155; }

        /* KPI CARDS */
        .ci-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .ci-kpi {
            background: #fff; border: 1px solid var(--rh-border);
            border-radius: var(--rh-radius); padding: 1.1rem 1.25rem;
            display: flex; align-items: center; gap: .9rem;
            box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
            position: relative; overflow: hidden;
            transition: all .2s ease;
        }
        .ci-kpi::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0;
            width: 4px; background: var(--rh-muted);
        }
        .ci-kpi:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(15, 23, 42, .08); }

        .ci-kpi-icon {
            width: 46px; height: 46px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; flex-shrink: 0;
        }
        .ci-kpi-value { font-size: 1.5rem; font-weight: 800; line-height: 1; color: var(--rh-primary); }
        .ci-kpi-label {
            font-size: .72rem; color: var(--rh-muted); font-weight: 600;
            text-transform: uppercase; letter-spacing: .04em; margin-top: .3rem;
        }

        .ci-kpi.total::before      { background: var(--rh-primary); }
        .ci-kpi.total .ci-kpi-icon { background: var(--rh-primary-light); color: var(--rh-primary); }
        .ci-kpi.ouvertes::before   { background: var(--rh-success); }
        .ci-kpi.ouvertes .ci-kpi-icon { background: rgba(25, 135, 84, .1); color: var(--rh-success); }
        .ci-kpi.preparation::before { background: var(--rh-warning); }
        .ci-kpi.preparation .ci-kpi-icon { background: rgba(245, 158, 11, .12); color: var(--rh-warning); }
        .ci-kpi.cloturees::before  { background: var(--rh-danger); }
        .ci-kpi.cloturees .ci-kpi-icon { background: rgba(220, 53, 69, .08); color: var(--rh-danger); }

        /* TABLEAU */
        .ci-section-title {
            display: flex; align-items: center; gap: .55rem;
            font-size: .95rem; font-weight: 800; color: var(--rh-primary);
            text-transform: uppercase; letter-spacing: .05em;
            margin: 0 0 1rem;
        }
        .ci-section-title i { color: var(--rh-gold); font-size: 1.15rem; }

        .ci-table-wrapper {
            background: #fff; border: 1px solid var(--rh-border);
            border-radius: var(--rh-radius); overflow: hidden;
            box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
        }
        .ci-table { width: 100%; margin: 0; border-collapse: collapse; font-size: .85rem; }
        .ci-table thead {
            background: linear-gradient(90deg, #f8fafc 0%, #fff 100%);
            border-bottom: 2px solid var(--rh-gold);
        }
        .ci-table thead th {
            color: var(--rh-primary); font-weight: 700;
            font-size: .72rem; text-transform: uppercase;
            letter-spacing: .05em; padding: .85rem 1rem;
            text-align: left; white-space: nowrap;
        }
        .ci-table tbody tr { border-bottom: 1px solid var(--rh-border); transition: background .15s ease; }
        .ci-table tbody tr:hover { background: var(--rh-primary-light); }
        .ci-table tbody tr:last-child { border-bottom: none; }
        .ci-table tbody td { padding: .85rem 1rem; vertical-align: middle; color: #334155; }

        .ci-table .campagne-titre { font-weight: 700; color: var(--rh-primary); font-size: .88rem; }
        .ci-table .campagne-message { font-size: .75rem; color: var(--rh-muted); margin-top: .15rem; }

        /* TAGS */
        .ci-tag {
            display: inline-block;
            background: var(--rh-primary-light);
            color: var(--rh-primary);
            border: 1px solid rgba(3, 63, 31, .15);
            font-size: .72rem; font-weight: 600;
            padding: .2rem .6rem; border-radius: 5px;
        }

        /* BADGES */
        .ci-badge {
            font-size: .72rem; font-weight: 600;
            padding: .3rem .7rem; border-radius: 50rem;
            display: inline-flex; align-items: center; gap: .35rem; white-space: nowrap;
        }
        .ci-badge-ok    { background: rgba(25, 135, 84, .1);   color: var(--rh-success); border: 1px solid rgba(25, 135, 84, .25); }
        .ci-badge-ko    { background: rgba(220, 53, 69, .08);  color: var(--rh-danger);  border: 1px solid rgba(220, 53, 69, .2); }
        .ci-badge-warn  { background: rgba(249, 178, 51, .15); color: #b97d0c;          border: 1px solid rgba(249, 178, 51, .4); }
        .ci-badge-muted { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }

        /* BOUTONS ACTION */
        .ci-action-btn {
            background: #fff; color: var(--rh-primary);
            border: 1px solid var(--rh-border);
            font-weight: 600; font-size: .75rem;
            padding: .32rem .75rem; border-radius: 8px;
            text-decoration: none; transition: all .2s ease;
            display: inline-flex; align-items: center; gap: .3rem;
            cursor: pointer;
        }
        .ci-action-btn:hover { background: var(--rh-primary-light); border-color: var(--rh-primary); color: var(--rh-primary); }
        .ci-action-btn.danger { color: var(--rh-danger); border-color: rgba(220, 53, 69, .3); }
        .ci-action-btn.danger:hover { background: rgba(220, 53, 69, .08); border-color: var(--rh-danger); color: var(--rh-danger); }
        .ci-action-btn.primary { background: var(--rh-primary); color: #fff; border-color: var(--rh-primary); }
        .ci-action-btn.primary:hover { background: var(--rh-primary-dark); color: #fff; }

        /* EMPTY */
        .ci-empty { padding: 3rem 1rem; text-align: center; }
        .ci-empty-icon {
            width: 72px; height: 72px; margin: 0 auto 1rem;
            border-radius: 50%; background: var(--rh-primary-light);
            color: var(--rh-primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 2.25rem;
        }
        .ci-empty h5 { color: var(--rh-primary); font-weight: 700; margin-bottom: .5rem; }
        .ci-empty p { color: var(--rh-muted); font-size: .88rem; margin-bottom: 1.25rem; }

        /* PAGINATION */
        .ci-pagination {
            padding: .85rem 1.15rem; border-top: 1px solid var(--rh-border);
            display: flex; justify-content: space-between; align-items: center;
            gap: 1rem; font-size: .78rem; color: var(--rh-muted); flex-wrap: wrap;
        }
        .ci-pagination .pagination { margin: 0; }

        @media (max-width: 768px) {
            .ci-hero { padding: 1.25rem 1.35rem; }
            .ci-hero h1 { font-size: 1.05rem; }
            .ci-table thead th:nth-child(3),
            .ci-table tbody td:nth-child(3),
            .ci-table thead th:nth-child(4),
            .ci-table tbody td:nth-child(4),
            .ci-table thead th:nth-child(6),
            .ci-table tbody td:nth-child(6) { display: none; }
        }
    </style>

    @php
        $formaterDate = fn($d) => $d ? \Carbon\Carbon::parse($d)->format('d/m/Y') : '—';
        $formaterDateHeure = fn($d) => $d ? \Carbon\Carbon::parse($d)->format('d/m/Y à H:i') : '—';

        $campagneActive = \App\Models\Campagne::ouverte()->latest('date_ouverture')->first();

        // ⚠️ CORRECTION : statuts MAJUSCULES
        $totalCampagnes = $campagnes->total();
        $nbOuvertes     = $campagnes->getCollection()->where('statut', 'OUVERTE')->count();
        $nbCloturees    = $campagnes->getCollection()->where('statut', 'CLOTUREE')->count();
        $nbPreparation  = $campagnes->getCollection()->where('statut', 'PREPARATION')->count();
    @endphp

    <div class="page-content">

        {{-- BREADCRUMB --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
            <div class="breadcrumb-title pe-3 ci-page-title">CONFLITS D'INTÉRÊT</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}" class="text-decoration-none" style="color: var(--rh-primary);">
                                <i class="bx bx-home-alt fs-6"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.conflits.index') }}" class="text-decoration-none" style="color: var(--rh-primary);">
                                Conflits d'intérêt
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-muted" style="font-size: .85rem;">
                            Campagnes
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- HERO --}}
        <div class="ci-hero mb-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                <div>
                    <h1 class="d-flex align-items-center gap-2">
                        <i class="bx bx-megaphone" style="color: var(--rh-gold);"></i>
                        Campagnes de déclaration
                    </h1>
                    <p>Ouvrez une campagne pour notifier tous les agents qu'ils peuvent déclarer leurs conflits d'intérêt.</p>
                </div>
                <a href="{{ route('admin.conflits.campagnes.create') }}" class="ci-btn-gold">
                    <i class="bx bx-plus-circle"></i> Nouvelle campagne
                </a>
            </div>
        </div>

        {{-- CAMPAGNE ACTIVE --}}
        @if ($campagneActive)
            <div class="ci-alert-success mb-4">
                <i class="bx bx-broadcast"></i>
                <div class="flex-grow-1">
                    <strong style="color: var(--rh-primary);">Campagne en cours : {{ $campagneActive->libelle }}</strong>
                    <div style="font-size: .82rem; color: var(--rh-muted); margin-top: .25rem;">
                        Ouverte le {{ $formaterDateHeure($campagneActive->date_ouverture) }}
                        @if ($campagneActive->date_cloture)
                            — Clôture prévue le {{ $formaterDate($campagneActive->date_cloture) }}
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.conflits.campagnes.cloturer', $campagneActive) }}"
                      onsubmit="return confirm('Clôturer cette campagne ? Les agents ne pourront plus déclarer.')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="ci-btn-outline">
                        <i class="bx bx-stop-circle"></i> Clôturer
                    </button>
                </form>
            </div>
        @endif

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
        <div class="ci-kpi-grid">
            <div class="ci-kpi total">
                <div class="ci-kpi-icon"><i class="bx bx-collection"></i></div>
                <div>
                    <div class="ci-kpi-value">{{ $totalCampagnes }}</div>
                    <div class="ci-kpi-label">Total campagnes</div>
                </div>
            </div>
            <div class="ci-kpi ouvertes">
                <div class="ci-kpi-icon"><i class="bx bx-broadcast"></i></div>
                <div>
                    <div class="ci-kpi-value">{{ $nbOuvertes }}</div>
                    <div class="ci-kpi-label">Ouvertes</div>
                </div>
            </div>
            <div class="ci-kpi preparation">
                <div class="ci-kpi-icon"><i class="bx bx-pencil"></i></div>
                <div>
                    <div class="ci-kpi-value">{{ $nbPreparation }}</div>
                    <div class="ci-kpi-label">En préparation</div>
                </div>
            </div>
            <div class="ci-kpi cloturees">
                <div class="ci-kpi-icon"><i class="bx bx-check2-circle"></i></div>
                <div>
                    <div class="ci-kpi-value">{{ $nbCloturees }}</div>
                    <div class="ci-kpi-label">Clôturées</div>
                </div>
            </div>
        </div>

        {{-- LISTE --}}
        <div class="ci-section-title">
            <i class="bx bx-list-ul"></i>
            Historique des campagnes
        </div>

        @if ($campagnes->isEmpty())
            <div class="ci-table-wrapper">
                <div class="ci-empty">
                    <div class="ci-empty-icon">
                        <i class="bx bx-megaphone"></i>
                    </div>
                    <h5>Aucune campagne pour le moment</h5>
                    <p>Créez votre première campagne pour inviter les agents à déclarer leurs conflits d'intérêt.</p>
                    <a href="{{ route('admin.conflits.campagnes.create') }}" class="ci-btn-gold">
                        <i class="bx bx-plus-circle"></i> Créer une campagne
                    </a>
                </div>
            </div>
        @else
            <div class="ci-table-wrapper">
                <table class="ci-table">
                    <thead>
                        <tr>
                            <th>Libellé</th>
                            <th>Année</th>
                            <th>Ouverture</th>
                            <th>Clôture</th>
                            <th>Statut</th>
                            <th>Créée par</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($campagnes as $campagne)
                            <tr>
                                <td>
                                    <div class="campagne-titre">{{ $campagne->libelle }}</div>
                                    @if ($campagne->message)
                                        <div class="campagne-message">{{ Str::limit($campagne->message, 80) }}</div>
                                    @endif
                                </td>
                                <td><span class="ci-tag">{{ $campagne->annee }}</span></td>
                                <td>{{ $formaterDate($campagne->date_ouverture) }}</td>
                                <td>{{ $formaterDate($campagne->date_cloture) }}</td>

                                {{-- STATUT avec accesseurs --}}
                                <td>
                                    <span class="ci-badge ci-badge-{{ $campagne->statut_color }}">
                                        <i class="bx {{ $campagne->statut_icon }}"></i>
                                        {{ $campagne->statut_label }}
                                    </span>
                                </td>

                                <td style="color: var(--rh-muted); font-size: .8rem;">
                                    {{ $campagne->auteur->name ?? '—' }}
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1 flex-wrap">

                                        {{-- Bouton Ouvrir (PREPARATION) --}}
                                        @if ($campagne->estEnPreparation())
                                            <form method="POST" action="{{ route('admin.conflits.campagnes.ouvrir', $campagne) }}"
                                                  onsubmit="return confirm('Ouvrir cette campagne et notifier tous les agents ?')"
                                                  style="display:inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="ci-action-btn primary">
                                                    <i class="bx bx-send"></i> Ouvrir
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Bouton Clôturer (OUVERTE) --}}
                                        @if ($campagne->estOuverte())
                                            <form method="POST" action="{{ route('admin.conflits.campagnes.cloturer', $campagne) }}"
                                                  onsubmit="return confirm('Clôturer cette campagne ?')"
                                                  style="display:inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="ci-action-btn danger">
                                                    <i class="bx bx-stop-circle"></i> Clôturer
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('admin.conflits.campagnes.show', $campagne) }}"
                                           class="ci-action-btn" title="Voir le détail">
                                            <i class="bx bx-show"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($campagnes->hasPages())
                    <div class="ci-pagination">
                        <div>
                            Affichage de <strong>{{ $campagnes->firstItem() }}</strong>
                            à <strong>{{ $campagnes->lastItem() }}</strong>
                            sur <strong>{{ $campagnes->total() }}</strong>
                        </div>
                        <div>{{ $campagnes->links() }}</div>
                    </div>
                @endif
            </div>
        @endif

    </div>

@endsection
