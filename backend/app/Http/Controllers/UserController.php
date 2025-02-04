<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\User;
use App\Models\UserCompetence;
use App\Models\UserDomaineCompetence;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUser()
    {
        // Ensure the user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    
        // Eager load the necessary relationships
        $user = $user->load(['agence', 'userCompetences.domaineCompetence', 'userCompetences.module', 'userCompetences.niveauCompetence']);
    
        // Debugging: Check if the user and relationships are loaded correctly
        \Log::info('User:', ['user' => $user]);
        \Log::info('Agence:', ['agence' => $user->agence]);
        \Log::info('User Competences:', ['userCompetences' => $user->userCompetences]);
    
        // Prepare the user information
        $userInfo = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'type' => $user->type,
            'agence_id' => $user->agence_id,
            'agence_name' => $user->agence ? $user->agence->name : null,
            'recuperation' => $user->recuperation,
            'conge' => $user->conge,
            'domaines_competence' => $user->userCompetences->map(function ($competence) {
                return [
                    'domaine_nom' => $competence->domaineCompetence->nom ?? 'Non attribué', // Nom du domaine
                    'module_nom' => $competence->module->nom ?? 'Non attribué', // Nom du module
                    'niveau_competence' => $competence->niveauCompetence->niveau ?? 'Non attribué', // Niveau de compétence
                    'user_domaine_competence_id' => $competence->id ?? null, // ID de la table pivot pour suppression
                ];
            }),
        ];
    
        // Return the user information as JSON
        return response()->json($userInfo);
    }
    


    public function getUserSkills($id)
    {
        try {
            // Fetch the user with their skills (domaines de compétence) including niveau_competence
            $user = User::with([
                'domaineCompetence' => function ($query) {
                    $query->with('niveauCompetence')->orderBy('nom', 'asc'); // Trier par nom en ordre alphabétique (de A à Z)
                }
            ])->findOrFail($id);

            // Return the skills as a JSON response
            return response()->json($user->domaineCompetence, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User or skills not found'], 404);
        }
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
     {
         // Validation des données, incluant l'agence
         $validator = Validator::make($request->all(), [
             'name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users',
             'password' => 'required|string|min:8',
             'type' => 'required|in:0,1',
             'agence_id' => 'required|exists:agences,id',  // Assurez-vous que l'agence existe dans la base de données
         ]);

         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], 422);
         }

         // Création de l'utilisateur, ajout de l'agence
         $user = User::create([
             'name' => $request->name,
             'email' => $request->email,
             'password' => Hash::make($request->password),
             'statut' => $request->type,
             'agence_id' => $request->agence_id,  // Ajouter l'ID de l'agence
         ]);

         return response()->json(['user' => $user], 201);
     }



    public function getTypeZeroUsers()
    {
        $users = User::where('type', '!=', 5)
            ->with(['interventions', 'userCompetences.domaineCompetence', 'userCompetences.module', 'userCompetences.niveauCompetence'])
            ->orderBy('name', 'asc') // Trier par nom en ordre alphabétique (de A à Z)
            ->get();

        return response()->json($users);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Valider les données de la requête
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255',
            'type' => 'sometimes|integer',
            'agence_id' => 'sometimes|integer', // Ajouter l'ID de l'agence
            'conge' => 'sometimes|integer',
            'recuperation' => 'sometimes|integer',
            'domaines' => 'sometimes|array',
            'domaines.*.domaine_competence_id' => 'required|exists:domaines_competences,id',
            'domaines.*.niveau_competence_id' => 'required|exists:niveaux_competences,id',
        ]);

        // Retourner les erreurs de validation s'il y en a
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        // Trouver l'utilisateur par ID
        $user = User::findOrFail($id);

        // Mettre à jour les informations de l'utilisateur
        $user->update($request->only(['name', 'email', 'type', 'agence_id', 'conge', 'recuperation']));

        // Mettre à jour les compétences de l'utilisateur
        if ($request->has('domaines')) {
            foreach ($request->domaines as $competence) {
                UserCompetence::updateOrCreate(
                    ['user_id' => $user->id, 'domaine_competence_id' => $competence['domaine_competence_id']],
                    ['niveau_competence_id' => $competence['niveau_competence_id']]
                );
            }
        }

        return response(['message' => 'User updated successfully', 'user' => $user], 200);
    }


    public function up(Request $request, $userId)
    {
        // Valider la requête
        $request->validate([
            'competences' => 'required|array',
            'competences.*.domaine_competence_id' => 'required|exists:domaines_competences,id',
            'competences.*.module_id' => 'required|exists:modules,id',
            'competences.*.niveau_competence_id' => 'required|exists:niveaux_competences,id',
        ]);

        // Récupérer l'utilisateur
        $user = User::findOrFail($userId);

        // Boucler à travers les compétences et les ajouter
        foreach ($request->competences as $competence) {
            UserCompetence::create([
                'user_id' => $user->id,
                'domaine_competence_id' => $competence['domaine_competence_id'],
                'module_id' => $competence['module_id'],
                'niveau_competence_id' => $competence['niveau_competence_id'],
            ]);
        }

        return response()->json(['message' => 'Compétences ajoutées avec succès.'], 201);
    }

    public function showAll($id)
    {
        try {
            // Récupérer l'utilisateur avec ses compétences et niveaux associés
            $user = User::with(['userCompetences' => function ($query) {
                $query->with(['domaineCompetence', 'module', 'niveauCompetence']);
            }])->findOrFail($id);

            // Formatage de la réponse JSON avec vérification si les domaines et niveaux existent
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'type' => $user->type,
                'agence_id' => $user->agence_id,
                'recuperation' =>$user->recuperation,
                'conge' => $user->conge,
                'domaines_competence' => $user->userCompetences->map(function ($competence) {
                    return [
                        'domaine_nom' => $competence->domaineCompetence->nom ?? 'Non attribué', // Nom du domaine
                        'module_nom' => $competence->module->nom ?? 'Non attribué', // Nom du module
                        'niveau_competence' => $competence->niveauCompetence->niveau ?? 'Non attribué', // Niveau de compétence
                        'user_domaine_competence_id' => $competence->id ?? null, // ID de la table pivot pour suppression
                    ];
                }),
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Gestion de l'exception NotFound
            return response()->json([
                'message' => 'Utilisateur non trouvé.',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            // Gestion des erreurs générales
            return response()->json([
                'message' => 'Erreur lors de la récupération des données de l\'utilisateur.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function updatePassword(Request $request, $id)
    {
        // Valider les données de la requête
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Retourner les erreurs de validation s'il y en a
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        // Trouver l'utilisateur par ID
        $user = User::findOrFail($id);

        // Hacher le nouveau mot de passe
        $user->password = Hash::make($request->password);
        $user->save();

        return response(['message' => 'Password updated successfully'], 200);
    }

    public function updateFirstConnect(Request $request, $id)
    {
        // Valider les données de la requête
        $validator = Validator::make($request->all(), [
            'firstconnect' => 'required|string|in:ON,NO',
        ]);

        // Retourner les erreurs de validation s'il y en a
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        // Trouver l'utilisateur par ID
        $user = User::findOrFail($id);

        // Mettre à jour l'attribut firstconnect
        $user->firstconnect = $request->firstconnect;
        $user->save();

        return response(['message' => 'First connect status updated successfully'], 200);
    }

    public function deactivate($id)
    {
        // Trouver l'utilisateur par ID
        $user = User::findOrFail($id);

        // Mettre à jour l'attribut active
        $user->active = false;
        $user->save();

        return response(['message' => 'User deactivated successfully'], 200);
    }

    public function activate($id)
    {
        // Trouver l'utilisateur par ID
        $user = User::findOrFail($id);

        // Mettre à jour l'attribut active
        $user->active = true;
        $user->save();

        return response(['message' => 'User activated successfully'], 200);
    }

    /**
     * Attribuer un domaine de compétence avec un niveau à un utilisateur.
     **/

    public function addCompetence(Request $request, $id)
    {
        $request->validate([
            'domaine_competence_id' => 'required|exists:domaines_competences,id',
            'niveau_competence_id' => 'required|exists:niveaux_competences,id',
        ]);

        UserCompetence::create([
            'user_id' => $id,
            'domaine_competence_id' => $request->domaine_competence_id,
            'module_id' => $request->module_id,
            'niveau_competence_id' => $request->niveau_competence_id,
        ]);

        return response()->json(['message' => 'Competence added successfully']);
    }

    /**
     * Retirer un domaine de compétence d'un utilisateur.
     */
    public function removeCompetence($userId, $domaineId)
    {
        $user = User::findOrFail($userId);
        $user->domainesCompetence()->detach($domaineId);

        return response()->json(['message' => 'Domaine de compétence retiré avec succès']);
    }

    /**
     * Afficher un utilisateur spécifique.
     */
    public function showCompetence($id)
    {
        $user = User::with('domainesCompetence')->findOrFail($id);
        return response()->json($user);
    }

    public function getAvailableUsers(Request $request, $interventionId)
    {
        // Récupérer les dates de l'intervention
        $intervention = Intervention::find($interventionId);

        if (!$intervention) {
            return response()->json(['message' => 'Intervention non trouvée'], 404);
        }

        $dateDebut = $intervention->date_debut;
        $dateFin = $intervention->date_fin;

        // Récupérer les utilisateurs de type 0 qui ne sont pas affectés à une autre intervention dans cette période
        $users = User::with('domaineCompetence') // Charger les domaines de compétence
            ->where('type', 0) // Filtrer par type 0
            ->whereNotIn('id', function ($query) use ($dateDebut, $dateFin) {
                $query->select('user_id')
                    ->from('user_intervention')
                    ->join('interventions', 'interventions.id', '=', 'user_intervention.intervention_id')
                    ->where(function ($q) use ($dateDebut, $dateFin) {
                        $q->whereBetween('interventions.date_debut', [$dateDebut, $dateFin])
                            ->orWhereBetween('interventions.date_fin', [$dateDebut, $dateFin]);
                    });
            })->get();

        // Créer un tableau pour stocker les utilisateurs avec leurs domaines de compétence
        $usersArray = [];
        foreach ($users as $user) {
            $domaineCompetenceArray = [];
            foreach ($user->domaineCompetence as $domaine) {
                $domaineCompetenceArray[] = [
                    'id' => $domaine->id,
                    'nom' => $domaine->nom,
                ];
            }

            $usersArray[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'domaine_competence' => $domaineCompetenceArray,
            ];
        }

        return response()->json($usersArray);
    }
}
