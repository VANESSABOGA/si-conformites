@extends('layouts.main')

@section('content')
    @include('conflits.partials._styles')

    <div class="page-content">
        <div class="ci-hero">
            <h1><i class="bx bx-shield-quarter"></i> Nouvelle déclaration de conformité</h1>
            <p>Choisissez le type de déclaration que vous souhaitez effectuer.</p>
        </div>

        <div class="ci-choice-grid">
            <a href="{{ route('conflits.auto.create') }}" class="ci-choice-card">
                <div class="ci-choice-icon"><i class="bx bx-user-check"></i></div>
                <h3>Déclaration pour moi-même</h3>
                <p>Vous déclarez une situation de conflit d'intérêts qui vous concerne personnellement.</p>
                <ul class="ci-choice-list">
                    <li><i class="bx bx-time"></i> Environ 3 minutes</li>
                    <li><i class="bx bx-shield"></i> Traitement confidentiel</li>
                </ul>
                <span class="ci-choice-cta">Commencer <i class="bx bx-right-arrow-alt"></i></span>
            </a>

            <a href="{{ route('conflits.denonciation.create') }}" class="ci-choice-card den">
                <div class="ci-choice-icon"><i class="bx bx-error-alt"></i></div>
                <h3>Dénoncer un autre agent</h3>
                <p>Vous signalez une situation impliquant un autre collaborateur.</p>
                <ul class="ci-choice-list">
                    <li><i class="bx bx-time"></i> Environ 5 minutes</li>
                    <li><i class="bx bx-lock"></i> Anonymat garanti</li>
                </ul>
                <span class="ci-choice-cta">Dénoncer <i class="bx bx-right-arrow-alt"></i></span>
            </a>
        </div>

        <div class="ci-info-banner">
            <i class="bx bx-info-circle"></i>
            <div><strong>Bon à savoir :</strong> Les déclarations sont traitées par la cellule Conformité.</div>
        </div>
    </div>
@endsection
