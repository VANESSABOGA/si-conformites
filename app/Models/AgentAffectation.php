<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\LectureSeule;
use Illuminate\Database\Eloquent\Model;

class AgentAffectation extends Model
{
    use HasFactory, LectureSeule;

    protected $fillable = [
        'agent_id',
        'direction_id',
        'departement_id',
        'service_id',
        'poste_id',
        'rang_hierarchique_id',
        'superieur_id',
        'date_debut',
        'date_fin',
        'statut',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin'   => 'date',
    ];

    // ============================================
    // Relations
    // ============================================

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'direction_id', 'id');
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function poste()
    {
        return $this->belongsTo(Poste::class, 'poste_id', 'id');
    }


    /**
     * Le supérieur hiérarchique (N+1) associé à cette affectation.
     */
    public function superieur()
    {
        return $this->belongsTo(Agent::class, 'superieur_id', 'id');
    }

    // Alias pour compatibilité (si tu utilises 'superviseur' quelque part)
    public function superviseur()
    {
        return $this->superieur();
    }
}
