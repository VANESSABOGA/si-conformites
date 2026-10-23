<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeclarationConflit extends Model
{
    protected $table = 'declaration_conflits';

    protected $fillable = [
        'id_declaration',
        'nature_conflit',
        'description_detaillee',
        'personne_societe_concernee',
        'client_fournisseur_partenaire',
        'relation_type',
        'relation_agent_id',
        'relation_nom_externe',
        'cadeau_type',
        'cadeau_agent_id',
        'cadeau_nom_externe',
        'date_debut_situation',
        'influence_decisions',
        'mesures_prises',


        // Dénonciation
        'agent_denonce_id',
        'agent_denonce_nom',
        'agent_denonce_fonction',
        'niveau_urgence',
        'date_faits',
        'lieu_faits',
        'impact_estime',
        'deja_signale',
        'signale_a',
        'temoins',
        'protection_denonciateur',
        'souhaite_anonyme',
    ];

    protected $casts = [

        'nature_conflit'         => 'array',
        'temoins'                => 'array',
        'date_debut_situation'   => 'date',
        'date_faits'             => 'date',
        'deja_signale'           => 'boolean',
        'protection_denonciateur' => 'boolean',
        'souhaite_anonyme'       => 'boolean',
        'created_at'           => 'datetime',
        'updated_at'           => 'datetime',
    ];

    /* ============================================================
     | RELATIONS
     ============================================================ */

    public function declaration()
    {
        return $this->belongsTo(Declaration::class, 'id_declaration');
    }

    public function relationAgent()
    {
        return $this->belongsTo(Agent::class, 'relation_agent_id');
    }

    public function cadeauAgent()
    {
        return $this->belongsTo(Agent::class, 'cadeau_agent_id');
    }

    /**
     * Agent dénoncé (relation interne).
     * Utilisé pour les déclarations de type DENONCIATION.
     */
    public function agentDenonce()
    {
        return $this->belongsTo(Agent::class, 'agent_denonce_id', 'id');
    }

    /* ============================================================
     | ACCESSEURS UTILES
     ============================================================ */

    /**
     * Nom complet de l'agent dénoncé :
     *  - priorité à la relation Eloquent (agent interne)
     *  - sinon fallback sur le champ libre agent_denonce_nom
     */
    public function getAgentDenonceNomCompletAttribute(): ?string
    {
        if ($this->agentDenonce) {
            return trim($this->agentDenonce->nom . ' ' . $this->agentDenonce->prenom);
        }

        return $this->agent_denonce_nom;
    }

    /**
     * Natures du conflit sous forme de tableau (sécurisé).
     */
    public function getNaturesArrayAttribute(): array
    {
        $natures = $this->nature_conflit;

        if (is_string($natures)) {
            $natures = json_decode($natures, true) ?? [];
        }

        return is_array($natures) ? $natures : [];
    }
}
