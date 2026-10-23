@php
    use Carbon\Carbon;

    /* Helpers */
    $formatDate     = fn($d) => $d ? Carbon::parse($d)->format('d/m/Y') : '—';
    $formatDateTime = fn($d) => $d ? Carbon::parse($d)->format('d/m/Y à H:i') : '—';

    /* Type */
    $reftype = $declaration->reference
        ? strtoupper(explode('-', $declaration->reference)[0] ?? '')
        : '';
    $isDen  = $reftype === 'DEN';
    $isOui  = $declaration->reponse_q1 === 'OUI';

    /* Raccourcis */
    $conflit = $declaration->conflit;
    $honneur = $declaration->declarationHonneur;

    /* Natures */
    $naturesLabels = [
        'PARTICIPATION_FINANCIERE' => 'Participation financière',
        'LIEN_FAMILIAL'            => 'Lien familial',
        'ACTIVITE_EXTERNE'         => 'Activité externe',
        'FONCTION_ADMINISTRATEUR'  => "Fonction d'administrateur",
        'RELATION_PERSONNELLE'     => 'Relation personnelle',
        'CADEAUX_AVANTAGES'        => 'Cadeaux / avantages',
        'AUTRE'                    => 'Autre',
    ];

    $natures = [];
    if ($conflit?->nature_conflit) {
        $natures = is_string($conflit->nature_conflit)
            ? (json_decode($conflit->nature_conflit, true) ?? [])
            : $conflit->nature_conflit;
    }
    $natures = is_array($natures) ? $natures : [];

    /* Agents */
    $relationAgent = $conflit?->relationAgent;
    $cadeauAgent   = $conflit?->cadeauAgent;
    $agentDenonce  = $conflit?->agentDenonce;

    /* DEN */
    $denonceNom      = $conflit?->agent_denonce_nom;
    $denonceFonction = $conflit?->agent_denonce_fonction;
    $niveauUrgence   = $conflit?->niveau_urgence;
    $dateFaits       = $conflit?->date_faits;
    $lieuFaits       = $conflit?->lieu_faits;
    $impactEstime    = $conflit?->impact_estime;
    $dejaSignale     = $conflit?->deja_signale ?? false;
    $signaleA        = $conflit?->signale_a;
    $protectionDen   = $conflit?->protection_denonciateur ?? false;
    $souhaiteAnonyme = $conflit?->souhaite_anonyme ?? false;

    $temoins = $conflit?->temoins ?? [];
    if (is_string($temoins)) $temoins = json_decode($temoins, true) ?? [];
    $temoins = is_array($temoins) ? $temoins : [];

    /* Statut */
    $statutMap = [
        'SOUMISE'  => ['label' => 'Soumise',  'icon' => 'bx-send',         'badge' => 'ci-badge-info'],
        'MODIFIEE' => ['label' => 'Modifiée', 'icon' => 'bx-edit',         'badge' => 'ci-badge-warn'],
        'VALIDEE'  => ['label' => 'Validée',  'icon' => 'bx-check-double', 'badge' => 'ci-badge-ok'],
        'REJETEE'  => ['label' => 'Rejetée',  'icon' => 'bx-x',            'badge' => 'ci-badge-ko'],
    ];
    $statut = $statutMap[$declaration->statut] ?? [
        'label' => $declaration->statut,
        'icon'  => 'bx-info-circle',
        'badge' => 'ci-badge-info',
    ];

    /* Urgence */
    $urgenceClass = [
        'FAIBLE'   => 'ci-badge-ok',
        'MOYEN'    => 'ci-badge-info',
        'ELEVE'    => 'ci-badge-warn',
        'CRITIQUE' => 'ci-badge-ko',
    ][$niveauUrgence ?? 'MOYEN'] ?? 'ci-badge-info';
@endphp

<div class="ci-dynamic-block">
    <div class="ci-block-title">
        <i class="bx bx-list-check"></i> Récapitulatif
    </div>

    {{-- ============================================================
         1. MÉTADONNÉES
    ============================================================ --}}
    <div class="ci-recap mb-3">
        <div class="ci-recap-row">
            <strong>Référence</strong>
            <span class="font-monospace">{{ $declaration->reference ?? '—' }}</span>
        </div>
        <div class="ci-recap-row">
            <strong>Type</strong>
            <span>
                <span class="ci-badge {{ $isDen ? 'ci-badge-ko' : 'ci-badge-info' }}">
                    <i class="bx {{ $isDen ? 'bx-error-alt' : 'bx-user-check' }}"></i>
                    {{ $isDen ? 'Dénonciation' : 'Auto-déclaration' }}
                </span>
            </span>
        </div>
        <div class="ci-recap-row">
            <strong>Statut</strong>
            <span>
                <span class="ci-badge {{ $statut['badge'] }}">
                    <i class="bx {{ $statut['icon'] }}"></i> {{ $statut['label'] }}
                </span>
            </span>
        </div>
        <div class="ci-recap-row">
            <strong>Déclarant</strong>
            <span>{{ $declaration->nom_prenoms }} — {{ $declaration->matricule }}</span>
        </div>
        <div class="ci-recap-row">
            <strong>Soumise le</strong>
            <span>{{ $formatDateTime($declaration->date_soumission) }}</span>
        </div>
    </div>

    {{-- ============================================================
         2. CAS : AUCUN CONFLIT (Q1 = NON)
    ============================================================ --}}
    @if (! $isOui)
        <div class="ci-honneur">
            <strong>Aucun conflit d'intérêts déclaré</strong>
            Déclaration sur l'honneur signée le
            {{ $honneur?->date_certification ? $formatDateTime($honneur->date_certification) : '—' }}.
        </div>

    {{-- ============================================================
         3. CAS : CONFLIT DÉCLARÉ (Q1 = OUI)
    ============================================================ --}}
    @else
        {{-- 3a. Bloc agent dénoncé (DEN uniquement) --}}
        @if ($isDen)
            <div class="ci-recap mb-3" style="border-top:1px dashed var(--rh-border);padding-top:.75rem;">
                <div class="ci-recap-row">
                    <strong>Agent concerné</strong>
                    <span>
                        @if ($agentDenonce)
                            {{ $agentDenonce->nom }} {{ $agentDenonce->prenom }}
                            <small class="text-muted">({{ $agentDenonce->matricule }})</small>
                        @elseif ($denonceNom)
                            {{ $denonceNom }}
                            @if ($denonceFonction) <small class="text-muted">— {{ $denonceFonction }}</small> @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </span>
                </div>
                @if ($niveauUrgence)
                    <div class="ci-recap-row">
                        <strong>Urgence</strong>
                        <span>
                            <span class="ci-badge {{ $urgenceClass }}">
                                <i class="bx bx-error"></i> {{ $niveauUrgence }}
                            </span>
                        </span>
                    </div>
                @endif
                @if ($dateFaits)
                    <div class="ci-recap-row">
                        <strong>Date des faits</strong>
                        <span>{{ $formatDate($dateFaits) }}</span>
                    </div>
                @endif
                @if ($lieuFaits)
                    <div class="ci-recap-row">
                        <strong>Lieu des faits</strong>
                        <span>{{ $lieuFaits }}</span>
                    </div>
                @endif
            </div>
        @endif

        {{-- 3b. Détail du conflit --}}
        <div class="ci-recap">
            <div class="ci-recap-row">
                <strong>Nature(s)</strong>
                <span>
                    @forelse ($natures as $n)
                        <span class="ci-tag">{{ $naturesLabels[$n] ?? $n }}</span>
                    @empty
                        <span class="text-muted">—</span>
                    @endforelse
                </span>
            </div>

            <div class="ci-recap-row">
                <strong>{{ $isDen ? 'Faits constatés' : 'Description' }}</strong>
                <span style="white-space:pre-line;">{{ $conflit?->description_detaillee ?? '—' }}</span>
            </div>

            @if ($conflit?->personne_societe_concernee)
                <div class="ci-recap-row">
                    <strong>Société concernée</strong>
                    <span>{{ $conflit->personne_societe_concernee }}</span>
                </div>
            @endif

            @if ($conflit?->client_fournisseur_partenaire)
                <div class="ci-recap-row">
                    <strong>Client / fournisseur</strong>
                    <span>{{ $conflit->client_fournisseur_partenaire }}</span>
                </div>
            @endif

            @if ($conflit?->relation_type)
                <div class="ci-recap-row">
                    <strong>Relation personnelle</strong>
                    <span>
                        <span class="ci-badge {{ $conflit->relation_type === 'INTERNE' ? 'ci-badge-info' : 'ci-badge-warn' }}">
                            {{ $conflit->relation_type === 'INTERNE' ? 'Interne' : 'Externe' }}
                        </span>
                        @if ($conflit->relation_type === 'INTERNE' && $relationAgent)
                            — {{ $relationAgent->nom }} {{ $relationAgent->prenom }}
                        @elseif ($conflit->relation_nom_externe)
                            — {{ $conflit->relation_nom_externe }}
                        @endif
                    </span>
                </div>
            @endif

            @if ($conflit?->cadeau_type)
                <div class="ci-recap-row">
                    <strong>Cadeaux / avantages</strong>
                    <span>
                        <span class="ci-badge {{ $conflit->cadeau_type === 'INTERNE' ? 'ci-badge-info' : 'ci-badge-warn' }}">
                            {{ $conflit->cadeau_type === 'INTERNE' ? 'Interne' : 'Externe' }}
                        </span>
                        @if ($conflit->cadeau_type === 'INTERNE' && $cadeauAgent)
                            — {{ $cadeauAgent->nom }} {{ $cadeauAgent->prenom }}
                        @elseif ($conflit->cadeau_nom_externe)
                            — {{ $conflit->cadeau_nom_externe }}
                        @endif
                    </span>
                </div>
            @endif

            @if ($conflit?->date_debut_situation)
                <div class="ci-recap-row">
                    <strong>Début situation</strong>
                    <span>{{ $formatDate($conflit->date_debut_situation) }}</span>
                </div>
            @endif

            <div class="ci-recap-row">
                <strong>Influence décisions</strong>
                <span>
                    @if ($conflit?->influence_decisions === 'OUI')
                        <span class="ci-badge ci-badge-ko">Oui</span>
                    @else
                        <span class="ci-badge ci-badge-ok">Non</span>
                    @endif
                </span>
            </div>

            @if ($conflit?->mesures_prises)
                <div class="ci-recap-row">
                    <strong>Mesures prises</strong>
                    <span style="white-space:pre-line;">{{ $conflit->mesures_prises }}</span>
                </div>
            @endif

            {{-- DEN : impact + déjà signalé + témoins --}}
            @if ($isDen)
                @if ($impactEstime)
                    <div class="ci-recap-row">
                        <strong>Impact estimé</strong>
                        <span style="white-space:pre-line;">{{ $impactEstime }}</span>
                    </div>
                @endif

                @if ($dejaSignale)
                    <div class="ci-recap-row">
                        <strong>Déjà signalé</strong>
                        <span>
                            <span class="ci-badge ci-badge-warn">Oui</span>
                            @if ($signaleA) <small class="text-muted">— {{ $signaleA }}</small> @endif
                        </span>
                    </div>
                @endif

                @if (count($temoins) > 0)
                    <div class="ci-recap-row">
                        <strong>Témoins</strong>
                        <span>
                            @foreach ($temoins as $t)
                                <span class="ci-tag">{{ $t }}</span>
                            @endforeach
                        </span>
                    </div>
                @endif
            @endif
        </div>
    @endif

    {{-- ============================================================
         4. ENGAGEMENTS / PROTECTION
    ============================================================ --}}
    <div class="ci-recap mt-3" style="border-top:1px dashed var(--rh-border);padding-top:.75rem;">

        {{-- Honneur (si NON) --}}
        @if (! $isOui)
            <div class="ci-recap-row">
                <strong>Certification honneur</strong>
                <span>
                    @if ($honneur?->certification_honneur)
                        <span class="ci-badge ci-badge-ok"><i class="bx bx-check"></i> Signée</span>
                    @else
                        <span class="ci-badge ci-badge-ko"><i class="bx bx-x"></i> Non signée</span>
                    @endif
                </span>
            </div>
            <div class="ci-recap-row">
                <strong>Engagement signalement</strong>
                <span>
                    @if ($honneur?->engagement_signalement)
                        <span class="ci-badge ci-badge-ok"><i class="bx bx-check"></i> Signé</span>
                    @else
                        <span class="ci-badge ci-badge-ko"><i class="bx bx-x"></i> Non signé</span>
                    @endif
                </span>
            </div>
        @endif

        {{-- Protection (DEN) --}}
        @if ($isDen)
            <div class="ci-recap-row">
                <strong>Anonymat</strong>
                <span>
                    @if ($souhaiteAnonyme)
                        <span class="ci-badge ci-badge-warn"><i class="bx bx-hide"></i> Demandé</span>
                    @else
                        <span class="text-muted">Non demandé</span>
                    @endif
                </span>
            </div>
            <div class="ci-recap-row">
                <strong>Protection lanceur</strong>
                <span>
                    @if ($protectionDen)
                        <span class="ci-badge ci-badge-ok"><i class="bx bx-shield-quarter"></i> Demandée</span>
                    @else
                        <span class="text-muted">Non demandée</span>
                    @endif
                </span>
            </div>
        @endif
    </div>
</div>
