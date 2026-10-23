<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAutoDeclarationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $isOui = $this->input('reponse_q1') === 'OUI';

        return [
            // ============================================================
            // QUESTION PRINCIPALE
            // ============================================================
            'reponse_q1' => 'required|in:OUI,NON',

            // ============================================================
            // BLOC NON — Déclaration sur l'honneur
            // ============================================================
            // 'certification_honneur' => 'required_if:reponse_q1,NON|accepted',
            'certification_honneur' => 'nullable|accepted_if:reponse_q1,NON',

            // ============================================================
            // BLOC OUI — Détail du conflit (nullable si Q1 = NON)
            // ============================================================
            'nature_conflit' => $isOui
                ? 'required|array|min:1'
                : 'nullable|array',

            'nature_conflit.*' => 'in:PARTICIPATION_FINANCIERE,LIEN_FAMILIAL,ACTIVITE_EXTERNE,FONCTION_ADMINISTRATEUR,RELATION_PERSONNELLE,CADEAUX_AVANTAGES,AUTRE',

            'description_detaillee' => $isOui
                ? 'required|string|min:10|max:2000'
                : 'nullable|string|max:2000',

            'influence_decisions' => $isOui
                ? 'required|in:OUI,NON'
                : 'nullable|in:OUI,NON',

            'date_debut_situation' => 'nullable|date|before_or_equal:today',
            'mesures_prises'       => 'nullable|string|max:1000',

            // Société / organisation concernée
            'personne_societe_concernee' => 'nullable|string|max:255|required_if:has_societe,1',

            // Client / fournisseur / partenaire
            'client_fournisseur_partenaire' => 'nullable|string|max:255',

            // Relation personnelle
            'relation_type'        => 'nullable|in:INTERNE,EXTERNE',
            'relation_agent_id'    => 'nullable|exists:agents,id|required_if:relation_type,INTERNE',
            'relation_nom_externe' => 'nullable|string|max:255|required_if:relation_type,EXTERNE',

            // Cadeaux / avantages
            'cadeau_type'        => 'nullable|in:INTERNE,EXTERNE',
            'cadeau_agent_id'    => 'nullable|exists:agents,id|required_if:cadeau_type,INTERNE',
            'cadeau_nom_externe' => 'nullable|string|max:255|required_if:cadeau_type,EXTERNE',

            // ============================================================
            // CONSENTEMENT (commun aux 2 blocs)
            // ============================================================
            'lecture_notice'          => 'accepted',
            'autorisation_traitement' => 'accepted',
            'engagement_declarant'    => 'accepted',
        ];
    }

    public function messages(): array
    {
        return [
            // Question principale
            'reponse_q1.required' => 'Veuillez répondre à la question principale.',
            'reponse_q1.in'       => 'La réponse doit être OUI ou NON.',

            // Bloc NON
            'certification_honneur.required_if' => "Veuillez certifier votre déclaration sur l'honneur.",
            'certification_honneur.accepted'    => "Veuillez certifier votre déclaration sur l'honneur.",

            // Bloc OUI
            'nature_conflit.required'          => 'Veuillez sélectionner au moins une nature de conflit.',
            'nature_conflit.array'             => 'Le format de la nature du conflit est invalide.',
            'nature_conflit.min'               => 'Veuillez sélectionner au moins une nature de conflit.',
            'nature_conflit.*.in'              => 'Une des natures sélectionnées est invalide.',

            'description_detaillee.required' => 'La description détaillée est obligatoire.',
            'description_detaillee.string'   => 'La description doit être un texte.',
            'description_detaillee.min'      => 'La description doit contenir au moins 10 caractères.',
            'description_detaillee.max'      => 'La description ne peut pas dépasser 2000 caractères.',

            'influence_decisions.required' => 'Veuillez préciser si la situation influence vos décisions.',
            'influence_decisions.in'       => 'La réponse doit être OUI ou NON.',

            'date_debut_situation.date'              => 'La date de début doit être une date valide.',
            'date_debut_situation.before_or_equal'   => 'La date de début ne peut pas être dans le futur.',

            'mesures_prises.string' => 'Les mesures prises doivent être un texte.',
            'mesures_prises.max'    => 'Les mesures prises ne peuvent pas dépasser 1000 caractères.',

            // Société
            'personne_societe_concernee.required_if' => "Veuillez préciser la société ou l'organisation concernée.",
            'personne_societe_concernee.string'      => 'Le nom de la société doit être un texte.',
            'personne_societe_concernee.max'         => 'Le nom de la société ne peut pas dépasser 255 caractères.',

            // Client / fournisseur
            'client_fournisseur_partenaire.string' => 'Le nom du client / fournisseur doit être un texte.',
            'client_fournisseur_partenaire.max'    => 'Le nom du client / fournisseur ne peut pas dépasser 255 caractères.',

            // Relation
            'relation_type.in'          => 'Le type de relation doit être INTERNE ou EXTERNE.',
            'relation_agent_id.required_if' => "Veuillez sélectionner l'agent concerné.",
            'relation_agent_id.exists'  => "L'agent sélectionné n'existe pas.",
            'relation_nom_externe.required_if' => 'Veuillez préciser le nom et prénoms de la personne.',
            'relation_nom_externe.string'      => 'Le nom de la personne doit être un texte.',
            'relation_nom_externe.max'         => 'Le nom de la personne ne peut pas dépasser 255 caractères.',

            // Cadeaux
            'cadeau_type.in'           => 'Le type de cadeau doit être INTERNE ou EXTERNE.',
            'cadeau_agent_id.required_if' => "Veuillez sélectionner l'agent concerné.",
            'cadeau_agent_id.exists'   => "L'agent sélectionné n'existe pas.",
            'cadeau_nom_externe.required_if' => 'Veuillez préciser le nom et prénoms de la personne.',
            'cadeau_nom_externe.string'      => 'Le nom de la personne doit être un texte.',
            'cadeau_nom_externe.max'         => 'Le nom de la personne ne peut pas dépasser 255 caractères.',

            // Consentement
            'lecture_notice.accepted'          => 'Veuillez accepter la notice de confidentialité.',
            'autorisation_traitement.accepted' => 'Veuillez autoriser le traitement des données.',
            'engagement_declarant.accepted'    => "Veuillez cocher la case de déclaration et d'engagement.",
        ];
    }

    public function attributes(): array
    {
        return [
            'reponse_q1'                    => 'réponse à la question principale',
            'certification_honneur'         => 'certification sur l\'honneur',
            'nature_conflit'                => 'nature du conflit',
            'description_detaillee'         => 'description détaillée',
            'influence_decisions'           => 'influence sur les décisions',
            'date_debut_situation'          => 'date de début de la situation',
            'mesures_prises'                => 'mesures déjà prises',
            'personne_societe_concernee'    => 'société concernée',
            'client_fournisseur_partenaire' => 'client / fournisseur / partenaire',
            'relation_type'                 => 'type de relation',
            'relation_agent_id'             => 'agent concerné',
            'relation_nom_externe'          => 'nom de la personne concernée',
            'cadeau_type'                   => 'type de cadeau',
            'cadeau_agent_id'               => 'agent concerné (cadeau)',
            'cadeau_nom_externe'            => 'nom de la personne (cadeau)',
            'lecture_notice'                => 'notice de confidentialité',
            'autorisation_traitement'       => 'autorisation de traitement',
            'engagement_declarant'          => 'engagement du déclarant',
        ];
    }

    protected function prepareForValidation(): void
    {
        // ============================================================
        // Indicateur "société requise" calculé côté serveur
        // ============================================================
        $natures = (array) $this->input('nature_conflit', []);

        $hasSociete = count(array_intersect($natures, [
            'PARTICIPATION_FINANCIERE',
            'ACTIVITE_EXTERNE',
            'FONCTION_ADMINISTRATEUR',
        ])) > 0;

        $this->merge([
            'has_societe' => $hasSociete ? 1 : 0,
        ]);

        // ============================================================
        // Nettoyage : si Q1 = NON, neutraliser tous les champs du bloc OUI
        // ============================================================
        if ($this->input('reponse_q1') === 'NON') {
            $this->merge([
                'nature_conflit'                => [],
                'description_detaillee'         => null,
                'influence_decisions'           => null,
                'date_debut_situation'          => null,
                'mesures_prises'                => null,
                'personne_societe_concernee'    => null,
                'client_fournisseur_partenaire' => null,
                'relation_type'                 => null,
                'relation_agent_id'             => null,
                'relation_nom_externe'          => null,
                'cadeau_type'                   => null,
                'cadeau_agent_id'               => null,
                'cadeau_nom_externe'            => null,
            ]);
        }

        // ============================================================
        // Nettoyage : si Q1 = OUI, neutraliser les champs du bloc NON
        // ============================================================
        if ($this->input('reponse_q1') === 'OUI') {
            $this->merge([
                'certification_honneur' => null,
            ]);
        }
    }
}
