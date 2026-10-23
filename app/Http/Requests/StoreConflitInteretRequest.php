<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreConflitInteretRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // Question 1
            'reponse_q1' => ['required', Rule::in(['OUI', 'NON'])],

            // Bloc NON
            'certification_honneur' => [
                Rule::requiredIf($this->reponse_q1 === 'NON'),
                'accepted',
            ],

            // Bloc OUI
            'nature_conflit' => [
                Rule::requiredIf($this->reponse_q1 === 'OUI'),
                'array',
                'min:1',
            ],
            'nature_conflit.*' => [
                Rule::in([
                    'PARTICIPATION_FINANCIERE',
                    'LIEN_FAMILIAL',
                    'ACTIVITE_EXTERNE',
                    'FONCTION_ADMINISTRATEUR',
                    'RELATION_PERSONNELLE',
                    'CADEAUX_AVANTAGES',
                    'AUTRE',
                ]),
            ],
            'description_detaillee' => [
                Rule::requiredIf($this->reponse_q1 === 'OUI'),
                'string',
                'min:10',
                'max:2000',
            ],
            'personne_societe_concernee' => ['nullable', 'string', 'max:255'],
            'date_debut_situation' => ['nullable', 'date', 'before_or_equal:today'],
            'influence_decisions' => [
                Rule::requiredIf($this->reponse_q1 === 'OUI'),
                Rule::in(['OUI', 'NON']),
            ],
            'mesures_prises' => ['nullable', 'string', 'max:1000'],

            // Consentement
            'lecture_notice'          => ['accepted'],
            'autorisation_traitement' => ['accepted'],
            'engagement_declarant'    => ['accepted'],
            // Bloc OUI — champs conditionnels

            'client_fournisseur_partenaire'  => ['nullable', 'string', 'max:255'],

            'relation_type'                  => ['nullable', Rule::in(['INTERNE', 'EXTERNE'])],
            'relation_agent_id'              => ['nullable', 'integer', 'exists:agents,id'],
            'relation_nom_externe'           => ['nullable', 'string', 'max:255'],

            'cadeau_type'                    => ['nullable', Rule::in(['INTERNE', 'EXTERNE'])],
            'cadeau_agent_id'                => ['nullable', 'integer', 'exists:agents,id'],
            'cadeau_nom_externe'             => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'reponse_q1.required' => 'Veuillez répondre à la question 1.',
            'reponse_q1.in'       => 'La réponse doit être Oui ou Non.',

            'certification_honneur.required' => 'La certification sur l\'honneur est obligatoire.',
            'certification_honneur.accepted' => 'Vous devez certifier votre déclaration sur l\'honneur.',

            'nature_conflit.required' => 'Veuillez sélectionner au moins une nature de conflit.',
            'nature_conflit.min'      => 'Veuillez sélectionner au moins une nature de conflit.',

            'description_detaillee.required' => 'La description détaillée est obligatoire.',
            'description_detaillee.min'      => 'La description doit contenir au moins 10 caractères.',
            'description_detaillee.max'      => 'La description ne peut pas dépasser 2000 caractères.',

            'influence_decisions.required' => 'Veuillez préciser si la situation influence vos décisions.',
            'influence_decisions.in'       => 'La réponse doit être Oui ou Non.',

            'date_debut_situation.before_or_equal' => 'La date ne peut pas être dans le futur.',

            'lecture_notice.accepted'          => 'Vous devez accepter la notice de confidentialité.',
            'autorisation_traitement.accepted' => 'Vous devez autoriser le traitement de vos données.',
            'engagement_declarant.accepted'    => 'Vous devez vous engager à fournir des informations exactes.',

        ];
    }
}
