<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Récupérer les modules avec leurs domaines de compétence associés
        $modules = Module::with('domaineCompetence')->get();

        // Transformer les modules pour inclure le nom du domaine de compétence
        $modulesWithDomainNames = $modules->map(function ($module) {
            return [
                'id' => $module->id,
                'nom' => $module->nom,
                'domaine_competence_id' => $module->domaine_competence_id,
                'domaine_competence_nom' => $module->domaineCompetence->nom,
                'created_at' => $module->created_at,
                'updated_at' => $module->updated_at,
            ];
        });

        // Retourner les modules sous forme de réponse JSON
        return response()->json(['modules' => $modulesWithDomainNames]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function edit(Module $module)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $module)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        //
    }
}
