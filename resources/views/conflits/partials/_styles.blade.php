<style>
    /* ============================================================================
   VARIABLES GLOBALES
============================================================================ */
    :root {
        --rh-primary: #033f1f;
        --rh-primary-dark: #012411;
        --rh-primary-light: rgba(3, 63, 31, 0.06);
        --rh-gold: #F9B233;
        --rh-gold-hover: #e09d20;
        --rh-gold-light: rgba(249, 178, 51, 0.12);
        --rh-danger: #dc3545;
        --rh-danger-light: rgba(220, 53, 69, 0.08);
        --rh-success: #198754;
        --rh-info: #0d6efd;
        --rh-warning: #f59e0b;
        --rh-border: #e5e9ef;
        --rh-muted: #64748b;
        --rh-radius: 14px;
    }

    * {
        box-sizing: border-box;
    }

    .page-content {
        /* max-width: 1000px;*/
        margin: 0 auto;
        /* padding: 1.5rem 1rem; */
    }

    .ci-page-title {
        color: var(--rh-primary);
        font-weight: 700;
    }

    /* ============================================================================
   HERO
============================================================================ */
    .ci-hero {
        border-radius: var(--rh-radius);
        padding: 1.75rem 2rem;
        color: #fff;
        background: radial-gradient(circle at 92% 0%, rgba(249, 178, 51, .25) 0, transparent 38%),
            linear-gradient(135deg, var(--rh-primary) 0%, var(--rh-primary-dark) 100%);
        border-bottom: 4px solid var(--rh-gold);
        box-shadow: 0 10px 30px rgba(3, 63, 31, .12);
        margin-bottom: 1.5rem;
    }

    .ci-hero.danger {
        background: radial-gradient(circle at 92% 0%, rgba(249, 178, 51, .25) 0, transparent 38%),
            linear-gradient(135deg, #7a1a24 0%, #4a0f16 100%);
        border-bottom-color: #ff9e9e;
    }

    .ci-hero h1 {
        color: #fff;
        font-size: 1.35rem;
        font-weight: 700;
        margin: 0 0 .35rem;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .ci-hero h1 i {
        color: var(--rh-gold);
    }

    .ci-hero.danger h1 i {
        color: #ff9e9e;
    }

    .ci-hero p {
        margin: 0;
        color: rgba(255, 255, 255, .82);
        font-size: .88rem;
    }

    .ci-hero-actions {
        display: flex;
        gap: .6rem;
        flex-wrap: wrap;
    }

    /* ============================================================================
   CHOICE CARDS (écran de choix)
============================================================================ */
    .ci-choice-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .ci-choice-grid {
            grid-template-columns: 1fr;
        }
    }

    .ci-choice-card {
        display: block;
        background: #fff;
        border: 2px solid var(--rh-border);
        border-radius: var(--rh-radius);
        padding: 2rem;
        text-decoration: none;
        color: inherit;
        transition: all .3s ease;
        position: relative;
        overflow: hidden;
    }

    .ci-choice-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--rh-primary-light);
        transition: height .3s ease;
    }

    .ci-choice-card.den::before {
        background: var(--rh-danger-light);
    }

    .ci-choice-card:hover {
        border-color: var(--rh-primary);
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(3, 63, 31, .15);
    }

    .ci-choice-card:hover::before {
        height: 6px;
    }

    .ci-choice-card.den:hover {
        border-color: var(--rh-danger);
        box-shadow: 0 12px 30px rgba(220, 53, 69, .15);
    }

    .ci-choice-card.den:hover::before {
        background: var(--rh-danger);
        height: 6px;
    }

    .ci-choice-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: var(--rh-primary-light);
        color: var(--rh-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.25rem;
    }

    .ci-choice-card.den .ci-choice-icon {
        background: var(--rh-danger-light);
        color: var(--rh-danger);
    }

    .ci-choice-card h3 {
        color: var(--rh-primary);
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: .65rem;
    }

    .ci-choice-card.den h3 {
        color: var(--rh-danger);
    }

    .ci-choice-card p {
        font-size: .88rem;
        color: #475569;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .ci-choice-list {
        list-style: none;
        padding: 0;
        margin: 1.25rem 0;
        font-size: .83rem;
        color: var(--rh-muted);
    }

    .ci-choice-list li {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: .4rem;
    }

    .ci-choice-list i {
        color: var(--rh-gold);
    }

    .ci-choice-card.den .ci-choice-list i {
        color: var(--rh-danger);
    }

    .ci-choice-cta {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-weight: 700;
        color: var(--rh-primary);
        font-size: .9rem;
    }

    .ci-choice-card.den .ci-choice-cta {
        color: var(--rh-danger);
    }

    /* ============================================================================
   INFO BANNER
============================================================================ */
    .ci-info-banner {
        background: var(--rh-primary-light);
        border-left: 4px solid var(--rh-primary-light);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: .85rem;
        font-size: .85rem;
        color: #334155;
        line-height: 1.6;
    }

    .ci-info-banner i {
        color: var(--rh-primary);
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .ci-info-banner.den {
        background: var(--rh-danger-light);
        border-left-color: var(--rh-primary-light);
    }

    .ci-info-banner.den i {
        color: var(--rh-danger);
    }

    /* ============================================================================
   STEPPER
============================================================================ */
    .ci-progress-bar-container {
        background: #fff;
        border: 1px solid var(--rh-border);
        border-radius: var(--rh-radius);
        padding: 1.5rem 1.5rem 1rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .03);
    }

    .ci-steps {
        display: flex;
        justify-content: space-between;
        gap: .5rem;
    }

    .ci-step {
        flex: 1;
        text-align: center;
        padding: 0 .35rem;
        position: relative;
    }

    .ci-step::after {
        content: '';
        position: absolute;
        top: 19px;
        left: calc(50% + 24px);
        right: calc(-50% + 24px);
        height: 2px;
        background: #e5e9ef;
        z-index: 0;
        transition: background .3s ease;
    }

    .ci-step:last-child::after {
        display: none;
    }

    .ci-step.completed::after {
        background: var(--rh-gold);
    }

    .ci-step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: .9rem;
        background: #f1f5f9;
        color: var(--rh-muted);
        border: 2px solid transparent;
        transition: all .3s ease;
        margin: 0 auto .5rem;
        position: relative;
        z-index: 1;
    }

    .ci-step-circle.active {
        background: var(--rh-primary);
        color: #fff;
        box-shadow: 0 0 0 4px rgba(3, 63, 31, .1);
    }

    .ci-step-circle.done {
        background: var(--rh-gold);
        color: var(--rh-primary);
    }

    .ci-step-label {
        font-size: .74rem;
        font-weight: 600;
        color: var(--rh-muted);
        line-height: 1.3;
    }

    .ci-step.active .ci-step-label,
    .ci-step.done .ci-step-label {
        color: var(--rh-primary);
    }

    .ci-progress-line {
        height: 4px;
        background: #e5e9ef;
        border-radius: 4px;
        margin-top: 1rem;
        overflow: hidden;
    }

    .ci-progress-line-fill {
        height: 100%;
        width: 20%;
        background: linear-gradient(90deg, var(--rh-primary), var(--rh-gold));
        border-radius: 4px;
        transition: width .4s ease;
    }

    /* ============================================================================
   CARD
============================================================================ */
    .ci-card {
        background: #fff;
        border: 1px solid var(--rh-border);
        border-radius: var(--rh-radius);
        box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
        overflow: hidden;
        margin-bottom: 1.25rem;
    }

    .ci-card-header {
        background: linear-gradient(90deg, #f8fafc 0%, #fff 100%);
        border-bottom: 1px solid var(--rh-border);
        padding: .95rem 1.35rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .ci-card-header h6 {
        color: var(--rh-primary);
        font-weight: 700;
        text-transform: uppercase;
        font-size: .78rem;
        letter-spacing: .06em;
        display: flex;
        align-items: center;
        margin: 0;
    }

    .ci-card-header h6 i {
        color: var(--rh-gold);
        font-size: 1.2rem;
        margin-right: .55rem;
    }

    .ci-card-header .ci-step-badge {
        background: var(--rh-primary-light);
        color: var(--rh-primary);
        font-size: .68rem;
        font-weight: 700;
        padding: .25rem .65rem;
        border-radius: 50rem;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .ci-card-body {
        padding: 1.5rem;
    }

    /* ============================================================================
   FORM
============================================================================ */
    .ci-label {
        display: block;
        font-size: .8rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: .4rem;
        line-height: 1.4;
    }

    .ci-label .required {
        color: var(--rh-danger);
        font-weight: 700;
        margin-left: .1rem;
    }

    .ci-label .ci-optional {
        color: var(--rh-muted);
        font-weight: 400;
        font-size: .72rem;
        font-style: italic;
        margin-left: .3rem;
    }

    .form-control,
    .form-select {
        font-size: .88rem;
        border: 1px solid var(--rh-border);
        border-radius: 10px;
        padding: .65rem .9rem;
        transition: border-color .2s ease, box-shadow .2s ease;
        min-height: 44px;
        width: 100%;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--rh-primary);
        box-shadow: 0 0 0 .2rem var(--rh-primary-light);
        outline: none;
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
        line-height: 1.6;
        font-family: inherit;
    }

    .ci-readonly {
        background: #f8fafc !important;
        color: #475569 !important;
        cursor: not-allowed;
        border-style: dashed !important;
    }

    .form-text {
        font-size: .72rem;
        color: var(--rh-muted);
        margin-top: .35rem;
        display: flex;
        align-items: flex-start;
        gap: .3rem;
    }

    .form-text i {
        margin-top: .15rem;
        flex-shrink: 0;
    }

    .ci-char-count {
        font-size: .72rem;
        color: var(--rh-muted);
        text-align: right;
        margin-top: .25rem;
    }

    .ci-char-count.warn {
        color: var(--rh-danger);
    }

    /* ============================================================================
   RADIO CARDS
============================================================================ */
    .ci-radio-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: .85rem;
    }

    .ci-radio {
        position: relative;
    }

    .ci-radio input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .ci-radio label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .6rem;
        padding: 1rem 1.15rem;
        border: 2px solid var(--rh-border);
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        font-size: .88rem;
        color: var(--rh-muted);
        transition: all .2s ease;
        background: #fff;
        text-align: center;
        height: 100%;
        margin: 0;
    }

    .ci-radio label i {
        font-size: 1.35rem;
    }

    .ci-radio:hover label {
        border-color: var(--rh-gold);
        background: var(--rh-gold-light);
    }

    .ci-radio input[type="radio"]:checked+label {
        border-color: var(--rh-primary);
        background: var(--rh-primary-light);
        color: var(--rh-primary);
        box-shadow: 0 0 0 3px rgba(3, 63, 31, .08);
    }

    .ci-radio.ci-radio-oui input[type="radio"]:checked+label {
        border-color: var(--rh-danger);
        background: var(--rh-danger-light);
        color: var(--rh-danger);
    }

    .ci-radio.ci-radio-critique input[type="radio"]:checked+label {
        border-color: #7a1a24;
        background: #f5d5d8;
        color: #7a1a24;
    }

    /* ============================================================================
   CHECKBOX CARDS
============================================================================ */
    .ci-checkbox-group {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: .65rem;
    }

    .ci-checkbox {
        display: flex;
        align-items: flex-start;
        gap: .65rem;
        padding: .85rem 1rem;
        border: 2px solid var(--rh-border);
        border-radius: 10px;
        cursor: pointer;
        transition: all .2s ease;
        background: #fff;
        font-size: .84rem;
        color: #334155;
        margin: 0;
        line-height: 1.5;
    }

    .ci-checkbox:hover {
        border-color: var(--rh-gold);
        background: var(--rh-gold-light);
        transform: translateY(-1px);
    }

    .ci-checkbox input[type="checkbox"] {
        margin-top: .15rem;
        accent-color: var(--rh-primary);
        flex-shrink: 0;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .ci-checkbox:has(input:checked) {
        border-color: var(--rh-primary);
        background: var(--rh-primary-light);
        box-shadow: 0 0 0 3px rgba(3, 63, 31, .06);
    }

    /* ============================================================================
   DYNAMIC BLOCKS
============================================================================ */
    .ci-section-hidden {
        display: none !important;
    }

    .ci-dynamic-block {
        background: linear-gradient(135deg, #f8fafc 0%, #fff 100%);
        border: 1px solid var(--rh-border);
        border-left: 4px solid #f8fafc;
        border-radius: 12px;
        padding: 1.25rem 1.35rem;
        margin-bottom: 1.25rem;
        animation: slideDown .3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .ci-dynamic-block .ci-block-title {
        font-size: .78rem;
        font-weight: 700;
        color: var(--rh-primary);
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: .85rem;
        display: flex;
        align-items: center;
        gap: .4rem;
    }

    .ci-dynamic-block .ci-block-title i {
        color: var(--rh-gold);
        font-size: 1rem;
    }

    /* ============================================================================
   HONNEUR
============================================================================ */
    .ci-honneur {
        background: linear-gradient(135deg, var(--rh-primary-light) 0%, #fff 100%);
        border-left: 4px solid var(--rh-primary-light);
        border-radius: 12px;
        padding: 1.15rem 1.35rem;
        font-size: .86rem;
        color: #334155;
        line-height: 1.7;
        font-style: italic;
        margin-bottom: 1rem;
    }

    .ci-honneur strong {
        font-style: normal;
        color: var(--rh-primary);
        display: block;
        margin-bottom: .35rem;
    }

    .ci-honneur.danger {
        background: linear-gradient(135deg, var(--rh-danger-light) 0%, #fff 100%);
        /* border-left-color: var(--rh-danger); */
        border-left-color: var(--rh-danger-light);
    }

    .ci-honneur.danger strong {
        color: var(--rh-danger);
    }

    /* ============================================================================
   CONSENT
============================================================================ */
    .ci-consent-box {
        border: 2px dashed var(--rh-border);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        background: #fafbfc;
    }

    .ci-notice {
        font-size: .83rem;
        color: #334155;
        line-height: 1.7;
        max-height: 280px;
        overflow-y: auto;
        padding-right: .75rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--rh-border);
        padding-bottom: 1rem;
    }

    .ci-notice::-webkit-scrollbar {
        width: 6px;
    }

    .ci-notice::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .ci-notice h5 {
        color: var(--rh-primary);
        font-size: .95rem;
        font-weight: 700;
        margin-bottom: .85rem;
    }

    .ci-notice h6 {
        color: var(--rh-primary);
        font-size: .82rem;
        font-weight: 700;
        margin: 1rem 0 .4rem;
    }

    .ci-notice p {
        margin-bottom: .55rem;
    }

    .ci-notice ul {
        padding-left: 1.25rem;
        margin-bottom: .55rem;
    }

    .ci-notice li {
        margin-bottom: .25rem;
    }

    .ci-consent-item {
        display: flex;
        align-items: flex-start;
        gap: .65rem;
        padding: .85rem 1rem;
        border: 2px solid var(--rh-border);
        border-radius: 10px;
        margin-bottom: .6rem;
        cursor: pointer;
        transition: all .2s ease;
        background: #fff;
    }

    .ci-consent-item:hover {
        border-color: var(--rh-gold);
        background: var(--rh-gold-light);
    }

    .ci-consent-item:has(input:checked) {
        border-color: var(--rh-primary);
        background: var(--rh-primary-light);
    }

    .ci-consent-item input[type="checkbox"] {
        margin-top: .15rem;
        accent-color: var(--rh-primary);
        flex-shrink: 0;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .ci-consent-item label {
        cursor: pointer;
        margin: 0;
        font-size: .83rem;
        color: #334155;
        line-height: 1.55;
    }

    /* ============================================================================
   BUTTONS
============================================================================ */
    .ci-btn {
        font-weight: 700;
        padding: .8rem 1.75rem;
        border-radius: 10px;
        transition: all .2s ease;
        font-size: .9rem;
        border: 1px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .35rem;
        text-decoration: none;
    }

    .ci-btn-primary {
        background: var(--rh-primary);
        color: #fff;
        border-color: var(--rh-primary);
    }

    .ci-btn-primary:hover {
        background: var(--rh-primary-dark);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(3, 63, 31, .2);
    }

    .ci-btn-danger {
        background: var(--rh-danger);
        color: #fff;
        border-color: var(--rh-danger);
    }

    .ci-btn-danger:hover {
        background: #b02a37;
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(220, 53, 69, .25);
    }

    .ci-btn-gold {
        background: var(--rh-gold);
        color: var(--rh-primary);
        border-color: var(--rh-gold);
    }

    .ci-btn-gold:hover {
        background: var(--rh-gold-hover);
        color: var(--rh-primary);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(249, 178, 51, .25);
    }

    .ci-btn-outline {
        background: #fff;
        color: var(--rh-muted);
        border-color: var(--rh-border);
        font-weight: 500;
    }

    .ci-btn-outline:hover {
        background: #f8fafc;
        color: #334155;
        border-color: #94a3b8;
    }

    .ci-btn:disabled {
        background: #cbd5e1 !important;
        border-color: #cbd5e1 !important;
        color: #64748b !important;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

    /* ============================================================================
   STEP PANELS
============================================================================ */
    .ci-step-panel {
        display: none;
        animation: fadeIn .35s ease;
    }

    .ci-step-panel.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateX(12px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* ============================================================================
   NAV BAR
============================================================================ */
    .ci-nav-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
        flex-wrap: wrap;
    }

    .ci-nav-info {
        font-size: .8rem;
        color: var(--rh-muted);
        font-weight: 600;
    }

    /* ============================================================================
   RECAP
============================================================================ */
    .ci-recap {
        font-size: .85rem;
        color: #334155;
        line-height: 1.9;
    }

    .ci-recap .ci-recap-row {
        display: flex;
        gap: .5rem;
        padding: .35rem 0;
        border-bottom: 1px dashed #e5e9ef;
    }

    .ci-recap .ci-recap-row:last-child {
        border-bottom: none;
    }

    .ci-recap strong {
        color: var(--rh-primary);
        min-width: 180px;
        flex-shrink: 0;
    }

    /* ============================================================================
   UPLOAD
============================================================================ */
    .ci-upload-zone {
        border: 2px dashed var(--rh-border);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all .2s ease;
        background: #fafbfc;
    }

    .ci-upload-zone:hover {
        border-color: var(--rh-primary);
        background: var(--rh-primary-light);
    }

    .ci-upload-zone i {
        font-size: 2.5rem;
        color: var(--rh-primary);
        display: block;
        margin-bottom: .5rem;
    }

    .ci-upload-zone p {
        margin: 0;
        font-size: .85rem;
        color: #475569;
    }

    .ci-upload-zone small {
        font-size: .72rem;
        color: var(--rh-muted);
    }

    .ci-upload-list {
        list-style: none;
        padding: 0;
        margin: 1rem 0 0;
    }

    .ci-upload-list li {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .5rem .75rem;
        background: #f8fafc;
        border-radius: 8px;
        margin-bottom: .35rem;
        font-size: .82rem;
    }

    .ci-upload-list li i {
        color: var(--rh-primary);
    }

    .ci-upload-list li .ci-remove {
        margin-left: auto;
        color: var(--rh-danger);
        cursor: pointer;
        background: none;
        border: none;
        font-size: 1.1rem;
    }

    /* ============================================================================
   TEMOIN
============================================================================ */
    .ci-temoin-row {
        display: flex;
        gap: .5rem;
        margin-bottom: .5rem;
    }

    .ci-temoin-row input {
        flex: 1;
    }

    .ci-temoin-row button {
        background: var(--rh-danger-light);
        color: var(--rh-danger);
        border: none;
        width: 44px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 1.2rem;
    }

    /* ============================================================================
   KPI CARDS
============================================================================ */
    .ci-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .ci-kpi {
        background: #fff;
        border: 1px solid var(--rh-border);
        border-radius: var(--rh-radius);
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: .9rem;
        box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
        transition: all .2s ease;
        position: relative;
        overflow: hidden;
    }

    .ci-kpi::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #f8fafc;
    }

    .ci-kpi:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(15, 23, 42, .08);
    }

    .ci-kpi-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .ci-kpi-body {
        flex: 1;
        min-width: 0;
    }

    .ci-kpi-value {
        font-size: 1.4rem;
        font-weight: 800;
        line-height: 1.1;
        color: var(--rh-primary);
    }

    .ci-kpi-label {
        font-size: .72rem;
        color: var(--rh-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-top: .15rem;
    }

    .ci-kpi.total .ci-kpi-icon {
        background: var(--rh-primary-light);
        color: var(--rh-primary);
    }

    .ci-kpi.attente::before {
        background: var(--rh-info);
    }

    .ci-kpi.attente .ci-kpi-icon {
        background: rgba(13, 110, 253, .08);
        color: var(--rh-info);
    }

    .ci-kpi.validees::before {
        background: var(--rh-success);
    }

    .ci-kpi.validees .ci-kpi-icon {
        background: rgba(25, 135, 84, .1);
        color: var(--rh-success);
    }

    .ci-kpi.rejetees::before {
        background: var(--rh-danger);
    }

    .ci-kpi.rejetees .ci-kpi-icon {
        background: rgba(220, 53, 69, .08);
        color: var(--rh-danger);
    }

    .ci-kpi-type {
        background: #fff;
        border: 1px solid var(--rh-border);
        border-radius: var(--rh-radius);
        box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
        overflow: hidden;
        transition: all .2s ease;
        position: relative;
    }

    .ci-kpi-type::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .ci-kpi-type-auto::before {
        background: #e5e9ef;
    }

    .ci-kpi-type-den::before {
        background: #e5e9ef;
    }

    .ci-kpi-type:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(15, 23, 42, .08);
    }

    .ci-kpi-type-header {
        display: flex;
        align-items: center;
        gap: .85rem;
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid var(--rh-border);
        background: linear-gradient(90deg, #f8fafc 0%, #fff 100%);
    }

    .ci-kpi-type-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .ci-kpi-type-auto .ci-kpi-type-icon {
        background: var(--rh-primary-light);
        color: var(--rh-primary);
    }

    .ci-kpi-type-den .ci-kpi-type-icon {
        background: var(--rh-danger-light);
        color: var(--rh-danger);
    }

    .ci-kpi-type-title {
        font-size: .82rem;
        font-weight: 700;
        color: var(--rh-primary);
        text-transform: uppercase;
        letter-spacing: .04em;
        line-height: 1.2;
    }

    .ci-kpi-type-den .ci-kpi-type-title {
        color: var(--rh-danger);
    }

    .ci-kpi-type-total {
        font-size: .72rem;
        color: var(--rh-muted);
        font-weight: 600;
        margin-top: .15rem;
    }

    .ci-kpi-type-body {
        padding: .85rem 1.25rem;
    }

    .ci-kpi-type-stat {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .4rem 0;
        font-size: .82rem;
        border-bottom: 1px dashed var(--rh-border);
    }

    .ci-kpi-type-stat:last-child {
        border-bottom: none;
    }

    .ci-kpi-type-label {
        color: var(--rh-muted);
        font-weight: 500;
        flex: 1;
    }

    .ci-kpi-type-stat strong {
        color: var(--rh-primary);
        font-size: .95rem;
        font-weight: 800;
        min-width: 24px;
        text-align: right;
    }

    .ci-kpi-type-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .ci-dot-info {
        background: var(--rh-info);
    }

    .ci-dot-ok {
        background: var(--rh-success);
    }

    .ci-dot-ko {
        background: var(--rh-danger);
    }

    @page {
        size: A4 landscape;
        margin: 10mm;
    }


    /* ============================================================================
   TOOLBAR
============================================================================ */
    .ci-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .ci-toolbar-title {
        font-size: .9rem;
        font-weight: 700;
        color: var(--rh-primary);
        text-transform: uppercase;
        letter-spacing: .04em;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .ci-toolbar-title i {
        color: var(--rh-gold);
        font-size: 1.15rem;
    }

    .ci-toolbar-title small {
        font-weight: 500;
        color: var(--rh-muted);
        font-size: .75rem;
        text-transform: none;
        letter-spacing: 0;
    }

    /* ============================================================================
   TABLEAU
============================================================================ */
    .ci-table-wrapper {
        background: #fff;
        border: 1px solid var(--rh-border);
        border-radius: var(--rh-radius);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
    }

    .ci-table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
        font-size: .85rem;
    }

    .ci-table thead {
        background: linear-gradient(90deg, #f8fafc 0%, #fff 100%);
        border-bottom: 2px solid var(--rh-gold);
    }

    .ci-table thead th {
        color: var(--rh-primary);
        font-weight: 700;
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        padding: .85rem 1rem;
        text-align: left;
        white-space: nowrap;
    }

    .ci-table tbody tr {
        border-bottom: 1px solid var(--rh-border);
        transition: background .15s ease;
    }

    .ci-table tbody tr:hover {
        background: var(--rh-primary-light);
    }

    .ci-table tbody tr:last-child {
        border-bottom: none;
    }

    .ci-table tbody td {
        padding: .8rem 1rem;
        vertical-align: middle;
        color: #334155;
    }

    .ci-table .ref {
        font-family: monospace;
        font-weight: 700;
        color: var(--rh-primary);
        font-size: .82rem;
    }

    /* ============================================================================
   BADGES & TAGS
============================================================================ */
    .ci-badge {
        font-size: .72rem;
        font-weight: 600;
        padding: .3rem .7rem;
        border-radius: 50rem;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        white-space: nowrap;
    }

    .ci-badge-ok {
        background: rgba(25, 135, 84, .1);
        color: var(--rh-success);
        border: 1px solid rgba(25, 135, 84, .25);
    }

    .ci-badge-ko {
        background: rgba(220, 53, 69, .08);
        color: var(--rh-danger);
        border: 1px solid rgba(220, 53, 69, .2);
    }

    .ci-badge-warn {
        background: rgba(249, 178, 51, .15);
        color: #b97d0c;
        border: 1px solid rgba(249, 178, 51, .4);
    }

    .ci-badge-info {
        background: rgba(13, 110, 253, .08);
        color: var(--rh-info);
        border: 1px solid rgba(13, 110, 253, .2);
    }

    .ci-tag {
        display: inline-block;
        background: var(--rh-primary-light);
        color: var(--rh-primary);
        border: 1px solid rgba(3, 63, 31, .15);
        font-size: .68rem;
        font-weight: 600;
        padding: .15rem .5rem;
        border-radius: 5px;
        margin: .1rem .15rem .1rem 0;
    }

    /* ============================================================================
   ACTION BUTTONS (petits, dans tableau)
============================================================================ */
    .ci-action-btn {
        background: var(--rh-primary);
        color: #fff;
        border: 1px solid var(--rh-primary);
        font-weight: 600;
        font-size: .75rem;
        padding: .32rem .75rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all .2s ease;
        display: inline-flex;
        align-items: center;
        gap: .25rem;
    }

    .ci-action-btn:hover {
        background: var(--rh-primary-dark);
        color: #fff;
    }

    .ci-action-btn-outline {
        background: #fff;
        color: var(--rh-primary);
        border: 1px solid var(--rh-border);
        font-weight: 600;
        font-size: .75rem;
        padding: .32rem .75rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all .2s ease;
        display: inline-flex;
        align-items: center;
        gap: .25rem;
    }

    .ci-action-btn-outline:hover {
        background: var(--rh-primary-light);
        border-color: var(--rh-primary);
        color: var(--rh-primary);
    }

    /* ============================================================================
   PAGINATION
============================================================================ */
    .ci-pagination {
        padding: .85rem 1.15rem;
        border-top: 1px solid var(--rh-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        font-size: .78rem;
        color: var(--rh-muted);
        flex-wrap: wrap;
    }

    .ci-pagination .pagination {
        margin: 0;
    }

    /* ============================================================================
   EMPTY STATE
============================================================================ */
    .ci-empty {
        padding: 3rem 1rem;
        text-align: center;
        color: var(--rh-muted);
    }

    .ci-empty i {
        font-size: 3rem;
        color: #cbd5e1;
        display: block;
        margin-bottom: .75rem;
    }

    .ci-empty h5 {
        color: var(--rh-primary);
        font-weight: 700;
        margin-bottom: .5rem;
    }

    .ci-empty p {
        font-size: .88rem;
        margin-bottom: 1.25rem;
    }

    /* ============================================================================
   INFO ROW (show)
============================================================================ */
    .ci-info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        /* padding: .7rem 0; */
        border-bottom: 1px dashed var(--rh-border);
        gap: 1rem;
    }

    .ci-info-row:last-child {
        border-bottom: none;
    }

    .ci-info-label {
        font-size: .78rem;
        color: var(--rh-muted);
        font-weight: 600;
        flex-shrink: 0;
    }

    .ci-info-value {
        font-size: .85rem;
        color: #1e293b;
        font-weight: 600;
        text-align: right;
        word-break: break-word;
    }

    /* ============================================================================
   TIMELINE (optionnel, si tu l'utilises dans show)
============================================================================ */
    .ci-timeline {
        position: relative;
        padding-left: 24px;
    }

    .ci-timeline::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 4px;
        bottom: 4px;
        width: 2px;
        background: var(--rh-border);
    }

    .ci-timeline-item {
        position: relative;
        padding-bottom: 1rem;
    }

    .ci-timeline-item:last-child {
        padding-bottom: 0;
    }

    .ci-timeline-item::before {
        content: '';
        position: absolute;
        left: -22px;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid var(--rh-gold);
    }

    .ci-timeline-action {
        font-size: .82rem;
        font-weight: 700;
        color: var(--rh-primary);
    }

    .ci-timeline-meta {
        font-size: .72rem;
        color: var(--rh-muted);
        margin-top: .15rem;
    }

    .ci-timeline-comment {
        font-size: .78rem;
        color: #334155;
        margin-top: .25rem;
        font-style: italic;
    }

    /* ============================================================================
   RESPONSIVE
============================================================================ */
    @media (max-width: 768px) {
        .ci-hero {
            padding: 1.25rem 1.35rem;
        }

        .ci-hero h1 {
            font-size: 1.1rem;
        }

        .ci-card-body {
            padding: 1.15rem;
        }

        .ci-step-label {
            font-size: .62rem;
        }

        .ci-step-circle {
            width: 32px;
            height: 32px;
            font-size: .78rem;
        }

        .ci-step::after {
            top: 15px;
            left: calc(50% + 18px);
            right: calc(-50% + 18px);
        }

        .ci-nav-bar {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .ci-nav-bar .ci-btn {
            width: 100%;
        }

        .ci-nav-bar .ci-nav-info {
            text-align: center;
            order: -1;
            margin-bottom: .5rem;
        }

        .ci-recap .ci-recap-row {
            flex-direction: column;
            gap: .15rem;
        }

        .ci-recap strong {
            min-width: auto;
        }

        .ci-hero-actions {
            width: 100%;
        }

        .ci-hero-actions .ci-btn-gold {
            flex: 1;
            justify-content: center;
        }

        .ci-table thead th:nth-child(4),
        .ci-table tbody td:nth-child(4),
        .ci-table thead th:nth-child(5),
        .ci-table tbody td:nth-child(5) {
            display: none;
        }
    }

    /* ============================================================================
   IMPRESSION
============================================================================ */
    @media print {
        .ci-no-print {
            display: none !important;
        }

        body,
        html {
            background: #fff !important;
        }

        .ci-hero {
            background: #033f1f !important;
            color: #fff !important;
            border-bottom-color: #F9B233 !important;
            box-shadow: none !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .ci-kpi {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .ci-table {
            font-size: 10px;
        }

        .ci-table thead th {
            background: #f0f0f0 !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .ci-table .ci-badge,
        .ci-table .ci-tag {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .ci-table td:last-child,
        .ci-table th:last-child {
            display: none !important;
        }

        a {
            text-decoration: none !important;
            color: #000 !important;
        }



        @page {
            size: A4 landscape;
            margin: 10mm;
        }
    }
</style>
