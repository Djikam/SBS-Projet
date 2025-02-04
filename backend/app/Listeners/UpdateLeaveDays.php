<?php
namespace App\Listeners;

use App\Events\InterventionUpdated;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateLeaveDays
{
    public function __construct()
    {
        //
    }

    public function handle(InterventionUpdated $event)
    {
        $intervention = $event->intervention;
        $user = User::findOrFail($intervention->user_id);
        $client = $intervention->client;
        $oldColor = $event->oldColor;

        // Calculer le nombre de jours de l'intervention
        $startDate = new \DateTime($intervention->date_debut);
        $endDate = new \DateTime($intervention->date_fin);
        $interval = $startDate->diff($endDate);
        $days = $interval->days + 1; // Inclure le jour de début

        // Vérifier le nom du client de manière insensible à la casse
        $clientName = strtolower($client->nom);

        if ($oldColor != $intervention->color) {
            if ($clientName == 'conge' || $clientName == 'congé' || $clientName == 'CONGE') {
                if ($user->conge >= $days) {
                    $user->conge -= $days; // Soustraire les jours de congé
                    $user->save();
                } 
            } elseif ($clientName == 'recuperation' || $clientName == 'récupération' || $clientName == 'RECUPERATION') {
                if ($user->recuperation >= $days) {
                    $user->recuperation -= $days; // Soustraire les jours de récupération
                    $user->save();
                }
            }
        }
    }
}
