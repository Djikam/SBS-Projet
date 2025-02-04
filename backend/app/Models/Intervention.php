<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = [

        'commentaire',  // anciennement 'description'
        'date_debut',
        'date_fin',
        'cote_importance',
        'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_intervention', 'intervention_id', 'user_id');
    }

}
