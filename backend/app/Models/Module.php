<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'domaine_competence_id'];

    public function domaineCompetence()
    {
        return $this->belongsTo(DomaineCompetence::class);
    }
    public function demandes()
    {
        return $this->hasMany(Demande::class);
    }
}
