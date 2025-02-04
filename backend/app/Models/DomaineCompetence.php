<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomaineCompetence extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];
    protected $table = 'domaines_competences';

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}
