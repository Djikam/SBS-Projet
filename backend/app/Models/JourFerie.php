<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JourFerie extends Model
{
    use HasFactory;

    protected $table = "jours_feries";
    protected $fillable = ['date_ferie', 'nom_ferie', 'agence_id'];

    public function agence()
    {
        return $this->belongsTo(Agence::class, 'agence_id');
    }
}
