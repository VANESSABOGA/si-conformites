@extends('layouts.main')

@section('content')
    @include('conflits.partials._styles')

    @php
        $agent       = $agent ?? auth()->user()?->agent;
        $contact     = $agent?->contacts;
        $affectation = $agent?->affectations?->firstWhere('statut', 'active');
        $direction   = $affectation?->direction?->libelle ?? '—';
        $fonction    = $affectation?->poste?->libelle ?? '—';
        $nomComplet  = trim(($agent?->nom ?? '') . ' ' . ($agent?->prenom ?? ''));

        $agents = $agents ?? \App\Models\Agent::where('statut', 'actif')
            ->where('id', '!=', $agent?->id)
            ->orderBy('nom')->orderBy('prenom')->get();

        $natures = [
            'PARTICIPATION_FINANCIERE' => 'Participation financière dans une entreprise partenaire ou prestataire',
            'LIEN_FAMILIAL'            => 'Lien familial avec un client, fournisseur ou partenaire',
            'ACTIVITE_EXTERNE'         => 'Activité professionnelle externe',
            'FONCTION_ADMINISTRATEUR'  => "Fonction d'administrateur ou de dirigeant dans une autre structure",
            'RELATION_PERSONNELLE'     => 'Relation personnelle pouvant influencer une décision professionnelle',
            'CADEAUX_AVANTAGES'        => 'Cadeaux, avantages ou invitations reçus',
            'AUTRE'                    => 'Autre',
        ];
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
                        <li class="breadcrumb-item active text-muted" style="font-size: .85rem;">Dénonciation</li>
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
        <div class="ci-hero danger">
            <h1><i class="bx bx-error-alt"></i> Dénonciation d'un agent</h1>
            <p>Vous signalez une situation impliquant un autre collaborateur. Votre identité sera protégée.</p>
        </div>

        {{-- FORMULAIRE --}}
        @include('conflits.denonciation._form', [
            'agent'       => $agent,
            'contact'     => $contact,
            'direction'   => $direction,
            'fonction'    => $fonction,
            'nomComplet'  => $nomComplet,
            'agents'      => $agents,
            'natures'     => $natures,
        ])

    </div>
@endsection
