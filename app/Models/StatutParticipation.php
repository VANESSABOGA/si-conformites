<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatutParticipation extends Model
{
    protected $table = 'statut_participations';

    protected $fillable = [
        'id_campagne',
        'id_collaborateur',
        'a_declare',
        'date_declaration',
        'reponse',
        'relance_envoyee',
        'nb_relances',
    ];

    protected $casts = [
        'a_declare'        => 'boolean',
        'relance_envoyee'  => 'boolean',
        'date_declaration' => 'datetime',
        'nb_relances'      => 'integer',
    ];

    public function campagne()
    {
        return $this->belongsTo(Campagne::class, 'id_campagne');
    }

    public function collaborateur()
    {
        return $this->belongsTo(Agent::class, 'id_collaborateur');
    }
}