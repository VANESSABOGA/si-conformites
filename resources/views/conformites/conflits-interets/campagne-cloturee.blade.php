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
            --rh-radius: 16px;
        }

        .cc-page {
            max-width: 780px;
            margin: 0 auto;
            padding: 3rem 1rem;
        }

        /* ============================================================
           CARTE PRINCIPALE
        ============================================================ */
        .cc-card {
            background: #fff;
            border: 1px solid var(--rh-border);
            border-radius: var(--rh-radius);
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(15, 23, 42, .1);
        }

        /* Bannière verte */
        .cc-banner {
            height: 130px;
            background: radial-gradient(circle at 92% 0%, rgba(249, 178, 51, .3) 0, transparent 38%),
                        linear-gradient(135deg, var(--rh-primary) 0%, var(--rh-primary-dark) 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cc-banner::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 30%, rgba(255,255,255,.08) 1.5px, transparent 1.5px),
                radial-gradient(circle at 70% 60%, rgba(255,255,255,.08) 1.5px, transparent 1.5px);
            background-size: 60px 60px;
        }

        .cc-banner-icon {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(249, 178, 51, .95);
            color: var(--rh-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            box-shadow: 0 12px 32px rgba(249, 178, 51, .4);
            border: 5px solid #fff;
            position: relative;
            z-index: 2;
            transform: translateY(30px);
        }

        /* Corps */
        .cc-body {
            padding: 3.5rem 2.5rem 2.5rem;
            text-align: center;
        }

        .cc-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--rh-primary);
            margin: 0 0 .75rem;
            line-height: 1.25;
        }

        .cc-subtitle {
            font-size: 1rem;
            color: var(--rh-muted);
            line-height: 1.6;
            margin: 0 0 2rem;
            max-width: 520px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Nom de la campagne */
        .cc-campagne-name {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: var(--rh-primary-light);
            border: 1px solid rgba(3, 63, 31, .15);
            color: var(--rh-primary);
            font-weight: 700;
            font-size: .88rem;
            padding: .55rem 1.15rem;
            border-radius: 50rem;
            margin: 0 0 2rem;
        }

        .cc-campagne-name i {
            color: var(--rh-gold);
            font-size: 1.1rem;
        }

        /* Dates */
        .cc-dates {
            display: flex;
            justify-content: center;
            gap: 2.5rem;
            margin: 2rem 0;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px dashed var(--rh-border);
        }

        .cc-date-item {
            text-align: center;
        }

        .cc-date-label {
            font-size: .72rem;
            font-weight: 700;
            color: var(--rh-muted);
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .35rem;
        }

        .cc-date-value {
            font-size: 1rem;
            font-weight: 700;
            color: var(--rh-primary);
        }

        /* Message de réconfort */
        .cc-message {
            background: linear-gradient(135deg, var(--rh-gold-light) 0%, #fff 100%);
            border-left: 4px solid var(--rh-gold);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            text-align: left;
            margin: 2rem 0;
            font-size: .92rem;
            color: #334155;
            line-height: 1.7;
        }

        .cc-message strong {
            color: var(--rh-primary);
            display: block;
            margin-bottom: .35rem;
            font-size: .95rem;
        }

        .cc-message i {
            color: var(--rh-gold);
            margin-right: .25rem;
        }

        /* Actions */
        .cc-actions {
            display: flex;
            gap: .75rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .cc-btn-primary {
            background: var(--rh-primary);
            color: #fff;
            border: 1px solid var(--rh-primary);
            font-weight: 700;
            padding: .75rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .9rem;
            transition: all .2s ease;
        }

        .cc-btn-primary:hover {
            background: var(--rh-primary-dark);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(3, 63, 31, .25);
        }

        .cc-btn-outline {
            background: #fff;
            color: var(--rh-muted);
            border: 1px solid var(--rh-border);
            font-weight: 500;
            padding: .75rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .9rem;
            transition: all .2s ease;
        }

        .cc-btn-outline:hover {
            background: #f8fafc;
            color: #334155;
            border-color: #94a3b8;
        }

        /* Encart contact */
        .cc-contact {
            margin-top: 2rem;
            padding: 1.15rem 1.35rem;
            background: #f8fafc;
            border-radius: 10px;
            font-size: .82rem;
            color: var(--rh-muted);
            line-height: 1.6;
            display: flex;
            align-items: center;
            gap: .85rem;
        }

        .cc-contact i {
            color: var(--rh-primary);
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .cc-contact a {
            color: var(--rh-primary);
            font-weight: 600;
            text-decoration: none;
        }

        .cc-contact a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .cc-page { padding: 2rem .75rem; }
            .cc-body { padding: 3rem 1.5rem 2rem; }
            .cc-title { font-size: 1.35rem; }
            .cc-subtitle { font-size: .92rem; }
            .cc-dates { flex-direction: column; gap: 1.25rem; }
            .cc-actions { flex-direction: column; }
            .cc-actions a { justify-content: center; width: 100%; }
        }
    </style>

    <div class="cc-page">

        <div class="cc-card">

            {{-- BANNIÈRE --}}
            <div class="cc-banner">
                <div class="cc-banner-icon">
                    <i class="bx bx-lock-alt"></i>
                </div>
            </div>

            {{-- CORPS --}}
            <div class="cc-body">

                {{-- TITRE --}}
                <h1 class="cc-title">
                    Désolé {{ $agentPrenom ?? 'cher(e) agent(e)' }} 🙏
                </h1>

                <p class="cc-subtitle">
                    La période de déclaration des conflits d'intérêt est actuellement fermée.
                </p>

                {{-- NOM DE LA CAMPAGNE (si disponible) --}}
                @if (!empty($campagneLibelle))
                    <div class="cc-campagne-name">
                        <i class="bx bx-megaphone"></i>
                        {{ $campagneLibelle }}
                    </div>
                @endif

                {{-- DATES (si disponibles) --}}
                @if (!empty($dateOuverture) || !empty($dateCloture))
                    <div class="cc-dates">
                        @if (!empty($dateOuverture))
                            <div class="cc-date-item">
                                <div class="cc-date-label">
                                    <i class="bx bx-calendar-check"></i> Ouverture
                                </div>
                                <div class="cc-date-value">
                                    {{ \Carbon\Carbon::parse($dateOuverture)->format('d/m/Y') }}
                                </div>
                            </div>
                        @endif

                        @if (!empty($dateCloture))
                            <div class="cc-date-item">
                                <div class="cc-date-label">
                                    <i class="bx bx-calendar-x"></i> Clôture
                                </div>
                                <div class="cc-date-value">
                                    {{ \Carbon\Carbon::parse($dateCloture)->format('d/m/Y') }}
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- MESSAGE DE RÉCONFORT --}}
                <div class="cc-message">
                    <strong><i class="bx bx-info-circle"></i> Merci de votre patience</strong>
                    Une nouvelle campagne sera prochainement ouverte pour vous permettre de faire votre déclaration.
                    Vous serez notifié(e) par email dès son lancement.
                    <br><br>
                    Si votre situation est <strong>urgente</strong> ou nécessite un traitement immédiat,
                    veuillez contacter directement le service Conformité ou les Ressources Humaines.
                </div>

                {{-- ACTIONS --}}
                <div class="cc-actions">
                    <a href="{{ route('dashboard') }}" class="cc-btn-primary">
                        <i class="bx bx-home"></i> Retour au tableau de bord
                    </a>

                    <a href="{{ route('conflits.index') }}" class="cc-btn-outline">
                        <i class="bx bx-list-ul"></i> Voir mes déclarations
                    </a>
                </div>

                {{-- CONTACT --}}
                <div class="cc-contact">
                    <i class="bx bx-support"></i>
                    <div>
                        <strong>Besoin d'aide ?</strong>
                        Contactez le service Conformité à
                        <a href="mailto:conformite@yakoafricassur.com">conformite@yakoafricassur.com</a>
                        ou les RH à
                        <a href="mailto:rh@yakoafricassur.com">rh@yakoafricassur.com</a>.
                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection
