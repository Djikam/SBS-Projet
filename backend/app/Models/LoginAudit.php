<?php

// app/Models/LoginAudit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'ip_address',
        'success',
        'message',
    ];
}

