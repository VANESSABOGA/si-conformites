<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\LectureSeule;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory, LectureSeule;

    protected $fillable = [
        'departement_id',
        'code',
        'libelle',
        'description',
        'statut'
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }
}
