<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agence extends Model
{
    use HasFactory;

    protected $fillable = ["name", "pays"];

    public function users()
    {
        return $this->hasMany(User::class, 'agence_id');
    }

    public function joursFeries()
    {
        return $this->hasMany(JourFerie::class, 'agence_id');
    }
}
