<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'abbreviation', 'color']; // Ajout du champ abbreviation

    /**
     * Un client peut avoir plusieurs interventions.
     */
    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }
    public function demandes()
    {
        return $this->hasMany(Demande::class);
    }
}
