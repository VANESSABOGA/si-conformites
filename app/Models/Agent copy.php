<?php

namespace App\Models;

use App\Models\Carriere;
use App\Models\DemandeRh;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;

class Agent extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        'uuid',
        'identifiant_agent',
        'matricule',
        'nom',
        'prenom',
        'sexe',
        'date_naissance',
        'lieu_naissance',
        'nationalite',
        'situation_familiale',
        'photo',
        'about_me',
        'email',
        'first_connexion',
        'last_connexion',
        'verifier',
        'statut',
        // Pièce d'identité
        'piece_identite_type',
        'piece_identite_numero',
        'piece_identite_date_expiration',

        // Documents sociaux
        'numero_cnps',
        'numero_contribuable',
        'rib',
        'banque',

        // Consentements
        'accepte_cgu',
        'accepte_notice',
        'signature_deontologie',
        'date_acceptation_cgu',
        'date_acceptation_notice',
        'date_signature_deontologie',
    ];

    protected $casts = [
        'verifier'        => 'boolean',
        'first_connexion' => 'datetime',
        'last_connexion'  => 'datetime',
        'date_naissance'  => 'date',
    ];

    public function contacts()
    {
        return $this->hasOne(AgentContact::class, 'agent_id', 'id');
    }

    public function urgences()
    {
        return $this->hasMany(AgentUrgence::class, 'agent_id', 'id');
    }

    public function affectations()
    {
        return $this->hasMany(AgentAffectation::class, 'agent_id', 'id');
    }

    public function contrats()
    {
        return $this->hasMany(Contrat::class, 'agent_id', 'id');
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function formations()
    {
        return $this->hasMany(Formation::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function connexions()
    {
        return $this->hasMany(AgentConnexions::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'agent_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uuid', 'id');
    }

    // app/Models/Agent.php

    public function habilitation()
    {
        return $this->hasOne(Habilitation::class);
    }

    /**
     * Utilisé par le parcours d'onboarding (EnsureAgentIsOnboarded) :
     * détermine si les informations minimales attendues d'un agent
     * ont bien été renseignées (email + contact + contact d'urgence).
     */
    public function profilComplet(): bool
    {
        return filled($this->email)
            && $this->contacts()->whereNotNull('telephone_pro')->exists()
            && $this->urgences()->whereNotNull('telephone')->exists();
    }

    /**
     * A-t-il déjà signé le Code de Déontologie ?
     */
    public function aSigneDeontologie(): bool
    {
        return $this->documents()
            ->where('type_document', 'deontologie')
            ->where('statut', 'signe')
            ->exists();
    }

    public function carrieres(): HasMany
    {
        return $this->hasMany(Carriere::class, 'agent_id', 'id');
    }


    public function demandes(): HasMany
    {
        return $this->hasMany(DemandeRh::class, 'agent_id', 'id');
    }

    public function affectationActuelle()
    {
        return $this->hasOne(AgentAffectation::class, 'agent_id', 'id')->latestOfMany();
    }

    public function contactsActuelle()
    {
        return $this->hasOne(AgentContact::class, 'agent_id', 'id')->latestOfMany();
    }

    // app/Models/Agent.php





// app/Models/Agent.php

    /**
     * Contrat actif le plus récent.
     */
    public function contratActuel()
    {
        return $this->hasOne(Contrat::class, 'agent_id', 'id')
            ->latestOfMany('date_debut');
    }



    /**
     * Si les congés sont une table dédiée :
     */
    public function conges(): HasMany
    {
        return $this->hasMany(DemandeConge::class, 'agent_id', 'id');
    }

    // ---------- SCOPES ----------

    // public function scopeDocumentsRecents(Builder $query, int $limit = 10): Builder
    // {
    //     return $query->orderBy('created_at', 'desc')->limit($limit);
    // }

    // public function scopeDemandesRecentes(Builder $query, int $limit = 10): Builder
    // {
    //     return $query->orderBy('created_at', 'desc')->limit($limit);
    // }

    // public function scopeCongesActifs(Builder $query): Builder
    // {
    //     return $query->whereIn('statut', ['valide', 'en_cours'])
    //         ->orderBy('date_debut', 'desc');
    // }

    // app/Models/Agent.php



    /**
     * Vérifie si la photo existe sur le disque.
     */
    public function hasPhoto(): bool
    {
        // Si photo en BDD
        // if ($this->photo) {
        //     return file_exists(public_path('assets/images/agents/' . $this->photo));
        // }

        // Fallback : nom standard par matricule
        if ($this->matricule) {
            return file_exists(public_path("assets/images/agents/IMG_{$this->matricule}.JPG"));
        }

        return false;
    }


    /**
     * Retourne l'URL de la photo avec cache-buster.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        $relativePath = null;

        if ($this->photo && file_exists(public_path('assets/images/agents/' . $this->photo))) {
            $relativePath = 'assets/images/agents/' . $this->photo;
        } elseif ($this->matricule && file_exists(public_path("assets/images/agents/IMG_{$this->matricule}.JPG"))) {
            $relativePath = "assets/images/agents/IMG_{$this->matricule}.JPG";
        }

        if (!$relativePath) {
            return null;
        }

        $absolutePath = public_path($relativePath);

        // ✅ Cache-buster : timestamp de dernière modification du fichier
        $v = Cache::remember(
            "agent_photo_v_{$this->id}",
            now()->addHours(24),
            fn() => filemtime($absolutePath)
        );

        return asset($relativePath) . '?v=' . $v;
    }

    /**
     * Subordonnés actifs dont cet agent est le supérieur direct.
     */
    public function subordonnes()
    {
        return $this->hasMany(AgentAffectation::class, 'superieur_id')
            ->where('statut', 'actif');
    }

    /**
     * Nombre de subordonnés actifs (accessor).
     */
    public function getNbSubordonnesAttribute(): int
    {
        return $this->subordonnes()->count();
    }

    /**
     * Cet agent est-il un N+1 (= a au moins un subordonné actif) ?
     */
    public function getEstN1Attribute(): bool
    {
        return $this->subordonnes()->exists();
    }

    /**
     * Mon supérieur hiérarchique direct (via mon affectation active).
     */
    public function superieur()
    {
        return $this->hasOneThrough(
            Agent::class,
            AgentAffectation::class,
            'agent_id',       // FK sur agent_affectations → mon agent_id
            'id',             // FK sur agents → id du supérieur
            'id',             // PK sur agents (moi)
            'superieur_id'    // FK sur agent_affectations → superieur_id
        )->where('agent_affectations.statut', 'actif');
    }

    /**
     * Mon supérieur direct (accès direct via affectation).
     */
    public function getMonSuperieurAttribute(): ?Agent
    {
        return $this->affectationActuelle?->superieur;
    }
}
