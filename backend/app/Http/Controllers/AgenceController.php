<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use Illuminate\Http\Request;


class AgenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $agences = Agence::all();
        return response()->json(['agences' => $agences], 200);
    }

    public function getUsersByAgence($agenceId)
    {
        // Récupérer l'agence par son ID
        $agence = Agence::findOrFail($agenceId);

        // Utiliser la méthode pour obtenir les utilisateurs de l'agence
        $users = $agence->users;

        return response()->json($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
        ]);

        $agence = Agence::create($validated);
        return response()->json(['agence' => $agence], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $agence = Agence::find($id);

        if (!$agence) {
            return response()->json(['error' => 'Agence not found'], 404);
        }

        return response()->json(['agence' => $agence], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $agence = Agence::find($id);

        if (!$agence) {
            return response()->json(['error' => 'Agence not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'pays' => 'sometimes|string|max:255',
        ]);

        $agence->update($validated);
        return response()->json(['agence' => $agence], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $agence = Agence::find($id);

        if (!$agence) {
            return response()->json(['error' => 'Agence not found'], 404);
        }

        $agence->delete();
        return response()->json(['message' => 'Delete successful'], 200);
    }
}
