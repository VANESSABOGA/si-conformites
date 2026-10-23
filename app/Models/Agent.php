<?php

namespace App\Models;

use App\Models\Concerns\LectureSeule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Agent — table "agents" du SI-RH (lecture seule ici).
 *
 * Version allégée : uniquement les relations utiles à la Conformité.
 * Lien compte : agents.uuid = users.id.
 */
class Agent extends Model
{
    use SoftDeletes, LectureSeule;

    protected $table = 'agents';

    protected $casts = [
        'verifier'        => 'boolean',
        'first_connexion' => 'datetime',
        'last_connexion'  => 'datetime',
        'date_naissance'  => 'date',
    ];

    // ---------- Relations SI-RH ----------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uuid', 'id');
    }

    public function contacts(): HasOne
    {
        return $this->hasOne(AgentContact::class, 'agent_id', 'id');
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(AgentAffectation::class, 'agent_id', 'id');
    }

    public function affectationActuelle(): HasOne
    {
        return $this->hasOne(AgentAffectation::class, 'agent_id', 'id')->latestOfMany();
    }

    // ---------- Relations Conformité ----------

    public function declarations(): HasMany
    {
        return $this->hasMany(Declaration::class, 'id_collaborateur', 'id');
    }

    public function statutsParticipations(): HasMany
    {
        return $this->hasMany(StatutParticipation::class, 'id_collaborateur', 'id');
    }

    // ---------- Helpers ----------

    public function getNomCompletAttribute(): string
    {
        return trim(($this->prenom ?? '') . ' ' . ($this->nom ?? ''));
    }

    /** Supérieur hiérarchique direct (N+1). */
    public function getMonSuperieurAttribute(): ?Agent
    {
        return $this->affectationActuelle?->superieur;
    }

    /** Photo servie par le SI-RH (les fichiers restent dans son dossier public). */
    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->matricule || ! config('conformite.sirh_url')) {
            return null;
        }

        return rtrim(config('conformite.sirh_url'), '/') . "/assets/images/agents/IMG_{$this->matricule}.JPG";
    }
}
