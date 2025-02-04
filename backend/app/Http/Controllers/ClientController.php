<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        // Récupère les clients et les trie par ordre croissant de nom
        $clients = Client::orderBy('nom', 'asc')->get(); // Assurez-vous que 'name' est le nom de la colonne dans votre table
        return response()->json($clients);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'abbreviation' => 'nullable|string',
            'color' => 'nullable|integer',
        ]);

        $client = Client::create($validated);
        return response()->json($client, 201);
    }

    public function show($id)
    {
        $client = Client::find($id);
        if (!$client) {
            return response()->json(['message' => 'client not found'], 404);
        }
        return response()->json($client);

    }
    public function showby($id)
    {
        $client = Client::with('interventions')->findOrFail($id);
        return response()->json($client);
    }
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'abbreviation' => 'nullable|string',
            'color' => 'nullable|integer',
        ]);

        $client->update($validated);
        return response()->json($client);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return response()->json(['message' => 'Client supprimé']);
    }
}
