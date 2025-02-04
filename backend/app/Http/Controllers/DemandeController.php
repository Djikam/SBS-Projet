<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DemandeController extends Controller
{
    public function index(Request $request)
    {
        // Pagination parameters
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        // Fetch demands with necessary relationships and selected columns
        $demandes = Demande::with([
            'user:id,name', // Select only necessary columns
            'client:id,nom', // Select only necessary columns
            'module:id,nom,domaine_competence_id', // Select only necessary columns
            'module.domaineCompetence:id,nom' // Load the domaineCompetence relation
        ])
        ->orderBy('created_at', 'desc') // Sort by creation date from newest to oldest
        ->paginate($perPage, ['*'], 'page', $page);

        // Transform the response to include the name of the client, module, and domaineCompetence
        $transformedDemandes = $demandes->map(function ($demande) {
            return [
                'id' => $demande->id,
                'user_id' => $demande->user_id,
                'motif' => $demande->motif,
                'date_fin' => $demande->date_fin,
                'date_debut' => $demande->date_debut,
                'description' => $demande->description,
                'status' => $demande->status,
                'created_at' => $demande->created_at,
                'updated_at' => $demande->updated_at,
                'comment' => $demande->comment,
                'client_id' => $demande->client_id,
                'module_id' => $demande->module_id,
                'user' => $demande->user,
                'client_nom' => $demande->client->nom ?? null,
                'module_nom' => $demande->module->nom ?? null,
                'domaine_competence_nom' => $demande->module->domaineCompetence->nom ?? null,
            ];
        });

        // Return the paginated and transformed demands as a JSON response
        return response()->json(['demandes' => $transformedDemandes]);
    }



    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if the user is of type 3
        if ($user->type !== 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'motif' => 'required|string|max:255',
            'date_fin' => 'required|date',
            'date_debut' => 'required|date',
            'description' => 'required|string',
            'comment' => 'nullable|string',
            'client_id' => 'required|exists:clients,id', // Ajout de la validation pour client_id
            'module_id' => 'required|exists:modules,id', // Ajout de la validation pour module_id
        ]);

        // Set default values for status
        $validated['status'] = 1; // En cours

        // Create the request linked to the user
        $demande = $user->demandes()->create($validated);

        return response()->json(['demande' => $demande], 201);
    }

    public function showit()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Récupérer toutes les demandes de l'utilisateur connecté avec les relations 'user', 'client', et 'module'
        $demandes = Demande::with(['user', 'client', 'module'])
            ->orderBy('created_at', 'desc')
            ->where('user_id', $user->id)
            ->get();

        // Vérifier si des demandes existent
        if ($demandes->isEmpty()) {
            return response()->json(['error' => 'No demands found for this user'], 404);
        }

        // Retourner les demandes
        return response()->json(['demandes' => $demandes]);
    }

    public function show($id)
    {
        $demande = Demande::with(['user', 'client', 'module'])->find($id);

        if (!$demande) {
            return response()->json(['error' => 'Demande not found'], 404);
        }

        return response()->json(['demande' => $demande]);
    }

    public function update(Request $request, $id)
    {
        $demande = Demande::find($id);

        $validated = $request->validate([
            'motif' => 'sometimes|string|max:255',
            'status' => 'sometimes|integer',
            'comment' => 'sometimes|string',
            'date_fin' => 'sometimes|date',
            'date_debut' => 'sometimes|nullable|date',
            'description' => 'sometimes|string',
            'client_id' => 'sometimes|exists:clients,id', // Ajout de la validation pour client_id
            'module_id' => 'sometimes|exists:modules,id', // Ajout de la validation pour module_id
        ]);

        $demande->update($validated);

        return response()->json(['demande' => $demande]);
    }

    public function destroy($id)
    {
        $demande = Demande::find($id);
        $demande->delete();

        return response()->json(['message' => 'Demande deleted successfully']);
    }
}
