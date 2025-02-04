<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MailResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'firstconnect',
        'agence_id',
        'recuperation',
        'conge',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime'];



    public function userCompetences()
    {
        return $this->hasMany(UserCompetence::class);
    }

    public function competences()
    {
        return $this->hasMany(UserCompetence::class);
    }


    // app/Models/User.php
    public function demandes()
    {
        return $this->hasMany(Demande::class);
    }

    public function agence()
    {
        return $this->belongsTo(Agence::class, 'agence_id');
    }

    /**
     * Relation entre un utilisateur et ses interventions via une table pivot.
     */
    public function interventions()
    {
        return $this->belongsToMany(Intervention::class, 'user_intervention', 'user_id', 'intervention_id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification($token));
    }



}
