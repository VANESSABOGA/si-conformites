@extends('layouts.main')

@section('content')
    @include('conflits.partials._styles')

    @php
        $agent       = $agent ?? auth()->user()?->agent;
        $contact     = $agent?->contacts;
        $affectation = $agent?->affectations?->firstWhere('statut', 'active');
        $direction   = $affectation?->direction?->libelle ?? '—';
        $departement = $affectation?->departement?->libelle ?? '—';
        $fonction    = $affectation?->poste?->libelle ?? '—';
        $n1          = $affectation?->superviseur;
        $nomN1       = $n1 ? trim($n1->nom . ' ' . $n1->prenom) : '—';
        $nomComplet  = trim(($agent?->nom ?? '') . ' ' . ($agent?->prenom ?? ''));

        $agents = $agents ?? \App\Models\Agent::where('statut', 'actif')
            ->where('id', '!=', $agent?->id)
            ->orderBy('nom')->orderBy('prenom')->get();
    @endphp

    <div class="page-content">

        {{-- BREADCRUMB --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
            <div class="breadcrumb-title pe-3" style="color:var(--rh-primary);font-weight:700;">CONFORMITÉ</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: var(--rh-primary);"><i class="bx bx-home-alt fs-6"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('conflits.index') }}" style="color: var(--rh-primary);">Mes déclarations</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('conflits.create') }}" style="color: var(--rh-primary);">Nouvelle</a></li>
                        <li class="breadcrumb-item active text-muted" style="font-size: .85rem;">Déclaration pour moi-même</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- MESSAGES --}}
        @if (session('success'))
            <div class="alert alert-success mb-4 border-0 rounded-3"><i class="bx bx-check-circle me-1"></i> {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mb-4 border-0 rounded-3"><i class="bx bx-error-circle me-1"></i> {{ session('error') }}</div>
        @endif

        {{-- HERO --}}
        <div class="ci-hero">
            <h1><i class="bx bx-user-check"></i> Déclaration pour moi-même</h1>
            <p>Merci de compléter ce formulaire pour déclarer tout conflit d'intérêts réel, potentiel ou apparent.</p>
        </div>

        {{-- FORMULAIRE --}}
        @include('conflits.auto._form', [
            'agent'        => $agent,
            'contact'      => $contact,
            'direction'    => $direction,
            'departement'  => $departement,
            'fonction'     => $fonction,
            'nomN1'        => $nomN1,
            'nomComplet'   => $nomComplet,
            'agents'       => $agents,
        ])

    </div>
@endsection
