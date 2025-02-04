<?php
namespace App\Listeners;

use App\Events\InterventionDeleted;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RestoreLeaveDays
{
    public function __construct()
    {
        //
    }

    public function handle(InterventionDeleted $event)
    {
        $intervention = $event->intervention;
        $user = User::findOrFail($intervention->user_id);
        $client = $intervention->client;

        // Calculer le nombre de jours de l'intervention
        $startDate = new \DateTime($intervention->date_debut);
        $endDate = new \DateTime($intervention->date_fin);
        $interval = $startDate->diff($endDate);
        $days = $interval->days + 1; // Inclure le jour de début

        // Vérifier le nom du client de manière insensible à la casse
        $clientName = strtolower($client->nom);

        if ($clientName == 'conge' || $clientName == 'congé' || $clientName == 'CONGE') {
            $user->conge += $days; // Restaurer les jours de congé
            $user->save();
        } elseif ($clientName == 'recuperation' || $clientName == 'récupération' || $clientName == 'RECUPERATION') {
            $user->recuperation += $days; // Restaurer les jours de récupération
            $user->save();
        }
    }
}
