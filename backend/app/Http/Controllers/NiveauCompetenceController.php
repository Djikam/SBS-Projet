<?php

namespace App\Http\Controllers;

use App\Models\NiveauCompetence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NiveauCompetenceController extends Controller
{
    /**
     * Récupère tous les niveaux de compétence.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $niveaux = NiveauCompetence::all();
        return response()->json($niveaux);
    }

    /**
     * Ajoute un nouveau niveau de compétence.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'niveau' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Création d'un nouveau niveau de compétence
        $niveau = NiveauCompetence::create($request->only('niveau'));

        return response()->json($niveau, 201);
    }

    /**
     * Met à jour un niveau de compétence existant.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'niveau' => 'sometimes|required|string|max:255',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Recherche du niveau de compétence
        $niveau = NiveauCompetence::find($id);
        if (!$niveau) {
            return response()->json(['message' => 'Niveau de compétence non trouvé.'], 404);
        }

        // Mise à jour des données
        $niveau->update($request->only('niveau'));

        return response()->json($niveau);
    }

    /**
     * Supprime un niveau de compétence.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Recherche du niveau de compétence
        $niveau = NiveauCompetence::find($id);
        if (!$niveau) {
            return response()->json(['message' => 'Niveau de compétence non trouvé.'], 404);
        }

        // Suppression du niveau de compétence
        $niveau->delete();

        return response()->json(['message' => 'Niveau de compétence supprimé.']);
    }
}
