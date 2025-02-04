<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_intervention extends Model
{
    use HasFactory;
    protected $table = 'user_intervention';

    protected $fillable = [
        'user_id',
        'intervention_id',
    ];
}
