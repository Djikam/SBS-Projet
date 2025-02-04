<?php

namespace App\Http\Controllers;

use App\Models\DomaineCompetence;
use App\Models\Module;
use App\Models\UserCompetence;
use Illuminate\Http\Request;

class DomaineCompetenceController extends Controller
{
    /**
     * Afficher tous les domaines.
     */
    /**
     * Afficher tous les domaines.
     */
    public function index()
    {
        $domaines = DomaineCompetence::with('modules')->orderBy('created_at', 'desc')->get();
        return response()->json($domaines);
    }



    /**
     * Créer un domaine avec les niveaux en fonction du type sélectionné.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string', // Validation pour la clé étrangère
        ]);

        // Créer le domaine de compétence
        $domaine = DomaineCompetence::create($request->all());

        // Créer les modules associés au domaine de compétence
        if ($request->has('modules')) {
            foreach ($request->input('modules') as $moduleData) {
                $moduleData['domaine_competence_id'] = $domaine->id;
                Module::create($moduleData);
            }
        }

        return response()->json($domaine, 201);
    }


    /**
     * Mettre à jour un domaine de compétence.
     */
    public function update(Request $request, $id)
    {
        $domaine = DomaineCompetence::findOrFail($id);
        $domaine->update($request->all());

        // Mettre à jour les modules associés
        if ($request->has('modules')) {
            // Supprimer les modules existants
            $domaine->modules()->delete();

            // Créer les nouveaux modules
            foreach ($request->input('modules') as $moduleData) {
                $moduleData['domaine_competence_id'] = $domaine->id;
                Module::create($moduleData);
            }
        }

        return response()->json($domaine);
    }


    /**
     * Supprimer un domaine de compétence.
     */
    public function destroy($id)
    {
        // Rechercher le domaine de compétence en fonction de l'ID
        $domaine = DomaineCompetence::findOrFail($id);

        // Supprimer les modules associés
        $domaine->modules()->delete();

        // Supprimer le domaine de compétence
        $domaine->delete();

        return response()->json(null, 204);
    }


    public function DELETE($id)
    {
        // Rechercher la compétence en fonction de l'ID
        $competence = UserCompetence::find($id);

        if (!$competence) {
            return response()->json(['message' => 'Compétence non trouvée'], 404);
        }

        // Supprimer la compétence
        $competence->delete();
        $userCompetence = UserCompetence::select('*')->where('user_id', $competence->user_id)->get();
        return response()->json([$userCompetence], 200);
    }



}
