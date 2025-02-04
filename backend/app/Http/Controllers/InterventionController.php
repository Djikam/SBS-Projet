<?php

namespace App\Http\Controllers;

use App\Events\InterventionCreated;
use App\Events\InterventionUpdated;
use App\Events\InterventionDeleted;
use App\Models\Client;
use App\Models\Intervention;
use App\Models\User;
use App\Models\user_intervention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;



class InterventionController extends Controller
{

    /**
     * Affiche les interventions d'un utilisateur spécifique.
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserInterventions($userId)
    {
        try {
            // Fetch the user with the given ID along with their interventions and related data
            $user = User::with(['interventions.client', 'agence'])
                ->find($userId);

            // Check if the user exists
            if (!$user) {
                return response()->json(['message' => 'Utilisateur non trouvé'], 404);
            }

            // Map the user data to the desired format
            $result = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'type' => $user->type,
                'agence' => $user->agence ? [
                    'id' => $user->agence->id,
                    'name' => $user->agence->name,
                    'pays' => $user->agence->pays,
                ] : null,
                'interventions' => $user->interventions->map(function ($intervention) {
                    return [
                        'id' => $intervention->id,
                        'commentaire' => $intervention->commentaire,
                        'client_name' => $intervention->client->nom,
                        'client_id' => $intervention->client->id,
                        'client_abbreviation' => $intervention->client->abbreviation,
                        'client_color' => $intervention->client->color, // Ajout de la couleur du client
                        'date_debut' => $intervention->date_debut,
                        'date_fin' => $intervention->date_fin,
                    ];
                }),
            ];

            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des interventions de l\'utilisateur.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function getAuthenticatedUserInterventions(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Utilisateur non trouvé'], 404);
            }

            // Charger les interventions de l'utilisateur avec le client et trier par date de création
            $user->load(['interventions' => function ($query) {
                $query->with('client')->orderBy('created_at', 'desc');
            }]);

            $result = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'type' => $user->type,
                'agence' => $user->agence ? [
                    'id' => $user->agence->id,
                    'name' => $user->agence->name,
                    'pays' => $user->agence->pays,
                ] : null,
                'interventions' => $user->interventions->map(function ($intervention) {
                    return [
                        'id' => $intervention->id,
                        'commentaire' => $intervention->commentaire,
                        'client_name' => $intervention->client->nom,
                        'client_id' => $intervention->client->id,
                        'client_abbreviation' => $intervention->client->abbreviation,
                        'client_color' => $intervention->client->color, // Ajout de la couleur du client
                        'date_debut' => $intervention->date_debut,
                        'date_fin' => $intervention->date_fin,
                    ];
                }),
            ];

            return response()->json(['users' => [$result]], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des interventions de l\'utilisateur.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function index()
    {
        $interventions = Intervention::with('client', 'users')->get();
        return response()->json($interventions);
    }
    public function index1()
    {
        try {
            $users = User::with(['interventions.client', 'agence'])
            ->where('type', '!=', 5)
            ->where('active', 1) // Ajout de la condition pour vérifier que 'active' est égal à 1
            ->orderBy('name', 'asc') // Trier par nom en ordre alphabétique (de A à Z)
                ->get();

            $result = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'type' => $user->type,
                    'agence' => $user->agence ? [
                        'id' => $user->agence->id,
                        'name' => $user->agence->name,
                        'pays' => $user->agence->pays,
                    ] : null,
                    'interventions' => $user->interventions->map(function ($intervention) {
                        return [
                            'id' => $intervention->id,
                            'commentaire' => $intervention->commentaire,
                            'client_name' => $intervention->client->nom,
                            'client_id' => $intervention->client->id,
                            'client_abbreviation' => $intervention->client->abbreviation,
                            'client_color' => $intervention->client->color, // Ajout de la couleur du client
                            'date_debut' => $intervention->date_debut,
                            'date_fin' => $intervention->date_fin,
                        ];
                    }),
                ];
            });

            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des données des utilisateurs.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getInterventionsByClient($clientId)
    {
        $interventions = Intervention::where('client_id', $clientId)->get();

        return response()->json($interventions);
    }


 public function store(Request $request)
{
    try {
        // Log all incoming data for verification
        Log::info('Intervention Store Request', $request->all());

        // Explicit user verification
        $userExists = User::where('id', $request->user_id)->first();

        if (!$userExists) {
            Log::error('User not found', ['user_id' => $request->user_id]);
            return response()->json([
                'message' => 'Utilisateur non trouvé',
                'details' => User::where('id', $request->user_id)->get()
            ], 404);
        }

        // Create intervention with all validated data
        $intervention = Intervention::create([
            'commentaire' => $request->commentaire,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'client_id' => $request->client_id,
        ]);

        // Attach user to intervention
        $intervention->users()->attach($request->user_id);

        // Get the client associated with the intervention
        $client = Client::find($request->client_id);

        if (!$client) {
            Log::error('Client not found', ['client_id' => $request->client_id]);
            return response()->json([
                'message' => 'Client non trouvé',
            ], 404);
        }

        // Calculate the number of days of the intervention
        $dateDebut = Carbon::parse($request->date_debut);
        $dateFin = Carbon::parse($request->date_fin);
        $numberOfDays = $dateDebut->diffInDays($dateFin) + 1; // +1 to include both start and end dates

        // Update user's conge or recuperation based on client's color
        if ($client->color == 4) {
            $userExists->conge -= $numberOfDays;
        } elseif ($client->color == 6) {
            $userExists->recuperation -= $numberOfDays;
        }

        // Save the updated user
        $userExists->save();

        return response()->json($intervention, 201);

    } catch (\Exception $e) {
        Log::error('Intervention Creation Error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'message' => 'Erreur lors de la création',
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function show($id)
    {
        $intervention = Intervention::with(['client', 'users', 'users.domainesCompetence.niveauCompetence'])->findOrFail($id);
        return response()->json($intervention);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'commentaire' => 'nullable|string',
            'date_debut' => 'sometimes|date',
            'date_fin' => 'sometimes|date|after_or_equal:date_debut',
            'client_id' => 'sometimes|exists:clients,id',
            'user_id' => 'sometimes|exists:users,id',
            'color' => 'sometimes|integer', // Ajouter la validation pour le type
        ]);

        $intervention = Intervention::findOrFail($id);
        $oldColor = $intervention->color;
        $oldDateDebut = Carbon::parse($intervention->date_debut);
        $oldDateFin = Carbon::parse($intervention->date_fin);
        $oldNumberOfDays = $oldDateDebut->diffInDays($oldDateFin) + 1;

        $intervention->update($validated);

        $newDateDebut = Carbon::parse($intervention->date_debut);
        $newDateFin = Carbon::parse($intervention->date_fin);
        $newNumberOfDays = $newDateDebut->diffInDays($newDateFin) + 1;

        $differenceInDays = $newNumberOfDays - $oldNumberOfDays;

        // Get the client associated with the intervention
        $client = Client::find($intervention->client_id);

        if (!$client) {
            Log::error('Client not found', ['client_id' => $intervention->client_id]);
            return response()->json([
                'message' => 'Client non trouvé',
            ], 404);
        }

        // Get the user associated with the intervention
        $user = User::find($request->user_id);

        if (!$user) {
            Log::error('User not found', ['user_id' => $request->user_id]);
            return response()->json([
                'message' => 'Utilisateur non trouvé',
            ], 404);
        }

        // Verify that the user is associated with the intervention
        $userIntervention = $intervention->users()->where('user_id', $user->id)->first();

        if (!$userIntervention) {
            // If the user is not associated with the intervention, associate them
            $intervention->users()->attach($user->id);
        }

        // Update user's conge or recuperation based on client's color
        if ($client->color == 4) {
            $user->conge -= $differenceInDays;
        } elseif ($client->color == 6) {
            $user->recuperation -= $differenceInDays;
        }

        // Save the updated user
        $user->save();

        return response()->json($intervention);
    }


    public function destroy($id)
    {
        try {
            // Récupérer l'intervention existante
            $intervention = Intervention::findOrFail($id);

            // Récupérer le client associé à l'intervention
            $client = Client::find($intervention->client_id);

            if (!$client) {
                Log::error('Client not found', ['client_id' => $intervention->client_id]);
                return response()->json([
                    'message' => 'Client non trouvé',
                ], 404);
            }

            // Récupérer l'utilisateur associé à l'intervention
            $user = $intervention->users()->first();

            if (!$user) {
                Log::error('User not found', ['intervention_id' => $id]);
                return response()->json([
                    'message' => 'Utilisateur non trouvé',
                ], 404);
            }

            // Calculer le nombre de jours de l'intervention
            $dateDebut = Carbon::parse($intervention->date_debut);
            $dateFin = Carbon::parse($intervention->date_fin);
            $numberOfDays = $dateDebut->diffInDays($dateFin) + 1; // +1 to include both start and end dates

            // Mettre à jour les attributs de l'utilisateur en fonction de la couleur du client
            if ($client->color == 4) {
                $user->conge += $numberOfDays;
            } elseif ($client->color == 6) {
                $user->recuperation += $numberOfDays;
            }

            // Sauvegarder les modifications de l'utilisateur
            $user->save();

            // Détacher l'utilisateur de l'intervention (supprime l'entrée dans user_intervention)
            $intervention->users()->detach($user->id);

            // Supprimer l'intervention
            $intervention->delete();

            return response()->json(['message' => 'Intervention supprimée avec succès']);

        } catch (\Exception $e) {
            Log::error('Intervention Deletion Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Erreur lors de la suppression',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function assignUserToIntervention(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'intervention_id' => 'required|exists:interventions,id',
        ]);


        // Créer l'affectation dans la table user_intervention
        $affectation = new user_intervention();
        $affectation->user_id = $request->user_id;
        $affectation->intervention_id = $request->intervention_id;

        if ($affectation->save()) {
            return response()->json(['message' => 'Utilisateur affecté avec succès'], 200);
        } else {
            return response()->json(['message' => 'Erreur lors de l\'affectation de l\'utilisateur'], 500);
        }
    }

    public function unassignUser($interventionId, $userId)
    {
        $intervention = Intervention::findOrFail($interventionId);
        $intervention->users()->detach($userId);

        return response()->json(['message' => 'Affectation retirée avec succès']);
    }
    public function storeUserIntervention(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'intervention_id' => 'required|exists:interventions,id',
        ]);

        // Create the relationship
        user_intervention::create([
            'user_id' => $validated['user_id'],
            'intervention_id' => $validated['intervention_id'],
        ]);

        return response()->json(['message' => 'User assigned to intervention successfully.'], 201);
    }
    // Méthode pour récupérer les utilisateurs affectés à une intervention donnée
    public function getAssignedUsers($interventionId)
    {
        try {
            // On trouve l'intervention par son ID
            $intervention = Intervention::findOrFail($interventionId);

            // On récupère les utilisateurs affectés à cette intervention
            $assignedUsers = $intervention->users; // En supposant que la relation existe

            return response()->json($assignedUsers, 200); // Retourne les utilisateurs en JSON
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération des utilisateurs affectés'], 500);
        }
    }
    // Méthode pour retirer un utilisateur affecté à une intervention
    public function removeUserFromIntervention($interventionId, $userId)
    {
        try {
            Log::info("Suppression utilisateur de l'intervention : ", ['interventionId' => $interventionId, 'userId' => $userId]);


            // Supprimer l'entrée correspondante dans la table pivot "user_intervention"
            DB::table('user_intervention')
                ->where('intervention_id', $interventionId)
                ->where('user_id', $userId)
                ->delete();
            return response()->json(['message' => 'Utilisateur retiré avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la suppression de l\'affectation'], 500);
        }
    }

    public function deleteIntervention($id)
    {
        try {
            // Récupérer l'intervention
            $intervention = Intervention::findOrFail($id);

            // Supprimer toutes les relations avec les utilisateurs (dans la table pivot "user_intervention")
            $intervention->users()->detach(); // Détache tous les utilisateurs liés à l'intervention

            // Supprimer l'intervention elle-même
            $intervention->delete();

            return response()->json(['message' => 'Intervention supprimée avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la suppression de l\'intervention'], 500);
        }
    }

}
