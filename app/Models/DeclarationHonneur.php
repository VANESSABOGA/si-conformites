<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeclarationHonneur extends Model
{
    protected $table = 'declaration_honneurs';

    protected $fillable = [
        'id_declaration',
        'certification_honneur',
        'engagement_signalement',
        'date_certification',
    ];

    protected $casts = [
        'certification_honneur'  => 'boolean',
        'engagement_signalement' => 'boolean',
        'date_certification'     => 'datetime',
    ];

    public function declaration()
    {
        return $this->belongsTo(Declaration::class, 'id_declaration');
    }

}
