<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\LectureSeule;
use Illuminate\Database\Eloquent\Model;

class AgentContact extends Model
{
    use HasFactory, LectureSeule;

    protected $fillable = [
        'agent_id',
        'telephone_pro',
        'telephone_perso',
        'whatsapp',
        'email_pro',
        'email_perso',
        'adresse',
        'ville',
        'commune',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }
}
