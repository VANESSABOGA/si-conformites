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
            --rh-warning: #b97d0c;
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
        .ci-hero h1 i { color: var(--rh-gold); margin-right: .4rem; }
        .ci-hero p  { margin: 0; color: rgba(255, 255, 255, .85); font-size: .88rem; }

        /* CARDS */
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

        /* FORM */
        .ci-label {
            font-size: .82rem; font-weight: 600;
            color: var(--rh-primary); margin-bottom: .35rem;
            display: block;
        }
        .ci-label .required { color: var(--rh-danger); }
        .ci-label .optional { color: var(--rh-muted); font-weight: 400; font-size: .75rem; }

        .form-control {
            border-radius: 10px; padding: .65rem .9rem;
            border: 1px solid var(--rh-border);
            font-size: .88rem;
        }
        .form-control:focus {
            border-color: var(--rh-primary);
            box-shadow: 0 0 0 .2rem var(--rh-primary-light);
        }

        .form-help {
            font-size: .75rem; color: var(--rh-muted);
            margin-top: .35rem; display: block;
        }

        /* BOUTONS */
        .ci-btn-gold {
            background: var(--rh-gold); color: var(--rh-primary);
            border: 1px solid var(--rh-gold);
            font-weight: 700; padding: .65rem 1.35rem;
            border-radius: 10px; transition: all .2s ease;
            font-size: .88rem; text-decoration: none;
            display: inline-flex; align-items: center; justify-content: center;
            gap: .4rem; width: 100%;
        }
        .ci-btn-gold:hover {
            background: var(--rh-gold-hover); color: var(--rh-primary);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(249, 178, 51, .3);
        }

        .ci-btn-outline {
            background: #fff; color: var(--rh-muted);
            border: 1px solid var(--rh-border);
            font-weight: 500; padding: .65rem 1.15rem;
            border-radius: 10px; transition: all .2s ease;
            text-decoration: none; display: inline-flex;
            align-items: center; justify-content: center;
            gap: .35rem; font-size: .85rem; width: 100%;
        }
        .ci-btn-outline:hover { background: #f8fafc; color: #334155; }

        /* ALERT */
        .ci-alert-danger {
            background: rgba(220, 53, 69, .08);
            border-left: 4px solid var(--rh-danger);
            border-radius: 10px; padding: 1rem 1.25rem;
            color: #842029; margin-bottom: 1rem;
        }
        .ci-alert-danger ul { margin: .5rem 0 0; padding-left: 1.25rem; }
        .ci-alert-danger li { font-size: .85rem; }

        /* APERÇU */
        .ci-preview {
            background: linear-gradient(135deg, var(--rh-primary-light), #fff);
            border-left: 4px solid var(--rh-primary);
            border-radius: 10px; padding: 1rem;
        }
        .ci-preview-title {
            font-weight: 700; color: var(--rh-primary);
            font-size: .88rem; margin-bottom: .35rem;
        }
        .ci-preview-body {
            font-size: .82rem; color: #475569; line-height: 1.5;
        }
        .ci-preview-meta {
            font-size: .75rem; color: var(--rh-muted); margin-top: .5rem;
        }

        /* TIMELINE ÉTAPES */
        .ci-etapes { position: relative; padding-left: 22px; }
        .ci-etapes::before {
            content: ''; position: absolute; left: 6px; top: 6px; bottom: 6px;
            width: 2px; background: var(--rh-border);
        }
        .ci-etape { position: relative; padding-bottom: .85rem; }
        .ci-etape:last-child { padding-bottom: 0; }
        .ci-etape::before {
            content: ''; position: absolute; left: -20px; top: 4px;
            width: 10px; height: 10px; border-radius: 50%;
            background: var(--rh-gold); border: 2px solid #fff;
        }
        .ci-etape-title {
            font-size: .82rem; font-weight: 700;
            color: var(--rh-primary);
        }
        .ci-etape-desc {
            font-size: .75rem; color: var(--rh-muted); margin-top: .15rem;
        }

        @media (max-width: 768px) {
            .ci-hero { padding: 1.25rem 1.35rem; }
            .ci-hero h1 { font-size: 1.05rem; }
        }
    </style>

    <div class="container-fluid py-4">

        {{-- BREADCRUMB --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="font-size:.82rem;">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="text-decoration-none" style="color:var(--rh-muted);">Accueil</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.conflits.index') }}" class="text-decoration-none" style="color:var(--rh-muted);">
                        Conflits d'intérêt
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.conflits.campagnes.index') }}" class="text-decoration-none" style="color:var(--rh-muted);">
                        Campagnes
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page" style="color:var(--rh-primary); font-weight:600;">
                    Nouvelle campagne
                </li>
            </ol>
        </nav>

        {{-- HERO --}}
        <div class="ci-hero mb-4">
            <h1><i class="bx bx-megaphone"></i> Créer une nouvelle campagne</h1>
            <p>
                Renseignez les informations. La campagne sera créée en <strong>préparation</strong> —
                vous pourrez l'ouvrir ensuite pour notifier tous les agents.
            </p>
        </div>

        {{-- ERREURS --}}
        @if ($errors->any())
            <div class="ci-alert-danger">
                <strong><i class="bx bx-error-circle me-1"></i> Veuillez corriger :</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORMULAIRE --}}
        <form method="POST" action="{{ route('admin.conflits.campagnes.store') }}" id="campagne-form">
            @csrf

            <div class="row g-4">

                {{-- COLONNE PRINCIPALE --}}
                <div class="col-lg-8">

                    {{-- Informations générales --}}
                    <div class="ci-card">
                        <div class="ci-card-header">
                            <h6><i class="bx bx-info-circle"></i> Informations générales</h6>
                        </div>
                        <div class="ci-card-body">

                            <div class="mb-3">
                                <label for="libelle" class="ci-label">
                                    Libellé <span class="required">*</span>
                                </label>
                                <input type="text" id="libelle" name="libelle"
                                    value="{{ old('libelle', 'Campagne ' . now()->year) }}"
                                    class="form-control @error('libelle') is-invalid @enderror"
                                    placeholder="Ex : Campagne annuelle 2026" required autofocus>
                                @error('libelle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-help">Nom visible par les agents.</small>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="annee" class="ci-label">
                                        Année <span class="required">*</span>
                                    </label>
                                    <input type="number" id="annee" name="annee"
                                        value="{{ old('annee', now()->year) }}"
                                        min="2024" max="2100"
                                        class="form-control @error('annee') is-invalid @enderror" required>
                                    @error('annee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="date_ouverture" class="ci-label">
                                        Date d'ouverture <span class="required">*</span>
                                    </label>
                                    <input type="date" id="date_ouverture" name="date_ouverture"
                                        value="{{ old('date_ouverture', now()->toDateString()) }}"
                                        class="form-control @error('date_ouverture') is-invalid @enderror" required>
                                    @error('date_ouverture')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="date_cloture" class="ci-label">
                                        Date de clôture <span class="optional">(optionnel)</span>
                                    </label>
                                    <input type="date" id="date_cloture" name="date_cloture"
                                        value="{{ old('date_cloture') }}"
                                        class="form-control @error('date_cloture') is-invalid @enderror">
                                    @error('date_cloture')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-help">Doit être après l'ouverture.</small>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Message aux agents --}}
                    <div class="ci-card">
                        <div class="ci-card-header">
                            <h6><i class="bx bx-chat"></i> Message aux agents</h6>
                        </div>
                        <div class="ci-card-body">
                            <label for="message" class="ci-label">
                                Message personnalisé <span class="optional">(optionnel)</span>
                            </label>
                            <textarea id="message" name="message" rows="5" maxlength="2000"
                                class="form-control @error('message') is-invalid @enderror"
                                placeholder="Ex : Merci de compléter votre déclaration avant le 31 mars...">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="d-flex justify-content-between mt-2">
                                <small class="form-help">Ce texte apparaîtra dans l'email et le bandeau agent.</small>
                                <small class="form-help">
                                    <span id="message-count">0</span> / 2000
                                </small>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- COLONNE LATÉRALE --}}
                <div class="col-lg-4">

                    {{-- Aperçu --}}
                    <div class="ci-card">
                        <div class="ci-card-header">
                            <h6><i class="bx bx-show"></i> Aperçu agent</h6>
                        </div>
                        <div class="ci-card-body">
                            <div class="ci-preview">
                                <div class="ci-preview-title">
                                    📢 <span id="preview-libelle">Campagne {{ now()->year }}</span> — Déclaration ouverte
                                </div>
                                <div class="ci-preview-body" id="preview-message">
                                    Merci de déclarer vos conflits d'intérêt.
                                </div>
                                <div class="ci-preview-meta">
                                    Clôture : <span id="preview-cloture">non définie</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Étapes suivantes --}}
                    <div class="ci-card">
                        <div class="ci-card-header">
                            <h6><i class="bx bx-list-ol"></i> Étapes suivantes</h6>
                        </div>
                        <div class="ci-card-body">
                            <div class="ci-etapes">
                                <div class="ci-etape">
                                    <div class="ci-etape-title">1. Création (maintenant)</div>
                                    <div class="ci-etape-desc">La campagne est enregistrée en <strong>préparation</strong>.</div>
                                </div>
                                <div class="ci-etape">
                                    <div class="ci-etape-title">2. Ouverture</div>
                                    <div class="ci-etape-desc">Vous déclenchez l'envoi des notifications.</div>
                                </div>
                                <div class="ci-etape">
                                    <div class="ci-etape-title">3. Déclarations</div>
                                    <div class="ci-etape-desc">Les agents remplissent leur déclaration.</div>
                                </div>
                                <div class="ci-etape">
                                    <div class="ci-etape-title">4. Clôture</div>
                                    <div class="ci-etape-desc">Vous clôturez la campagne.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="ci-card">
                        <div class="ci-card-header">
                            <h6><i class="bx bx-cog"></i> Actions</h6>
                        </div>
                        <div class="ci-card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="ci-btn-gold">
                                    <i class="bx bx-save"></i> Enregistrer
                                </button>
                                <a href="{{ route('admin.conflits.campagnes.index') }}" class="ci-btn-outline">
                                    <i class="bx bx-x-circle"></i> Annuler
                                </a>
                            </div>
                            <div class="mt-3 form-help">
                                <i class="bx bx-info-circle"></i>
                                Aucun agent ne sera notifié tant que la campagne n'est pas <strong>ouverte</strong>.
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </form>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const libelle  = document.getElementById('libelle');
                const message  = document.getElementById('message');
                const cloture  = document.getElementById('date_cloture');
                const prevLib  = document.getElementById('preview-libelle');
                const prevMsg  = document.getElementById('preview-message');
                const prevClot = document.getElementById('preview-cloture');
                const counter  = document.getElementById('message-count');

                const fmtDate = (v) => {
                    if (!v) return 'non définie';
                    return new Date(v).toLocaleDateString('fr-FR');
                };

                const update = () => {
                    prevLib.textContent  = libelle.value || 'Campagne';
                    prevMsg.textContent  = message.value || "Merci de déclarer vos conflits d'intérêt.";
                    prevClot.textContent = fmtDate(cloture.value);
                    counter.textContent  = message.value.length;
                };

                [libelle, message, cloture].forEach(el => el?.addEventListener('input', update));
                update();
            });
        </script>
    @endpush

@endsection
