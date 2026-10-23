@php
    $selectedNatures = old('nature_conflit', []);
@endphp

<div class="ci-progress-bar-container">
    <div class="ci-steps" id="stepperDen"></div>
    <div class="ci-progress-line">
        <div class="ci-progress-line-fill" id="progressFillDen"></div>
    </div>
</div>

<form id="formDen" action="{{ route('conflits.storeDenonciation') }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf

    {{-- ÉTAPE 1 : Dénonciateur --}}
    <div class="ci-step-panel active" data-step="1">
        <div class="ci-card">
            <div class="ci-card-header">
                <h6><i class="bx bx-user-circle"></i> 1. Identification du dénonciateur</h6>
                <span class="ci-step-badge">Étape 1 / 5</span>
            </div>
            <div class="ci-card-body">
                <div class="ci-info-banner den mb-3">
                    <i class="bx bx-lock"></i>
                    <div>Vos informations personnelles ne seront pas communiquées à l'agent concerné.</div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="ci-label">Nom et Prénoms</label>
                        <input type="text" class="form-control ci-readonly" value="{{ $nomComplet ?? '' }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="ci-label">Matricule</label>
                        <input type="text" class="form-control ci-readonly" value="{{ $agent?->matricule ?? '—' }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="ci-label">Fonction</label>
                        <input type="text" class="form-control ci-readonly" value="{{ $fonction ?? '—' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="ci-label">Direction</label>
                        <input type="text" class="form-control ci-readonly" value="{{ $direction ?? '—' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="ci-label">E-mail professionnel</label>
                        <input type="email" class="form-control ci-readonly" value="{{ $contact?->email_pro ?? '—' }}" readonly>
                    </div>
                </div>
                <div class="ci-consent-item mt-3">
                    <input type="checkbox" id="den_anonyme" name="souhaite_anonyme" value="1" @checked(old('souhaite_anonyme'))>
                    <label for="den_anonyme"><i class="bx bx-hide"></i> Je souhaite rester anonyme vis-à-vis de l'agent dénoncé</label>
                </div>
            </div>
        </div>
    </div>

    {{-- ÉTAPE 2 : Agent dénoncé --}}
    <div class="ci-step-panel" data-step="2">
        <div class="ci-card">
            <div class="ci-card-header">
                <h6><i class="bx bx-user-x"></i> 2. Identification de l'agent dénoncé</h6>
                <span class="ci-step-badge">Étape 2 / 5</span>
            </div>
            <div class="ci-card-body">
                <div class="mb-4">
                    <label class="ci-label"><span class="required">*</span> Sélectionnez l'agent concerné par la dénonciation</label>
                    <select class="form-select" id="den_agent_id" name="agent_denonce_id">
                        <option value="">— Rechercher un agent —</option>
                        @foreach ($agents as $a)
                            <option value="{{ $a->id }}" @selected(old('agent_denonce_id') == $a->id)>
                                {{ $a->nom }} {{ $a->prenom }} - {{ $a->matricule }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text"><i class="bx bx-info-circle"></i> Si l'agent n'est pas dans la liste, cochez la case ci-dessous.</div>
                    @error('agent_denonce_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="ci-consent-item">
                    <input type="checkbox" id="den_agent_externe" name="agent_externe" value="1" @checked(old('agent_externe'))>
                    <label for="den_agent_externe">L'agent concerné n'est pas dans la liste (externe, ancien agent, prestataire...)</label>
                </div>
                <div id="den_agent_externe_bloc" class="ci-section-hidden mt-3">
                    <div class="ci-dynamic-block">
                        <div class="ci-block-title"><i class="bx bx-user-plus"></i> Informations sur la personne</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="ci-label"><span class="required">*</span> Nom et prénoms</label>
                                <input type="text" class="form-control" name="agent_denonce_nom" maxlength="255"
                                       value="{{ old('agent_denonce_nom') }}" placeholder="Ex. M. KONE Ibrahim">
                                @error('agent_denonce_nom')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="ci-label">Fonction / société</label>
                                <input type="text" class="form-control" name="agent_denonce_fonction" maxlength="255"
                                       value="{{ old('agent_denonce_fonction') }}" placeholder="Ex. Directeur Commercial">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ÉTAPE 3 : Faits --}}
    <div class="ci-step-panel" data-step="3">
        <div class="ci-card">
            <div class="ci-card-header">
                <h6><i class="bx bx-file-find"></i> 3. Faits constatés</h6>
                <span class="ci-step-badge">Étape 3 / 5</span>
            </div>
            <div class="ci-card-body">
                <div class="mb-4">
                    <label class="ci-label d-block mb-3"><span class="required">*</span> Nature du conflit observé</label>
                    <div class="ci-checkbox-group">
                        @foreach ($natures as $key => $label)
                            <label class="ci-checkbox">
                                <input type="checkbox" name="nature_conflit[]" value="{{ $key }}"
                                       class="nature-checkbox" data-nature="{{ $key }}"
                                       @checked(in_array($key, (array) $selectedNatures))>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('nature_conflit')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="ci-label"><span class="required">*</span> Description détaillée des faits constatés</label>
                    <textarea class="form-control" name="faits_constates" rows="5" maxlength="3000"
                              placeholder="Décrivez précisément les faits observés...">{{ old('faits_constates') }}</textarea>
                    <div class="ci-char-count"><span class="count">0</span> / 3000 caractères (min. 50)</div>
                    @error('faits_constates')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="ci-label">Date des faits <span class="ci-optional">(optionnel)</span></label>
                        <input type="date" class="form-control" name="date_faits" value="{{ old('date_faits') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="ci-label">Lieu des faits <span class="ci-optional">(optionnel)</span></label>
                        <input type="text" class="form-control" name="lieu_faits" maxlength="255"
                               value="{{ old('lieu_faits') }}" placeholder="Ex. Siège social, Agence Cocody...">
                    </div>
                </div>

                {{-- Témoins --}}
                <div class="mb-4" hidden>
                    <label class="ci-label d-block mb-2">Témoins éventuels <span class="ci-optional">(optionnel)</span></label>
                    <div id="den_temoins_container">
                        @if (old('temoins'))
                            @foreach (old('temoins') as $t)
                                <div class="ci-temoin-row">
                                    <input type="text" class="form-control" name="temoins[]" value="{{ $t }}" placeholder="Nom et prénoms du témoin">
                                    <button type="button" onclick="this.parentElement.remove(); DenApp.decrementTemoin();">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" class="ci-btn ci-btn-outline" id="den_add_temoin">
                        <i class="bx bx-plus"></i> Ajouter un témoin
                    </button>
                </div>

                {{-- Preuves --}}
                <div class="mb-4" hidden>
                    <label class="ci-label d-block mb-2">Pièces justificatives <span class="ci-optional">(optionnel)</span></label>
                    <div class="ci-upload-zone" id="den_upload_zone">
                        <i class="bx bx-cloud-upload"></i>
                        <p><strong>Cliquez</strong> ou glissez-déposez vos fichiers ici</p>
                        <small>PDF, JPG, PNG — Max 5 Mo par fichier (5 fichiers max)</small>
                        <input type="file" id="den_files" name="fichiers[]" multiple accept=".pdf,.jpg,.jpeg,.png" style="display:none;">
                    </div>
                    <ul class="ci-upload-list" id="den_files_list"></ul>
                    @error('fichiers.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                {{-- Urgence --}}
                <div class="mb-4">
                    <label class="ci-label d-block mb-3"><span class="required">*</span> Niveau d'urgence</label>
                    <div class="ci-radio-group">
                        <div class="ci-radio">
                            <input type="radio" id="den_urg_faible" name="niveau_urgence" value="FAIBLE" @checked(old('niveau_urgence') === 'FAIBLE')>
                            <label for="den_urg_faible"><i class="bx bx-time"></i> Faible</label>
                        </div>
                        <div class="ci-radio">
                            <input type="radio" id="den_urg_moyen" name="niveau_urgence" value="MOYEN" @checked(old('niveau_urgence') === 'MOYEN')>
                            <label for="den_urg_moyen"><i class="bx bx-error"></i> Moyen</label>
                        </div>
                        <div class="ci-radio">
                            <input type="radio" id="den_urg_eleve" name="niveau_urgence" value="ELEVE" @checked(old('niveau_urgence') === 'ELEVE')>
                            <label for="den_urg_eleve"><i class="bx bx-error-circle"></i> Élevé</label>
                        </div>
                        <div class="ci-radio ci-radio-critique">
                            <input type="radio" id="den_urg_critique" name="niveau_urgence" value="CRITIQUE" @checked(old('niveau_urgence') === 'CRITIQUE')>
                            <label for="den_urg_critique"><i class="bx bx-alarm-exclamation"></i> Critique</label>
                        </div>
                    </div>
                    @error('niveau_urgence')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="ci-label">Impact estimé <span class="ci-optional">(optionnel)</span></label>
                    <textarea class="form-control" name="impact_estime" rows="3" maxlength="1000"
                              placeholder="Ex. Risque financier...">{{ old('impact_estime') }}</textarea>
                </div>

                <div class="ci-consent-item mb-3">
                    <input type="checkbox" id="den_deja_signale" name="deja_signale" value="1" @checked(old('deja_signale'))>
                    <label for="den_deja_signale">Cette situation a déjà été signalée en interne</label>
                </div>
                <div id="den_signale_bloc" class="ci-section-hidden">
                    <div class="ci-dynamic-block">
                        <label class="ci-label">À qui a-t-elle été signalée ?</label>
                        <input type="text" class="form-control" name="signale_a" maxlength="255"
                               value="{{ old('signale_a') }}" placeholder="Ex. Manager, RH, Compliance...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ÉTAPE 4 : Consentement --}}
    <div class="ci-step-panel" data-step="4">
        <div class="ci-card">
            <div class="ci-card-header">
                <h6><i class="bx bx-lock-alt"></i> 4. Consentement et protection</h6>
                <span class="ci-step-badge">Étape 4 / 5</span>
            </div>
            <div class="ci-card-body">
                <div class="ci-consent-box">
                    <div class="ci-notice">
                        <h5>Notice spécifique — Dénonciation de conflit d'intérêts</h5>
                        <p>Cette procédure s'inscrit dans le dispositif de <strong>protection du lanceur d'alerte</strong> de YAKO AFRICA.</p>
                        <ul>
                            <li>Aucune mesure de rétorsion ne peut être prise contre un déclarant de bonne foi ;</li>
                            <li>Votre identité ne sera pas divulguée à l'agent dénoncé sans votre accord ;</li>
                            <li>Vous pouvez demander à rester anonyme vis-à-vis de l'agent concerné ;</li>
                            <li>Vous pouvez demander la protection du lanceur d'alerte.</li>
                        </ul>
                    </div>
                    <div class="ci-consent-item">
                        <input type="checkbox" id="den_lecture" name="lecture_notice" value="1" @checked(old('lecture_notice'))>
                        <label for="den_lecture"><span class="required">*</span> J'ai lu et j'accepte la présente notice de confidentialité.</label>
                    </div>
                    @error('lecture_notice')<div class="text-danger small mt-1 mb-2">{{ $message }}</div>@enderror
                    <div class="ci-consent-item">
                        <input type="checkbox" id="den_autorisation" name="autorisation_traitement" value="1" @checked(old('autorisation_traitement'))>
                        <label for="den_autorisation"><span class="required">*</span> J'autorise le traitement de mes données.</label>
                    </div>
                    @error('autorisation_traitement')<div class="text-danger small mt-1 mb-2">{{ $message }}</div>@enderror
                    <div class="ci-consent-item">
                        <input type="checkbox" id="den_protection" name="protection_denonciateur" value="1" @checked(old('protection_denonciateur'))>
                        <label for="den_protection"><i class="bx bx-shield-quarter"></i> Je demande la protection du lanceur d'alerte.</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ÉTAPE 5 : Engagement --}}
    <div class="ci-step-panel" data-step="5">
        <div class="ci-card">
            <div class="ci-card-header">
                <h6><i class="bx bx-check-shield"></i> 5. Déclaration et engagement</h6>
                <span class="ci-step-badge">Étape 5 / 5</span>
            </div>
            <div class="ci-card-body">
                <div class="ci-honneur danger">
                    <strong>Engagement du dénonciateur</strong>
                    Je certifie que les faits rapportés sont exacts, rapportés de bonne foi et sans intention de nuire. Je comprends que toute fausse déclaration intentionnelle pourrait entraîner des sanctions disciplinaires.
                </div>
                <div class="ci-consent-item">
                    <input type="checkbox" id="den_engagement" name="engagement_declarant" value="1" @checked(old('engagement_declarant'))>
                    <label for="den_engagement"><span class="required">*</span> Je certifie l'exactitude des faits rapportés et déclare agir de bonne foi.</label>
                </div>
                @error('engagement_declarant')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                <div class="ci-dynamic-block mt-4">
                    <div class="ci-block-title"><i class="bx bx-list-check"></i> Récapitulatif</div>
                    <div class="ci-recap" id="den_recap"><em class="text-muted">Le récapitulatif s'affichera automatiquement.</em></div>
                </div>
            </div>
        </div>
    </div>

    {{-- NAV --}}
    <div class="ci-nav-bar">
        <button type="button" class="ci-btn ci-btn-outline" data-action="prev">
            <i class="bx bx-chevron-left"></i> Précédent
        </button>
        <div class="ci-nav-info" data-info>Étape 1 sur 5</div>
        <div class="d-flex gap-2">
            <a href="{{ route('conflits.index') }}" class="ci-btn ci-btn-outline d-none d-sm-inline-flex">
                <i class="bx bx-x"></i> Annuler
            </a>
            <button type="button" class="ci-btn ci-btn-danger" data-action="next">
                Suivant <i class="bx bx-chevron-right"></i>
            </button>
            <button type="submit" class="ci-btn ci-btn-gold ci-section-hidden" data-action="submit">
                <i class="bx bx-send"></i> Soumettre la dénonciation
            </button>
        </div>
    </div>
</form>

<script>
/* ====================================================================
   DEN — Wizard 5 étapes
   ==================================================================== */
const DenApp = (function () {
    const TOTAL_STEPS = 5;
    const STEPS = ['Dénonciateur', 'Agent visé', 'Faits', 'Consentement', 'Engagement'];
    const form = document.getElementById('formDen');
    const panels = form.querySelectorAll('.ci-step-panel');
    const progressFill = document.getElementById('progressFillDen');
    const info = form.querySelector('[data-info]');
    const btnPrev = form.querySelector('[data-action="prev"]');
    const btnNext = form.querySelector('[data-action="next"]');
    const btnSubmit = form.querySelector('[data-action="submit"]');
    let currentStep = 1;
    let temoinCounter = 0;
    let tempFiles = [];

    /* Stepper */
    const stepper = document.getElementById('stepperDen');
    STEPS.forEach((label, i) => {
        const step = i + 1;
        const div = document.createElement('div');
        div.className = 'ci-step';
        div.dataset.step = step;
        div.innerHTML = `<div class="ci-step-circle">${step}</div><div class="ci-step-label">${label}</div>`;
        div.addEventListener('click', () => {
            if (step < currentStep) { currentStep = step; updateStepper(); }
            else if (step > currentStep) {
                for (let i = currentStep; i < step; i++) {
                    const errs = validateStep(i);
                    if (errs.length) { alert(errs.join('\n')); return; }
                }
                currentStep = step; updateStepper();
            }
        });
        stepper.appendChild(div);
    });

    function updateStepper() {
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
        progressFill.style.width = ((currentStep / TOTAL_STEPS) * 100) + '%';
        info.textContent = `Étape ${currentStep} sur ${TOTAL_STEPS}`;
        btnPrev.disabled = currentStep === 1;
        if (currentStep === TOTAL_STEPS) {
            btnNext.classList.add('ci-section-hidden');
            btnSubmit.classList.remove('ci-section-hidden');
            buildRecap();
        } else {
            btnNext.classList.remove('ci-section-hidden');
            btnSubmit.classList.add('ci-section-hidden');
        }
        panels.forEach(p => p.classList.toggle('active', parseInt(p.dataset.step) === currentStep));
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(step) {
        const errors = [];
        const $ = (sel) => form.querySelector(sel);

        if (step === 2) {
            const agentExterne = $('#den_agent_externe').checked;
            if (!agentExterne && !$('#den_agent_id').value) errors.push('Veuillez sélectionner un agent.');
            if (agentExterne && !$('input[name="agent_denonce_nom"]').value.trim()) errors.push("Veuillez préciser le nom et prénoms.");
        }
        if (step === 3) {
            if (!form.querySelectorAll('.nature-checkbox:checked').length) errors.push('Veuillez sélectionner au moins une nature.');
            if ($('textarea[name="faits_constates"]').value.trim().length < 50) errors.push('Description des faits : minimum 50 caractères.');
            if (!form.querySelector('input[name="niveau_urgence"]:checked')) errors.push("Veuillez préciser le niveau d'urgence.");
        }
        if (step === 4) {
            if (!$('#den_lecture').checked) errors.push('Veuillez accepter la notice.');
            if (!$('#den_autorisation').checked) errors.push('Veuillez autoriser le traitement.');
        }

        return errors;
    }

    function buildRecap() {
        const $ = (sel) => form.querySelector(sel);
        const agentSel = $('#den_agent_id');
        const agentNom = agentSel.options[agentSel.selectedIndex]?.text || '—';
        const natures = Array.from(form.querySelectorAll('.nature-checkbox:checked'))
            .map(c => c.closest('.ci-checkbox').querySelector('span').textContent.trim());
        const faits = $('textarea[name="faits_constates"]')?.value.trim() || '';
        const urgence = form.querySelector('input[name="niveau_urgence"]:checked')?.value;

        const row = (l, v) => `<div class="ci-recap-row"><strong>${l}</strong><span>${v}</span></div>`;
        let html = '';
        html += row('Agent concerné', agentNom);
        if ($('#den_anonyme')?.checked) html += row('Anonymat', '✓ Demandé');
        if (natures.length) html += row('Nature(s)', natures.join(' • '));
        if (faits) html += row('Faits', faits.length > 120 ? faits.substring(0, 120) + '…' : faits);
        if (urgence) html += row('Urgence', urgence);
        html += row('Témoins', temoinCounter);
        html += row('Pièces jointes', tempFiles.length);
        if ($('#den_protection')?.checked) html += row('Protection lanceur', '✓ Demandée');
        html += row('Notice', $('#den_lecture').checked ? '✓ Acceptée' : '✗ Non acceptée');
        html += row('Traitement', $('#den_autorisation').checked ? '✓ Autorisé' : '✗ Non autorisé');
        document.getElementById('den_recap').innerHTML = html;
    }

    function initDynamicLinks() {
        // Agent externe
        const agentExt = document.getElementById('den_agent_externe');
        const agentExtBloc = document.getElementById('den_agent_externe_bloc');
        const agentSelect = document.getElementById('den_agent_id');
        const toggleAgent = () => {
            const isExt = agentExt.checked;
            agentExtBloc.classList.toggle('ci-section-hidden', !isExt);
            agentSelect.disabled = isExt;
            agentExtBloc.querySelectorAll('input').forEach(i => i.disabled = !isExt);
        };
        agentExt.addEventListener('change', toggleAgent);
        toggleAgent();

        // Déjà signalé
        const dejaSignale = document.getElementById('den_deja_signale');
        const signaleBloc = document.getElementById('den_signale_bloc');
        const toggleSignale = () => {
            signaleBloc.classList.toggle('ci-section-hidden', !dejaSignale.checked);
            signaleBloc.querySelectorAll('input').forEach(i => i.disabled = !dejaSignale.checked);
        };
        dejaSignale.addEventListener('change', toggleSignale);
        toggleSignale();

        // Témoins
        const temoinsContainer = document.getElementById('den_temoins_container');
        document.getElementById('den_add_temoin').addEventListener('click', () => {
            temoinCounter++;
            const row = document.createElement('div');
            row.className = 'ci-temoin-row';
            row.innerHTML = `
                <input type="text" class="form-control" name="temoins[]" placeholder="Nom et prénoms du témoin">
                <button type="button" onclick="this.parentElement.remove(); DenApp.decrementTemoin();">
                    <i class="bx bx-trash"></i>
                </button>
            `;
            temoinsContainer.appendChild(row);
        });

        // Upload
        const uploadZone = document.getElementById('den_upload_zone');
        const fileInput = document.getElementById('den_files');
        const fileList = document.getElementById('den_files_list');
        uploadZone.addEventListener('click', () => fileInput.click());
        uploadZone.addEventListener('dragover', e => { e.preventDefault(); uploadZone.style.borderColor = 'var(--rh-primary)'; });
        uploadZone.addEventListener('dragleave', () => uploadZone.style.borderColor = '');
        uploadZone.addEventListener('drop', e => { e.preventDefault(); uploadZone.style.borderColor = ''; handleFiles(e.dataTransfer.files); });
        fileInput.addEventListener('change', e => handleFiles(e.target.files));

        function handleFiles(files) {
            Array.from(files).forEach(f => {
                if (f.size > 5 * 1024 * 1024) { alert(`Fichier trop volumineux : ${f.name}`); return; }
                if (tempFiles.length >= 5) { alert('Maximum 5 fichiers.'); return; }
                tempFiles.push(f);
                renderFiles();
            });
        }

        function renderFiles() {
            fileList.innerHTML = '';
            tempFiles.forEach((f, idx) => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <i class="bx bx-file"></i>
                    <span>${f.name}</span>
                    <small style="color:var(--rh-muted);margin-left:.5rem;">(${(f.size/1024).toFixed(1)} Ko)</small>
                    <button type="button" class="ci-remove" data-idx="${idx}"><i class="bx bx-x"></i></button>
                `;
                li.querySelector('.ci-remove').addEventListener('click', () => {
                    tempFiles.splice(idx, 1);
                    renderFiles();
                });
                fileList.appendChild(li);
            });
        }

        // Compteurs
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

    /* Navigation */
    btnPrev.addEventListener('click', () => { if (currentStep > 1) { currentStep--; updateStepper(); } });
    btnNext.addEventListener('click', () => {
        const errs = validateStep(currentStep);
        if (errs.length) { alert(errs.join('\n')); return; }
        if (currentStep < TOTAL_STEPS) { currentStep++; updateStepper(); }
    });
    form.addEventListener('submit', (e) => {
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
        if (!document.getElementById('den_engagement').checked) {
            e.preventDefault();
            alert("Veuillez cocher la case d'engagement.");
        }
    });

    initDynamicLinks();
    updateStepper();

    return {
        decrementTemoin: () => { temoinCounter = Math.max(0, temoinCounter - 1); }
    };
})();
</script>
