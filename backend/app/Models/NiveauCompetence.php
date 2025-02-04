<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NiveauCompetence extends Model
{
    use HasFactory;

    protected $fillable = ['niveau'];
    protected $table = 'niveaux_competences';
}
