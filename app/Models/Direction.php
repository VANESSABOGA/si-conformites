<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\LectureSeule;
use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    use HasFactory, LectureSeule;

    protected $fillable = ['code', 'libelle', 'description', 'statut'];

    public function departements()
    {
        return $this->hasMany(Departement::class);
    }
}
