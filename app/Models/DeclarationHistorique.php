<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeclarationHistorique extends Model
{
    protected $table = 'declaration_historiques';

    protected $fillable = [
        'id_declaration',
        'ancien_statut',
        'nouveau_statut',
        'action',
        'commentaire',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function declaration()
    {
        return $this->belongsTo(Declaration::class, 'id_declaration');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}