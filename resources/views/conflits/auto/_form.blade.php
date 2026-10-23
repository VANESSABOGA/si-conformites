@php
    $isEdit = isset($declaration) && $declaration !== null;
    $conflit = $isEdit ? $declaration->conflit : null;
    $honneur = $isEdit ? $declaration->declarationHonneur : null;

    $reponseQ1 = old('reponse_q1', $isEdit ? $declaration->reponse_q1 : '');

    $naturesSelected = old('nature_conflit', []);
    if (empty($naturesSelected) && $conflit?->nature_conflit) {
        $naturesSelected = is_string($conflit->nature_conflit)
            ? json_decode($conflit->nature_conflit, true) ?? []
            : $conflit->nature_conflit;
    }

    $dateDebut = old(
        'date_debut_situation',
        $conflit?->date_debut_situation ? \Carbon\Carbon::parse($conflit->date_debut_situation)->format('Y-m-d') : null,
    );

    $natures = [
        'PARTICIPATION_FINANCIERE' => 'Participation financière dans une entreprise partenaire ou prestataire',
        'LIEN_FAMILIAL' => 'Lien familial avec un client, fournisseur ou partenaire',
        'ACTIVITE_EXTERNE' => 'Activité professionnelle externe',
        'FONCTION_ADMINISTRATEUR' => "Fonction d'administrateur ou de dirigeant dans une autre structure",
        'RELATION_PERSONNELLE' => 'Relation personnelle pouvant influencer une décision professionnelle',
        'CADEAUX_AVANTAGES' => 'Cadeaux, avantages ou invitations reçus',
        'AUTRE' => 'Autre',
    ];

    // Valeur initiale des checkboxes (édition = déjà signé)
    $lectureInit = old('lecture_notice', $isEdit ? true : false);
    $autorisationInit = old('autorisation_traitement', $isEdit ? true : false);
    $engagementInit = old('engagement_declarant', $isEdit ? true : false);
    $certificationInit = old('certification_honneur', $honneur?->certification_honneur ?? false);
@endphp

<div class="ci-progress-bar-container">
    <div class="ci-steps" id="stepperAuto"></div>
    <div class="ci-progress-line">
        <div class="ci-progress-line-fill" id="progressFillAuto"></div>
    </div>
</div>

{{-- ============================================================
     BANDEAU D'ERREURS GLOBAL
============================================================ --}}
@if ($errors->any())
    <div class="alert alert-danger mb-4 border-0 rounded-3" role="alert">
        <i class="bx bx-error-circle me-1"></i>
        <strong>Veuillez corriger les erreurs suivantes :</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form id="formAuto" action="{{ $isEdit ? route('conflits.update', $declaration) : route('conflits.storeAuto') }}"
    method="POST" novalidate>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    {{-- ============================================================
         ÉTAPE 1 — IDENTIFICATION
    ============================================================ --}}
    <div class="ci-step-panel active" data-step="1">
        <div class="ci-card">
            <div class="ci-card-header">
                <h6><i class="bx bx-user-circle"></i> 1. Identification du collaborateur</h6>
                <span class="ci-step-badge">Étape 1 / 4</span>
            </div>
            <div class="ci-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="ci-label">Nom et Prénoms</label>
                        <input type="text" class="form-control ci-readonly" value="{{ $nomComplet ?? '' }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="ci-label">Matricule</label>
                        <input type="text" class="form-control ci-readonly" value="{{ $agent?->matricule ?? '—' }}"
                            readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="ci-label">Fonction</label>
                        <input type="text" class="form-control ci-readonly" value="{{ $fonction ?? '—' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="ci-label">Direction</label>
                        <input type="text" class="form-control ci-readonly" value="{{ $direction ?? '—' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="ci-label">Département</label>
                        <input type="text" class="form-control ci-readonly" value="{{ $departement ?? '—' }}"
                            readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="ci-label">Adresse e-mail professionnelle</label>
                        <input type="email" class="form-control ci-readonly" value="{{ $contact?->email_pro ?? '—' }}"
                            readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="ci-label">Nom et prénoms du N+1</label>
                        <input type="text" class="form-control ci-readonly" value="{{ $nomN1 ?? '—' }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         ÉTAPE 2 — DÉCLARATION
    ============================================================ --}}
    <div class="ci-step-panel" data-step="2">
        <div class="ci-card">
            <div class="ci-card-header">
                <h6><i class="bx bx-help-circle"></i> 2. Déclaration</h6>
                <span class="ci-step-badge">Étape 2 / 4</span>
            </div>
            <div class="ci-card-body">

                {{-- Question OUI / NON --}}
                <div class="mb-4">
                    <label class="ci-label d-block mb-3">
                        <span class="required">*</span> Avez-vous actuellement connaissance d'une situation de conflit
                        d'intérêts réel, potentiel ou apparent à votre niveau ?
                    </label>
                    <div class="ci-radio-group">
                        <div class="ci-radio ci-radio-oui">
                            <input type="radio" id="auto_oui" name="reponse_q1" value="OUI"
                                @checked($reponseQ1 === 'OUI')>
                            <label for="auto_oui"><i class="bx bx-check-circle"></i> Oui</label>
                        </div>
                        <div class="ci-radio">
                            <input type="radio" id="auto_non" name="reponse_q1" value="NON"
                                @checked($reponseQ1 === 'NON')>
                            <label for="auto_non"><i class="bx bx-x-circle"></i> Non</label>
                        </div>
                    </div>
                    @error('reponse_q1')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                {{-- BLOC NON — Déclaration sur l'honneur --}}
                <div id="auto_blocNon" class="ci-section-hidden">
                    <div class="ci-dynamic-block">
                        <div class="ci-block-title"><i class="bx bx-check-shield"></i> Déclaration sur l'honneur</div>
                        <div class="ci-honneur">
                            <strong>Engagement du déclarant</strong>
                            Je certifie sur l'honneur ne pas être en situation de conflit d'intérêts réel, potentiel ou
                            apparent à la date de la présente déclaration et m'engage à signaler toute situation future
                            pouvant constituer un conflit d'intérêts.
                        </div>
                        <div class="ci-consent-item">
                            <input type="checkbox" id="auto_certification" name="certification_honneur" value="1"
                                @checked($certificationInit)>
                            <label for="auto_certification"><span class="required">*</span> Je certifie sur l'honneur
                                l'exactitude des informations déclarées ci-dessus.</label>
                        </div>
                        @error('certification_honneur')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- BLOC OUI — Détail du conflit --}}
                <div id="auto_blocOui" class="ci-section-hidden">

                    {{-- Natures --}}
                    <div class="mb-4">
                        <label class="ci-label d-block mb-3">
                            <span class="required">*</span> Nature du conflit d'intérêts
                            <span
                                style="background:var(--rh-danger-light);color:var(--rh-danger);font-size:.68rem;font-weight:700;padding:.2rem .55rem;border-radius:4px;text-transform:uppercase;margin-left:.5rem;">Plusieurs
                                choix possibles</span>
                        </label>
                        <div class="ci-checkbox-group">
                            @foreach ($natures as $key => $label)
                                <label class="ci-checkbox">
                                    <input type="checkbox" name="nature_conflit[]" value="{{ $key }}"
                                        class="nature-checkbox" data-nature="{{ $key }}"
                                        @checked(in_array($key, (array) $naturesSelected))>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('nature_conflit')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Société --}}
                    <div id="auto_blocSociete" class="ci-section-hidden">
                        <div class="ci-dynamic-block">
                            <div class="ci-block-title"><i class="bx bx-buildings"></i> Société ou organisation
                                concernée</div>
                            <label class="ci-label"><span class="required">*</span> Nom de la société ou
                                organisation</label>
                            <input type="text" class="form-control" name="personne_societe_concernee"
                                maxlength="255"
                                value="{{ old('personne_societe_concernee', $conflit?->personne_societe_concernee) }}"
                                placeholder="Ex. Société ABC, Cabinet XYZ...">
                            @error('personne_societe_concernee')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Client --}}
                    <div id="auto_blocClient" class="ci-section-hidden">
                        <div class="ci-dynamic-block">
                            <div class="ci-block-title"><i class="bx bx-link"></i> Client, fournisseur ou partenaire
                                concerné</div>
                            <label class="ci-label"><span class="required">*</span> Nom du client, fournisseur ou
                                partenaire</label>
                            <input type="text" class="form-control" name="client_fournisseur_partenaire"
                                maxlength="255"
                                value="{{ old('client_fournisseur_partenaire', $conflit?->client_fournisseur_partenaire) }}"
                                placeholder="Ex. Client ABC...">
                            @error('client_fournisseur_partenaire')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Relation --}}
                    <div id="auto_blocRelation" class="ci-section-hidden">
                        <div class="ci-dynamic-block">
                            <div class="ci-block-title"><i class="bx bx-user"></i> Relation personnelle — Personne
                                concernée</div>
                            <label class="ci-label d-block mb-2"><span class="required">*</span> La personne est-elle
                                en interne ou en externe ?</label>
                            <div class="ci-radio-group mb-3">
                                <div class="ci-radio">
                                    <input type="radio" id="auto_rel_int" name="relation_type" value="INTERNE"
                                        @checked(old('relation_type', $conflit?->relation_type) === 'INTERNE')>
                                    <label for="auto_rel_int"><i class="bx bx-buildings"></i> En interne (Agent YAKO
                                        AFRICA)</label>
                                </div>
                                <div class="ci-radio">
                                    <input type="radio" id="auto_rel_ext" name="relation_type" value="EXTERNE"
                                        @checked(old('relation_type', $conflit?->relation_type) === 'EXTERNE')>
                                    <label for="auto_rel_ext"><i class="bx bx-world"></i> En externe (Force de Vente,
                                        fournisseur, partenaire, Personnalité Publique, ...)</label>
                                </div>
                            </div>
                            <div id="auto_rel_interne" class="ci-section-hidden">
                                <label class="ci-label"><span class="required">*</span> Sélectionnez l'agent
                                    concerné</label>
                                <select class="form-select" name="relation_agent_id">
                                    <option value="">— Sélectionner un agent —</option>
                                    @foreach ($agents as $a)
                                        <option value="{{ $a->id }}" @selected(old('relation_agent_id', $conflit?->relation_agent_id) == $a->id)>
                                            {{ $a->nom }} {{ $a->prenom }} - {{ $a->matricule }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('relation_agent_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="auto_rel_externe" class="ci-section-hidden">
                                <label class="ci-label"><span class="required">*</span> Nom et prénoms de la
                                    personne</label>
                                <input type="text" class="form-control" name="relation_nom_externe"
                                    maxlength="255"
                                    value="{{ old('relation_nom_externe', $conflit?->relation_nom_externe) }}"
                                    placeholder="Ex. M. KOUAME Jean">
                                @error('relation_nom_externe')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Cadeaux --}}
                    <div id="auto_blocCadeaux" class="ci-section-hidden">
                        <div class="ci-dynamic-block">
                            <div class="ci-block-title"><i class="bx bx-gift"></i> Cadeaux, avantages ou invitations
                            </div>
                            <label class="ci-label d-block mb-2"><span class="required">*</span> Interne ou externe
                                ?</label>
                            <div class="ci-radio-group mb-3">
                                <div class="ci-radio">
                                    <input type="radio" id="auto_cad_int" name="cadeau_type" value="INTERNE"
                                        @checked(old('cadeau_type', $conflit?->cadeau_type) === 'INTERNE')>
                                    <label for="auto_cad_int"><i class="bx bx-buildings"></i> En interne</label>
                                </div>
                                <div class="ci-radio">
                                    <input type="radio" id="auto_cad_ext" name="cadeau_type" value="EXTERNE"
                                        @checked(old('cadeau_type', $conflit?->cadeau_type) === 'EXTERNE')>
                                    <label for="auto_cad_ext"><i class="bx bx-world"></i> En externe (Force de Vente,
                                        fournisseur, partenaire, Personnalité Publique, ...)</label>
                                </div>
                            </div>
                            <div id="auto_cad_interne" class="ci-section-hidden">
                                <label class="ci-label"><span class="required">*</span> Agent concerné</label>
                                <select class="form-select" name="cadeau_agent_id">
                                    <option value="">— Sélectionner —</option>
                                    @foreach ($agents as $a)
                                        <option value="{{ $a->id }}" @selected(old('cadeau_agent_id', $conflit?->cadeau_agent_id) == $a->id)>
                                            {{ $a->nom }} {{ $a->prenom }} - {{ $a->matricule }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cadeau_agent_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="auto_cad_externe" class="ci-section-hidden">
                                <label class="ci-label"><span class="required">*</span> Nom et prénoms</label>
                                <input type="text" class="form-control" name="cadeau_nom_externe" maxlength="255"
                                    value="{{ old('cadeau_nom_externe', $conflit?->cadeau_nom_externe) }}"
                                    placeholder="Ex. M. KONE Ibrahim">
                                @error('cadeau_nom_externe')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label class="ci-label"><span class="required">*</span> Description détaillée</label>
                        <textarea class="form-control" name="description_detaillee" rows="4" maxlength="2000"
                            placeholder="Décrivez précisément la situation...">{{ old('description_detaillee', $conflit?->description_detaillee) }}</textarea>
                        <div class="ci-char-count"><span class="count">0</span> / 2000 caractères (min. 10)</div>
                        @error('description_detaillee')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Date + influence --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="ci-label">Depuis quelle date cette situation existe-t-elle ? <span
                                    class="ci-optional">(optionnel)</span></label>
                            <input type="date" class="form-control" name="date_debut_situation"
                                value="{{ $dateDebut }}" max="{{ date('Y-m-d') }}">
                            @error('date_debut_situation')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="ci-label d-block mb-2"><span class="required">*</span> Cette situation
                                influence-t-elle vos décisions professionnelles ?</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="influence_decisions"
                                        id="auto_inf_oui" value="OUI" @checked(old('influence_decisions', $conflit?->influence_decisions) === 'OUI')>
                                    <label class="form-check-label ci-label" for="auto_inf_oui">Oui</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="influence_decisions"
                                        id="auto_inf_non" value="NON" @checked(old('influence_decisions', $conflit?->influence_decisions) === 'NON')>
                                    <label class="form-check-label ci-label" for="auto_inf_non">Non</label>
                                </div>
                            </div>
                            @error('influence_decisions')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Mesures --}}
                    <div class="mb-2">
                        <label class="ci-label">Mesures déjà prises <span
                                class="ci-optional">(optionnel)</span></label>
                        <textarea class="form-control" name="mesures_prises" rows="3" maxlength="1000"
                            placeholder="Ex. J'ai informé mon supérieur...">{{ old('mesures_prises', $conflit?->mesures_prises) }}</textarea>
                        @error('mesures_prises')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         ÉTAPE 3 — NOTICE & CONSENTEMENT
    ============================================================ --}}
    <div class="ci-step-panel" data-step="3">
        <div class="ci-card">
            <div class="ci-card-header">
                <h6><i class="bx bx-lock-alt"></i> 3. Notice de confidentialité et consentement</h6>
                <span class="ci-step-badge">Étape 3 / 4</span>
            </div>
            <div class="ci-card-body">
                <div class="ci-consent-box">
                    <div class="ci-notice">
                        <h5>Notice de confidentialité — Déclaration des conflits d'intérêts</h5>
                        <p>Dans le cadre de son dispositif de gouvernance, d'éthique et de conformité, la société
                            procède à la collecte des déclarations de conflits d'intérêts de l'ensemble de son
                            personnel.</p>
                        <p>Les informations recueillies ont pour finalité de détecter, prévenir et gérer les situations
                            de conflits d'intérêts réelles, potentielles ou apparentes.</p>
                        <h6>Utilisation des données</h6>
                        <ul>
                            <li>L'analyse des situations déclarées ;</li>
                            <li>L'évaluation des risques de conflits d'intérêts ;</li>
                            <li>La mise en œuvre de mesures adaptées ;</li>
                            <li>Le respect des exigences réglementaires.</li>
                        </ul>
                    </div>

                    <div class="ci-consent-item">
                        <input type="checkbox" id="auto_lecture" name="lecture_notice" value="1"
                            @checked($lectureInit)>
                        <label for="auto_lecture"><span class="required">*</span> J'ai lu et j'accepte la présente
                            notice de confidentialité.</label>
                    </div>
                    @error('lecture_notice')
                        <div class="text-danger small mt-1 mb-2">{{ $message }}</div>
                    @enderror

                    <div class="ci-consent-item">
                        <input type="checkbox" id="auto_autorisation" name="autorisation_traitement" value="1"
                            @checked($autorisationInit)>
                        <label for="auto_autorisation"><span class="required">*</span> J'autorise le traitement de mes
                            données dans le cadre de la gestion des conflits d'intérêts.</label>
                    </div>
                    @error('autorisation_traitement')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         ÉTAPE 4 — ENGAGEMENT & RÉCAP
    ============================================================ --}}
    <div class="ci-step-panel" data-step="4">
        <div class="ci-card">
            <div class="ci-card-header">
                <h6><i class="bx bx-check-shield"></i> 4. Déclaration et engagement</h6>
                <span class="ci-step-badge">Étape 4 / 4</span>
            </div>
            <div class="ci-card-body">
                <div class="ci-honneur">
                    <strong>Engagement final</strong>
                    En soumettant cette déclaration, je reconnais avoir pris connaissance de la politique de gestion des
                    conflits d'intérêts de l'entreprise et je certifie sincères les informations communiquées.
                </div>
                <div class="ci-consent-item">
                    <input type="checkbox" id="auto_engagement" name="engagement_declarant" value="1"
                        @checked($engagementInit)>
                    <label for="auto_engagement"><span class="required">*</span> Je reconnais avoir pris connaissance
                        de la politique et certifie sincères les informations communiquées.</label>
                </div>
                @error('engagement_declarant')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

                <div class="ci-dynamic-block mt-4">
                    <div class="ci-block-title"><i class="bx bx-list-check"></i> Récapitulatif</div>
                    <div class="ci-recap" id="auto_recap"><em class="text-muted">Le récapitulatif s'affichera
                            automatiquement.</em></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         NAVIGATION
    ============================================================ --}}
    <div class="ci-nav-bar">
        <button type="button" class="ci-btn ci-btn-outline" data-action="prev">
            <i class="bx bx-chevron-left"></i> Précédent
        </button>
        <div class="ci-nav-info" data-info>Étape 1 sur 4</div>
        <div class="d-flex gap-2">
            <a href="{{ $isEdit ? route('conflits.show', $declaration) : route('conflits.index') }}"
                class="ci-btn ci-btn-outline d-none d-sm-inline-flex">
                <i class="bx bx-x"></i> Annuler
            </a>
            <button type="button" class="ci-btn ci-btn-primary" data-action="next">
                Suivant <i class="bx bx-chevron-right"></i>
            </button>
            <button type="submit" class="ci-btn ci-btn-gold ci-section-hidden" data-action="submit">
                <i class="bx bx-send"></i> {{ $isEdit ? 'Enregistrer' : 'Soumettre' }}
            </button>
        </div>
    </div>
</form>

<script>
    /* ====================================================================
   AUTO — Wizard 4 étapes (corrigé)
   ==================================================================== */
    document.addEventListener('DOMContentLoaded', function() {
        const TOTAL_STEPS = 4;
        const STEPS = ['Identification', 'Déclaration', 'Consentement', 'Engagement'];
        const form = document.getElementById('formAuto');
        const panels = form.querySelectorAll('.ci-step-panel');
        const progressFill = document.getElementById('progressFillAuto');
        const info = form.querySelector('[data-info]');
        const btnPrev = form.querySelector('[data-action="prev"]');
        const btnNext = form.querySelector('[data-action="next"]');
        const btnSubmit = form.querySelector('[data-action="submit"]');

        let currentStep = 1;

        /* ================================================================
           GESTION DES BLOCS DYNAMIQUES (OUI/NON, NATURES, RELATION, CADEAUX)
        ================================================================ */
        function initDynamicLinks() {
            // OUI / NON
            const blocOui = document.getElementById('auto_blocOui');
            const blocNon = document.getElementById('auto_blocNon');
            const radios = form.querySelectorAll('input[name="reponse_q1"]');
            const toggleReponse = () => {
                const v = form.querySelector('input[name="reponse_q1"]:checked')?.value;
                if (blocOui) blocOui.classList.toggle('ci-section-hidden', v !== 'OUI');
                if (blocNon) blocNon.classList.toggle('ci-section-hidden', v !== 'NON');
            };
            radios.forEach(r => r.addEventListener('change', toggleReponse));
            toggleReponse();

            // Natures
            const checkboxes = form.querySelectorAll('.nature-checkbox');
            const blocSociete = document.getElementById('auto_blocSociete');
            const blocClient = document.getElementById('auto_blocClient');
            const blocRelation = document.getElementById('auto_blocRelation');
            const blocCadeaux = document.getElementById('auto_blocCadeaux');

            const toggleNatures = () => {
                const checked = Array.from(checkboxes).filter(c => c.checked).map(c => c.dataset.nature);
                const show = (el, cond) => {
                    if (!el) return;
                    el.classList.toggle('ci-section-hidden', !cond);
                    el.querySelectorAll('input, select, textarea').forEach(i => i.disabled = !cond);
                };
                show(blocSociete, ['PARTICIPATION_FINANCIERE', 'ACTIVITE_EXTERNE',
                    'FONCTION_ADMINISTRATEUR'
                ].some(n => checked.includes(n)));
                show(blocClient, checked.includes('LIEN_FAMILIAL'));
                show(blocRelation, checked.includes('RELATION_PERSONNELLE'));
                show(blocCadeaux, checked.includes('CADEAUX_AVANTAGES'));
            };
            checkboxes.forEach(c => c.addEventListener('change', toggleNatures));
            toggleNatures();

            // Relation type
            const relInt = document.getElementById('auto_rel_interne');
            const relExt = document.getElementById('auto_rel_externe');
            const toggleRel = () => {
                const v = form.querySelector('input[name="relation_type"]:checked')?.value;
                if (relInt) {
                    relInt.classList.toggle('ci-section-hidden', v !== 'INTERNE');
                    relInt.querySelectorAll('input, select').forEach(i => i.disabled = v !== 'INTERNE');
                }
                if (relExt) {
                    relExt.classList.toggle('ci-section-hidden', v !== 'EXTERNE');
                    relExt.querySelectorAll('input, select').forEach(i => i.disabled = v !== 'EXTERNE');
                }
            };
            form.querySelectorAll('input[name="relation_type"]').forEach(r => r.addEventListener('change',
                toggleRel));
            toggleRel();

            // Cadeau type
            const cadInt = document.getElementById('auto_cad_interne');
            const cadExt = document.getElementById('auto_cad_externe');
            const toggleCad = () => {
                const v = form.querySelector('input[name="cadeau_type"]:checked')?.value;
                if (cadInt) {
                    cadInt.classList.toggle('ci-section-hidden', v !== 'INTERNE');
                    cadInt.querySelectorAll('input, select').forEach(i => i.disabled = v !== 'INTERNE');
                }
                if (cadExt) {
                    cadExt.classList.toggle('ci-section-hidden', v !== 'EXTERNE');
                    cadExt.querySelectorAll('input, select').forEach(i => i.disabled = v !== 'EXTERNE');
                }
            };
            form.querySelectorAll('input[name="cadeau_type"]').forEach(r => r.addEventListener('change',
                toggleCad));
            toggleCad();

            // Compteurs de caractères
            form.querySelectorAll('textarea[maxlength]').forEach(ta => {
                const counter = ta.parentElement.querySelector('.ci-char-count .count');
                if (!counter) return;
                const update = () => {
                    counter.textContent = ta.value.length;
                    const max = parseInt(ta.maxLength);
                    counter.parentElement.classList.toggle('warn', ta.value.length > max * 0.9);
                };
                ta.addEventListener('input', update);
                update();
            });
        }

        /* ================================================================
           CONSTRUCTION DU STEPPER
        ================================================================ */
        const stepper = document.getElementById('stepperAuto');
        if (stepper) {
            stepper.innerHTML = '';
            STEPS.forEach((label, i) => {
                const step = i + 1;
                const div = document.createElement('div');
                div.className = 'ci-step';
                div.dataset.step = step;
                div.innerHTML =
                    `<div class="ci-step-circle">${step}</div><div class="ci-step-label">${label}</div>`;
                div.addEventListener('click', () => {
                    if (step < currentStep) {
                        currentStep = step;
                        updateStepper();
                    } else if (step > currentStep) {
                        for (let i = currentStep; i < step; i++) {
                            const errs = validateStep(i);
                            if (errs.length) {
                                alert(errs.join('\n'));
                                return;
                            }
                        }
                        currentStep = step;
                        updateStepper();
                    }
                });
                stepper.appendChild(div);
            });
        }

        /* ================================================================
           MISE À JOUR DU STEPPER
        ================================================================ */
        function updateStepper() {
            if (!stepper) return;

            stepper.querySelectorAll('.ci-step').forEach(el => {
                const step = parseInt(el.dataset.step);
                const circle = el.querySelector('.ci-step-circle');
                el.classList.remove('active', 'done', 'completed');
                if (step < currentStep) {
                    el.classList.add('done', 'completed');
                    circle.classList.add('done');
                    circle.innerHTML = '<i class="bx bx-check"></i>';
                } else if (step === currentStep) {
                    el.classList.add('active');
                    circle.classList.add('active');
                    circle.textContent = step;
                } else {
                    circle.classList.remove('active', 'done');
                    circle.textContent = step;
                }
            });

            if (progressFill) progressFill.style.width = ((currentStep / TOTAL_STEPS) * 100) + '%';
            if (info) info.textContent = `Étape ${currentStep} sur ${TOTAL_STEPS}`;
            if (btnPrev) btnPrev.disabled = currentStep === 1;

            if (currentStep === TOTAL_STEPS) {
                if (btnNext) btnNext.classList.add('ci-section-hidden');
                if (btnSubmit) btnSubmit.classList.remove('ci-section-hidden');
                buildRecap();
            } else {
                if (btnNext) btnNext.classList.remove('ci-section-hidden');
                if (btnSubmit) btnSubmit.classList.add('ci-section-hidden');
            }

            panels.forEach(p => p.classList.toggle('active', parseInt(p.dataset.step) === currentStep));
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        /* ================================================================
           VALIDATION PAR ÉTAPE
        ================================================================ */
        function validateStep(step) {

            // alert(step)
            const errors = [];
            const $ = (sel) => form.querySelector(sel);

            if (step === 2) {
                const rep = form.querySelector('input[name="reponse_q1"]:checked');
                if (!rep) {
                    errors.push('Veuillez répondre à la question principale.');
                    return errors;
                }

                if (rep.value === 'NON') {
                    if (!$('#auto_certification').checked) {
                        errors.push("Veuillez certifier votre déclaration sur l'honneur.");
                    }
                } else {
                    // Q1 = OUI
                    const natures = Array.from(form.querySelectorAll('.nature-checkbox:checked')).map(c => c
                        .value);
                    if (!natures.length) {
                        errors.push('Veuillez sélectionner au moins une nature.');
                        return errors;
                    }

                    const besoinSociete = ['PARTICIPATION_FINANCIERE', 'ACTIVITE_EXTERNE',
                        'FONCTION_ADMINISTRATEUR'
                    ].some(n => natures.includes(n));
                    const besoinClient = natures.includes('LIEN_FAMILIAL');
                    const besoinRelation = natures.includes('RELATION_PERSONNELLE');
                    const besoinCadeaux = natures.includes('CADEAUX_AVANTAGES');

                    if (besoinSociete && !$('input[name="personne_societe_concernee"]').value.trim())
                        errors.push("Veuillez préciser la société ou l'organisation concernée.");
                    if (besoinClient && !$('input[name="client_fournisseur_partenaire"]').value.trim())
                        errors.push('Veuillez préciser le client, fournisseur ou partenaire.');

                    if (besoinRelation) {
                        const relType = form.querySelector('input[name="relation_type"]:checked')?.value;
                        if (!relType) errors.push('Veuillez préciser si la relation est interne ou externe.');
                        else if (relType === 'INTERNE' && !$('select[name="relation_agent_id"]').value)
                            errors.push("Veuillez sélectionner l'agent concerné.");
                        else if (relType === 'EXTERNE' && !$('input[name="relation_nom_externe"]').value.trim())
                            errors.push('Veuillez préciser le nom et prénoms de la personne.');
                    }

                    if (besoinCadeaux) {
                        const cadType = form.querySelector('input[name="cadeau_type"]:checked')?.value;
                        if (!cadType) errors.push(
                            'Veuillez préciser si les cadeaux sont internes ou externes.');
                        else if (cadType === 'INTERNE' && !$('select[name="cadeau_agent_id"]').value)
                            errors.push("Veuillez sélectionner l'agent concerné.");
                        else if (cadType === 'EXTERNE' && !$('input[name="cadeau_nom_externe"]').value.trim())
                            errors.push('Veuillez préciser le nom et prénoms de la personne.');
                    }

                    if ($('textarea[name="description_detaillee"]').value.trim().length < 10)
                        errors.push('Description détaillée : minimum 10 caractères.');
                    if (!form.querySelector('input[name="influence_decisions"]:checked'))
                        errors.push('Veuillez préciser si la situation influence vos décisions.');
                }
            }

            if (step === 3) {
                if (!$('#auto_lecture').checked) errors.push('Veuillez accepter la notice de confidentialité.');
                if (!$('#auto_autorisation').checked) errors.push(
                    'Veuillez autoriser le traitement des données.');
            }

            return errors;
        }

        /* ================================================================
           RÉCAPITULATIF
        ================================================================ */
        function buildRecap() {
            const $ = (sel) => form.querySelector(sel);
            const rep = form.querySelector('input[name="reponse_q1"]:checked')?.value;
            let html = '';
            const row = (l, v) => `<div class="ci-recap-row"><strong>${l}</strong><span>${v}</span></div>`;

            html += row('Réponse principale', rep === 'OUI' ?
                '<span style="color:var(--rh-danger)">OUI — conflit</span>' :
                '<span style="color:var(--rh-success)">NON — aucun conflit</span>');

            if (rep === 'OUI') {
                const natures = Array.from(form.querySelectorAll('.nature-checkbox:checked'))
                    .map(c => c.closest('.ci-checkbox').querySelector('span').textContent.trim());
                if (natures.length) html += row('Nature(s)', natures.join(' • '));
                const desc = $('textarea[name="description_detaillee"]')?.value.trim() || '';
                if (desc) html += row('Description', desc.length > 120 ? desc.substring(0, 120) + '…' : desc);
            }

            //             else if (rep === 'NON') {
            // // alert("on est ici");
            //                 html += row('Certification', $('#auto_certification').checked ? '✓ Signée' : '✗ Non signée');


            //             }
            else if (rep === 'NON') {
                html += row('Certification', $('#auto_certification').checked ? '✓ Signée' : '✗ Non signée');
            }

            html += row('Notice', $('#auto_lecture').checked ? '✓ Acceptée' : '✗ Non acceptée');
            html += row('Traitement', $('#auto_autorisation').checked ? '✓ Autorisé' : '✗ Non autorisé');

            const recapEl = document.getElementById('auto_recap');
            if (recapEl) recapEl.innerHTML = html;
        }

        /* ================================================================
           NAVIGATION
        ================================================================ */
        if (btnPrev) {
            btnPrev.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    updateStepper();
                }
            });
        }

        if (btnNext) {
            btnNext.addEventListener('click', () => {
                const errs = validateStep(currentStep);
                if (errs.length) {
                    alert(errs.join('\n'));
                    return;
                }
                if (currentStep < TOTAL_STEPS) {
                    currentStep++;
                    updateStepper();
                }
            });
        }

        form.addEventListener('submit', (e) => {
            // Validation complète avant envoi
            for (let i = 1; i <= TOTAL_STEPS; i++) {
                const errs = validateStep(i);
                if (errs.length) {
                    e.preventDefault();
                    alert(errs.join('\n'));
                    currentStep = i;
                    updateStepper();
                    return;
                }
            }

            // Engagement final
            if (!document.getElementById('auto_engagement').checked) {
                e.preventDefault();
                alert("Veuillez cocher la case d'engagement.");
                return;
            }

            // ✅ Si tout est OK, le formulaire part vers Laravel
        });

        /* ================================================================
           INITIALISATION
        ================================================================ */
        initDynamicLinks();

        // ⭐ Si une réponse est déjà pré-sélectionnée (retour après erreur ou édition),
        //    on saute directement à l'étape 2
        const reponseInitiale = form.querySelector('input[name="reponse_q1"]:checked');
        if (reponseInitiale) {
            currentStep = 2;
        }

        updateStepper();
    });
</script>
