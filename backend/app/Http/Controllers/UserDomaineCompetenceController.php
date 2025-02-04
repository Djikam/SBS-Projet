<?php


namespace App\Http\Controllers;

use App\Models\UserDomaineCompetence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserDomaineCompetenceController extends Controller
{
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'domaine_competence_id' => 'required|exists:domaines_competences,id',
            'niveau_competence_id' => 'required|exists:niveaux_competences,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Création de l'association domaine de compétence
        $userDomaineCompetence = UserDomaineCompetence::create([
            'user_id' => $request->user_id,
            'domaine_competence_id' => $request->domaine_competence_id,
            'niveau_competence_id' => $request->niveau_competence_id,
        ]);

        return response()->json(['user_domaine_competence' => $userDomaineCompetence], 201);
    }
}

