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

        .ci-card {
            background: #fff; border: 1px solid var(--rh-border);
            border-radius: var(--rh-radius); overflow: hidden;
            box-shadow: 0 1px 3px rgba(15, 23, 42, .04); margin-bottom: 1.25rem;
        }
        .ci-card-header {
            background: linear-gradient(90deg, #f8fafc 0%, #fff 100%);
            border-bottom: 1px solid var(--rh-border);
            padding: .95rem 1.35rem;
        }
        .ci-card-header h6 {
            color: var(--rh-primary); font-weight: 700;
            text-transform: uppercase; font-size: .78rem;
            letter-spacing: .06em; display: flex; align-items: center; margin: 0;
        }
        .ci-card-header h6 i { color: var(--rh-gold); font-size: 1.2rem; margin-right: .55rem; }
        .ci-card-body { padding: 1.35rem 1.5rem; }

        .ci-info-row {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding: .7rem 0; border-bottom: 1px dashed var(--rh-border); gap: 1rem;
        }
        .ci-info-row:last-child { border-bottom: none; }
        .ci-info-label { font-size: .78rem; color: var(--rh-muted); font-weight: 600; flex-shrink: 0; }
        .ci-info-value { font-size: .85rem; color: #1e293b; font-weight: 600; text-align: right; word-break: break-word; }

        .ci-badge {
            font-size: .72rem; font-weight: 600;
            padding: .3rem .7rem; border-radius: 50rem;
            display: inline-flex; align-items: center; gap: .35rem; white-space: nowrap;
        }
        .ci-badge-ok    { background: rgba(25, 135, 84, .1);   color: var(--rh-success); border: 1px solid rgba(25, 135, 84, .25); }
        .ci-badge-ko    { background: rgba(220, 53, 69, .08);  color: var(--rh-danger);  border: 1px solid rgba(220, 53, 69, .2); }
        .ci-badge-warn  { background: rgba(249, 178, 51, .15); color: #b97d0c;          border: 1px solid rgba(249, 178, 51, .4); }
        .ci-badge-muted { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }

        .ci-honneur {
            background: linear-gradient(135deg, var(--rh-primary-light) 0%, #fff 100%);
            border-left: 4px solid var(--rh-primary);
            border-radius: 12px;
            padding: 1.15rem 1.35rem;
            font-size: .9rem; color: #334155;
            line-height: 1.7; white-space: pre-wrap;
        }

        .ci-progress {
            height: 8px; background: #e5e9ef;
            border-radius: 50rem; overflow: hidden;
        }
        .ci-progress-bar {
            height: 100%; border-radius: 50rem;
            background: linear-gradient(90deg, var(--rh-gold) 0%, var(--rh-primary) 100%);
            transition: width .3s ease;
        }

        .ci-btn-primary {
            background: var(--rh-primary); color: #fff;
            border: 1px solid var(--rh-primary);
            font-weight: 700; padding: .75rem 1.5rem;
            border-radius: 10px; transition: all .2s ease;
            font-size: .9rem; width: 100%;
            display: inline-flex; align-items: center; justify-content: center; gap: .35rem;
        }
        .ci-btn-primary:hover { background: var(--rh-primary-dark); color: #fff; transform: translateY(-1px); }

        .ci-btn-danger {
            background: var(--rh-danger); color: #fff;
            border: 1px solid var(--rh-danger);
            font-weight: 700; padding: .75rem 1.5rem;
            border-radius: 10px; transition: all .2s ease;
            font-size: .9rem; width: 100%;
            display: inline-flex; align-items: center; justify-content: center; gap: .35rem;
        }
        .ci-btn-danger:hover { background: #b02a37; color: #fff; transform: translateY(-1px); }

        .ci-btn-outline {
            background: #fff; color: var(--rh-muted);
            border: 1px solid var(--rh-border);
            font-weight: 500; padding: .55rem 1.1rem;
            border-radius: 10px; transition: all .2s ease;
            text-decoration: none; display: inline-flex;
            align-items: center; gap: .35rem; font-size: .85rem;
        }
        .ci-btn-outline:hover { background: #f8fafc; color: #334155; }

        @media (max-width: 768px) {
            .ci-hero { padding: 1.25rem 1.35rem; }
            .ci-hero h1 { font-size: 1.05rem; }
        }
    </style>

    @php
        $formaterDate      = fn($d) => $d ? \Carbon\Carbon::parse($d)->format('d/m/Y') : '—';
        $formaterDateHeure = fn($d) => $d ? \Carbon\Carbon::parse($d)->format('d/m/Y à H:i') : '—';

        $taux = $stats['total_agents'] > 0
            ? round(($stats['declarations'] / $stats['total_agents']) * 100)
            : 0;
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
                            <a href="{{ route('admin.conflits.campagnes.index') }}" class="text-decoration-none" style="color: var(--rh-primary);">
                                Campagnes
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-muted" style="font-size: .85rem;">
                            {{ $campagne->libelle }}
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
                        {{ $campagne->libelle }}
                    </h1>
                    <p>
                        Année {{ $campagne->annee }} —
                        {{ $campagne->statut_label }}
                    </p>
                </div>
                <a href="{{ route('admin.conflits.campagnes.index') }}" class="ci-btn-outline">
                    <i class="bx bx-arrow-back"></i> Retour
                </a>
            </div>
        </div>

        {{-- MESSAGES --}}
        @if (session('success'))
            <div class="alert alert-success mb-4 border-0 rounded-3">
                <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">

            {{-- COLONNE GAUCHE --}}
            <div class="col-lg-8">

                {{-- DÉTAIL --}}
                <div class="ci-card">
                    <div class="ci-card-header">
                        <h6><i class="bx bx-info-circle"></i> Détail de la campagne</h6>
                    </div>
                    <div class="ci-card-body">

                        <div class="ci-info-row">
                            <span class="ci-info-label">Libellé</span>
                            <span class="ci-info-value">{{ $campagne->libelle }}</span>
                        </div>
                        <div class="ci-info-row">
                            <span class="ci-info-label">Année</span>
                            <span class="ci-info-value">{{ $campagne->annee }}</span>
                        </div>
                        <div class="ci-info-row">
                            <span class="ci-info-label">Statut</span>
                            <span class="ci-info-value">
                                <span class="ci-badge ci-badge-{{ $campagne->statut_color }}">
                                    <i class="bx {{ $campagne->statut_icon }}"></i>
                                    {{ $campagne->statut_label }}
                                </span>
                            </span>
                        </div>
                        <div class="ci-info-row">
                            <span class="ci-info-label">Date d'ouverture</span>
                            <span class="ci-info-value">{{ $formaterDate($campagne->date_ouverture) }}</span>
                        </div>
                        <div class="ci-info-row">
                            <span class="ci-info-label">Date de clôture</span>
                            <span class="ci-info-value">{{ $formaterDate($campagne->date_cloture) }}</span>
                        </div>
                        <div class="ci-info-row">
                            <span class="ci-info-label">Créée par</span>
                            <span class="ci-info-value">{{ $campagne->auteur?->name ?? '—' }}</span>
                        </div>
                        @if ($campagne->ouverte_le)
                            <div class="ci-info-row">
                                <span class="ci-info-label">Ouverte le</span>
                                <span class="ci-info-value">{{ $formaterDateHeure($campagne->ouverte_le) }}</span>
                            </div>
                        @endif
                        @if ($campagne->cloturee_le)
                            <div class="ci-info-row">
                                <span class="ci-info-label">Clôturée le</span>
                                <span class="ci-info-value">{{ $formaterDateHeure($campagne->cloturee_le) }}</span>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- MESSAGE --}}
                @if ($campagne->message)
                    <div class="ci-card">
                        <div class="ci-card-header">
                            <h6><i class="bx bx-chat"></i> Message aux agents</h6>
                        </div>
                        <div class="ci-card-body">
                            <div class="ci-honneur">{{ $campagne->message }}</div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- COLONNE DROITE --}}
            <div class="col-lg-4">

                {{-- PARTICIPATION --}}
                <div class="ci-card">
                    <div class="ci-card-header">
                        <h6><i class="bx bx-bar-chart"></i> Participation</h6>
                    </div>
                    <div class="ci-card-body">
                        <div class="ci-info-row">
                            <span class="ci-info-label">Agents concernés</span>
                            <span class="ci-info-value">{{ $stats['total_agents'] }}</span>
                        </div>
                        <div class="ci-info-row">
                            <span class="ci-info-label">Déclarations</span>
                            <span class="ci-info-value">{{ $stats['declarations'] }}</span>
                        </div>
                        <div class="ci-info-row">
                            <span class="ci-info-label">En attente</span>
                            <span class="ci-info-value">{{ $stats['en_attente'] }}</span>
                        </div>
                        <div class="ci-info-row">
                            <span class="ci-info-label">Validées</span>
                            <span class="ci-info-value">{{ $stats['validees'] }}</span>
                        </div>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-1" style="font-size:.78rem; color: var(--rh-muted); font-weight:600;">
                                <span>Taux de participation</span>
                                <span>{{ $taux }} %</span>
                            </div>
                            <div class="ci-progress">
                                <div class="ci-progress-bar" style="width:{{ $taux }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="ci-card">
                    <div class="ci-card-header">
                        <h6><i class="bx bx-cog"></i> Actions</h6>
                    </div>
                    <div class="ci-card-body">
                        @if ($campagne->estEnPreparation())
                            <form method="POST" action="{{ route('admin.conflits.campagnes.ouvrir', $campagne) }}"
                                  onsubmit="return confirm('Ouvrir cette campagne et notifier tous les agents ?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="ci-btn-primary">
                                    <i class="bx bx-send"></i> Ouvrir & notifier
                                </button>
                            </form>
                        @elseif ($campagne->estOuverte())
                            <form method="POST" action="{{ route('admin.conflits.campagnes.cloturer', $campagne) }}"
                                  onsubmit="return confirm('Clôturer cette campagne ?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="ci-btn-danger">
                                    <i class="bx bx-stop-circle"></i> Clôturer
                                </button>
                            </form>
                        @else
                            <div class="text-center" style="color: var(--rh-muted); font-size: .85rem; padding: 1rem 0;">
                                <i class="bx bx-lock" style="font-size: 1.75rem;"></i>
                                <p class="mb-0 mt-2">Cette campagne est clôturée.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection
