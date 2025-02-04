<?php

namespace App\Http\Controllers;

use App\Models\JourFerie;
use App\Models\Agence;
use Illuminate\Http\Request;

class JourFerieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $joursFeries = JourFerie::with('agence')->get();
        return response()->json($joursFeries);  // Renvoie tous les jours fériés sous forme de JSON
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'date_ferie' => 'required|date',
            'nom_ferie' => 'required|string',
            'agence_id' => 'required|exists:agences,id',
        ]);
    
        $ferie = JourFerie::create($request->all());
    
        return response()->json($ferie, 201);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JourFerie  $jourFerie
     * @return \Illuminate\Http\Response
     */
    public function show(JourFerie $jourFerie)
    {
        return response()->json($jourFerie);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JourFerie  $jourFerie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date_ferie' => 'required|date',
            'nom_ferie' => 'required|string',
            'agence_id' => 'required|exists:agences,id',
        ]);
    
        $jourFerie = JourFerie::findOrFail($id);
        $jourFerie->update($request->all());
    
        return response()->json($jourFerie);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JourFerie  $jourFerie
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Recherche du jour férié par son ID
        $jourFerie = JourFerie::find($id);

        // Vérifie si le jour férié existe
        if (!$jourFerie) {
            return response()->json(['message' => 'Jour férié non trouvé'], 404);
        }

        // Suppression du jour férié
        $jourFerie->delete();

        // Retourne une réponse avec code 204 (No Content)
        return response()->noContent();
    }
}

