<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'motif',
        'date_fin',
        'date_debut',
        'description',
        'status',
        'comment',
        'client_id', // Ajout de l'attribut client_id
        'module_id', // Ajout de l'attribut module_id
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
