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
                        <li class="breadcrumb-item"><a href="{{ route('conflits.show', $declaration) }}" style="color: var(--rh-primary);">{{ $declaration->reference }}</a></li>
                        <li class="breadcrumb-item active text-muted" style="font-size: .85rem;">Modification</li>
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
        @if ($errors->any())
            <div class="alert alert-danger mb-4 border-0 rounded-3">
                <i class="bx bx-error-circle me-1"></i> <strong>Veuillez corriger les erreurs :</strong>
                <ul class="mb-0 mt-2">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        {{-- HERO --}}
        <div class="ci-hero">
            <h1><i class="bx bx-edit"></i> Modification de la déclaration</h1>
            <p>
                Référence : <strong>{{ $declaration->reference }}</strong> —
                Dernière modification :
                {{ $declaration->date_derniere_modif
                    ? \Carbon\Carbon::parse($declaration->date_derniere_modif)->format('d/m/Y à H:i')
                    : '—' }}
            </p>
        </div>

        {{-- BANDEAU INFO --}}
        <div class="ci-info-banner mb-4">
            <i class="bx bx-info-circle"></i>
            <div>
                <strong>Information :</strong> Vous pouvez modifier votre déclaration tant qu'elle n'a pas été
                traitée par la cellule Conformité. Toute modification sera horodatée.
            </div>
        </div>

        {{-- FORMULAIRE (réutilise le _form AUTO en mode édition) --}}
        @include('conflits.auto._form', [
            'declaration' => $declaration,
            'agent'       => $agent,
            'contact'     => $contact,
            'direction'   => $direction,
            'departement' => $departement,
            'fonction'    => $fonction,
            'nomN1'       => $nomN1,
            'nomComplet'  => $nomComplet,
            'agents'      => $agents,
        ])

    </div>
@endsection
