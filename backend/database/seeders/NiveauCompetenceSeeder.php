<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NiveauCompetence;

class NiveauCompetenceSeeder extends Seeder
{
    public function run()
    {
        NiveauCompetence::create(['niveau' => 'confirmé']);
        NiveauCompetence::create(['niveau' => 'débutant']);
        NiveauCompetence::create(['niveau' => 'expert']);
        NiveauCompetence::create(['niveau' => 'formé']);
        NiveauCompetence::create(['niveau' => 'aucun']);
    }
}
