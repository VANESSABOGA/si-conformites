<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDenonciationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $agentExterne = (bool) $this->input('agent_externe', false);

        return [
            // Dénonciateur
            'souhaite_anonyme' => 'nullable|boolean',

            // Agent dénoncé
            'agent_externe'          => 'nullable|boolean',
            'agent_denonce_id'       => 'nullable|exists:agents,id|required_without:agent_externe',
            'agent_denonce_nom'      => 'nullable|string|max:255|required_if:agent_externe,1',
            'agent_denonce_fonction' => 'nullable|string|max:255',

            // Faits
            'nature_conflit'   => 'required|array|min:1',
            'nature_conflit.*' => 'in:PARTICIPATION_FINANCIERE,LIEN_FAMILIAL,ACTIVITE_EXTERNE,FONCTION_ADMINISTRATEUR,RELATION_PERSONNELLE,CADEAUX_AVANTAGES,AUTRE',
            'faits_constates'  => 'required|string|min:50|max:3000',
            'date_faits'       => 'nullable|date|before_or_equal:today',
            'lieu_faits'       => 'nullable|string|max:255',
            'impact_estime'    => 'nullable|string|max:1000',
            'niveau_urgence'   => 'required|in:FAIBLE,MOYEN,ELEVE,CRITIQUE',

            // Témoins
            'temoins'     => 'nullable|array|max:10',
            'temoins.*'   => 'string|max:255',

            // Fichiers
            'fichiers'    => 'nullable|array|max:5',
            'fichiers.*'  => 'file|mimes:pdf,jpg,jpeg,png|max:5120', // 5 Mo

            // Déjà signalé
            'deja_signale' => 'nullable|boolean',
            'signale_a'    => 'nullable|string|max:255|required_if:deja_signale,1',

            // Consentement
            'lecture_notice'           => 'accepted',
            'autorisation_traitement'  => 'accepted',
            'protection_denonciateur'  => 'nullable|boolean',
            'engagement_declarant'     => 'accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'agent_denonce_id.required_without'   => 'Veuillez sélectionner un agent ou cocher la case "agent externe".',
            'agent_denonce_nom.required_if'       => 'Veuillez préciser le nom et prénoms de la personne.',
            'nature_conflit.required'             => 'Veuillez sélectionner au moins une nature de conflit.',
            'faits_constates.required'            => 'La description des faits est obligatoire.',
            'faits_constates.min'                 => 'La description des faits doit contenir au moins 50 caractères.',
            'niveau_urgence.required'             => "Veuillez préciser le niveau d'urgence.",
            'fichiers.*.mimes'                    => 'Format de fichier non autorisé (PDF, JPG, PNG).',
            'fichiers.*.max'                      => 'Chaque fichier ne doit pas dépasser 5 Mo.',
            'signale_a.required_if'               => 'Veuillez préciser à qui la situation a été signalée.',
            'lecture_notice.accepted'             => 'Veuillez accepter la notice de confidentialité.',
            'autorisation_traitement.accepted'    => 'Veuillez autoriser le traitement des données.',
            'engagement_declarant.accepted'       => "Veuillez certifier l'exactitude des faits.",
        ];
    }
}