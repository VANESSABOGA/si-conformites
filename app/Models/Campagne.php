<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Campagne extends Model
{
    use SoftDeletes;

    protected $table = 'campagnes';

    protected $fillable = [
        'uuid',
        'code_campagne',
        'libelle',
        'annee',
        'date_ouverture',
        'date_cloture',
        'statut',
        'message',
        'proprietaire_traitement',
        'direction_pilote',
        'cree_par',           // ← AJOUTÉ
        'ouverte_le',         // ← AJOUTÉ
        'cloturee_le',        // ← AJOUTÉ
    ];

    protected $casts = [
        'date_ouverture' => 'datetime',
        'date_cloture'   => 'datetime',
        'ouverte_le'     => 'datetime',   // ← AJOUTÉ
        'cloturee_le'    => 'datetime',   // ← AJOUTÉ
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
        'deleted_at'     => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Campagne $campagne) {
            $campagne->uuid ??= (string) Str::uuid();
        });
    }

    // ============================================================
    // RELATIONS
    // ============================================================

    public function declarations()
    {
        return $this->hasMany(Declaration::class, 'id_campagne');
    }

    public function statutsParticipations()
    {
        return $this->hasMany(StatutParticipation::class, 'id_campagne');
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeActive2($query)
    {
        return $query->where('statut', 'OUVERTE')
            ->where('date_ouverture', '<=', now())
            ->where('date_cloture', '>=', now());
    }

    // ✅ APRÈS
    public function scopeActive($query)
    {
        return $query->where('statut', 'OUVERTE')
            ->where('date_ouverture', '<=', now())
            ->where(function ($q) {
                $q->whereNull('date_cloture')
                    ->orWhere('date_cloture', '>=', now());
            });
    }

    public function scopeOuverte($query)
    {
        return $query->where('statut', 'OUVERTE');
    }

    public function scopeEnPreparation($query)
    {
        return $query->where('statut', 'PREPARATION');
    }

    public function scopeCloturee($query)
    {
        return $query->where('statut', 'CLOTUREE');
    }

    // ============================================================
    // HELPERS MÉTIER (pratiques dans le Blade)
    // ============================================================

    public function estOuverte(): bool
    {
        return $this->statut === 'OUVERTE';
    }

    public function estEnPreparation(): bool
    {
        return $this->statut === 'PREPARATION';
    }

    public function estCloturee(): bool
    {
        return $this->statut === 'CLOTUREE';
    }

    public function estArchivee(): bool
    {
        return $this->statut === 'ARCHIVEE';
    }

    /**
     * La campagne accepte-t-elle des déclarations ?
     */
    public function accepteDeclarations(): bool
    {
        return $this->estOuverte()
            && $this->date_ouverture?->lte(now())
            && ($this->date_cloture === null || $this->date_cloture->gte(now()));
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getEstOuverteAttribute(): bool
    {
        return $this->statut === 'OUVERTE'
            && $this->date_ouverture <= now()
            && ($this->date_cloture === null || $this->date_cloture >= now());
    }

    public function getDureeJoursAttribute(): int
    {
        if (!$this->date_ouverture || !$this->date_cloture) {
            return 0;
        }

        return (int) $this->date_ouverture->diffInDays($this->date_cloture);
    }

    // ============================================================
    // LABELS / COULEURS / ICÔNES (pour le Blade)
    // ============================================================

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'PREPARATION' => 'En préparation',
            'OUVERTE'     => 'Ouverte',
            'CLOTUREE'    => 'Clôturée',
            'ARCHIVEE'    => 'Archivée',
            default       => ucfirst(strtolower($this->statut ?? '')),
        };
    }

    public function getStatutColorAttribute(): string
    {
        return match ($this->statut) {
            'PREPARATION' => 'warn',      // ← aligné avec ton CSS ci-badge-warn
            'OUVERTE'     => 'ok',        // ← ci-badge-ok
            'CLOTUREE'    => 'ko',        // ← ci-badge-ko
            'ARCHIVEE'    => 'muted',     // ← ci-badge-muted
            default       => 'muted',
        };
    }

    public function getStatutIconAttribute(): string
    {
        return match ($this->statut) {
            'PREPARATION' => 'bx-pencil',
            'OUVERTE'     => 'bx-broadcast',
            'CLOTUREE'    => 'bx-check2-circle',
            'ARCHIVEE'    => 'bx-archive',
            default       => 'bx-help-circle',
        };
    }
}
