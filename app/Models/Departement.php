<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\LectureSeule;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory, LectureSeule;

    protected $fillable = [
        'direction_id',
        'code_direction',
        'code',
        'libelle',
        'description',
        'statut'
    ];

    public function direction()
    {
        return $this->belongsTo(Direction::class , 'direction_id' , 'id');
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
