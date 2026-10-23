<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\LectureSeule;
use Illuminate\Database\Eloquent\Model;

class Poste extends Model
{
    use HasFactory, LectureSeule;

    protected $fillable = [
        'code',
        'nom',
        'libelle',
        'description',
        'categorie',
        'description',
        'code',
        'niveau_hierarchique',
        'direction_id',
        'departement_id',
        'statut'
    ];

    // `id`, `nom`, `code`, `libelle`, `categorie`, `description`, `departement_id`, `direction_id`, `actif`, `created_at`, `updated_at`
    

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id', 'id');
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'direction_id', 'id');
    }


}
