<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Declaration extends Model
{
    use SoftDeletes;

    protected $table = 'declarations';

    protected $fillable = [
        'uuid',
        'reference',
        'id_campagne',
        'id_collaborateur',
        'matricule',
        'nom_prenoms',
        'direction',
        'departement',
        'fonction',
        'email_pro',
        'nom_n1',
        'reponse_q1',
        'statut',
        'date_soumission',
        'date_derniere_modif',
        'date_validation',
        'date_rejet',
        'validee_par',
        'rejetee_par',
        'motif_rejet',
        'accuse_reception_genere',
        'date_accuse_reception',
        'accuse_reception_numero',
        'ip_soumission',
        'user_agent',
    ];

    protected $casts = [
        'date_soumission'         => 'datetime',
        'date_derniere_modif'     => 'datetime',
        'date_validation'         => 'datetime',
        'date_rejet'              => 'datetime',
        'date_accuse_reception'   => 'datetime',
        'accuse_reception_genere' => 'boolean',
        'created_at'              => 'datetime',
        'updated_at'              => 'datetime',
        'deleted_at'              => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Declaration $declaration) {
            $declaration->uuid      ??= (string) Str::uuid();
            $declaration->reference ??= 'DECL-' . strtoupper(Str::random(8));
        });
    }

    // ============================================================
    // RELATIONS
    // ============================================================

    public function campagne()
    {
        return $this->belongsTo(Campagne::class, 'id_campagne');
    }

    public function collaborateur()
    {
        return $this->belongsTo(Agent::class, 'id_collaborateur', 'id');
    }

    public function conflit()
    {
        return $this->hasOne(DeclarationConflit::class, 'id_declaration');
    }

    public function conflits()
    {
        return $this->hasMany(DeclarationConflit::class, 'id_declaration');
    }

    public function declarationHonneur()
    {
        return $this->hasOne(DeclarationHonneur::class, 'id_declaration');
    }

    public function consentement()
    {
        return $this->hasOne(Consentement::class, 'id_declaration');
    }

    public function historiques()
    {
        return $this->hasMany(DeclarationHistorique::class, 'id_declaration')
            ->orderByDesc('created_at');
    }

    public function valideePar()
    {
        return $this->belongsTo(User::class, 'validee_par');
    }

    public function rejeteePar()
    {
        return $this->belongsTo(User::class, 'rejetee_par');
    }

    // public function agentDenonce(): BelongsTo
    // {
    //     return $this->belongsTo(Agent::class, 'agent_denonce_id');
    // }



    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeSoumises($query)
    {
        return $query->where('statut', 'SOUMISE');
    }

    public function scopePositives($query)
    {
        return $query->where('reponse_q1', 'OUI');
    }

    public function scopeNegatives($query)
    {
        return $query->where('reponse_q1', 'NON');
    }

    public function scopePourCampagne($query, $campagneId)
    {
        return $query->where('id_campagne', $campagneId);
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getEstPositiveAttribute(): bool
    {
        return $this->reponse_q1 === 'OUI';
    }

    public function getEstNegativeAttribute(): bool
    {
        return $this->reponse_q1 === 'NON';
    }

    public function getEstValideeAttribute(): bool
    {
        return $this->statut === 'VALIDEE';
    }

    public function getEstRejeteeAttribute(): bool
    {
        return $this->statut === 'REJETEE';
    }

    public function getNatureLabelsAttribute(): array
    {
        if (!$this->conflit) {
            return [];
        }

        $labels = [
            'PARTICIPATION_FINANCIERE' => 'Participation financière',
            'LIEN_FAMILIAL'            => 'Lien familial',
            'ACTIVITE_EXTERNE'         => 'Activité externe',
            'FONCTION_ADMINISTRATEUR'  => 'Fonction d\'administrateur',
            'RELATION_PERSONNELLE'     => 'Relation personnelle',
            'CADEAUX_AVANTAGES'        => 'Cadeaux / avantages',
            'AUTRE'                    => 'Autre',
        ];

        $natures = is_string($this->conflit->nature_conflit)
            ? json_decode($this->conflit->nature_conflit, true)
            : $this->conflit->nature_conflit;

        return array_map(fn($n) => $labels[$n] ?? $n, $natures ?? []);
    }
    // Dans app/Models/Declaration.php


}
