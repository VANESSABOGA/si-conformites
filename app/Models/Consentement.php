<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consentement extends Model
{
    protected $table = 'consentements';

    protected $fillable = [
        'id_declaration',
        'lecture_notice',
        'autorisation_traitement',
        'engagement_declarant',
        'date_consentement',
        'version_notice',
    ];

    protected $casts = [
        'lecture_notice'          => 'boolean',
        'autorisation_traitement' => 'boolean',
        'engagement_declarant'    => 'boolean',
        'date_consentement'       => 'datetime',
    ];

    public function declaration()
    {
        return $this->belongsTo(Declaration::class, 'id_declaration');
    }
}