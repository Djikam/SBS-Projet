<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DomaineCompetence;

class DomaineCompetenceSeeder extends Seeder
{
    public function run()
    {
        DomaineCompetence::create(["nom" => "ENGAGEMENTS"]);
        DomaineCompetence::create(["nom" => "MDP"]);
        DomaineCompetence::create(["nom" => "MONETIQUE"]);
        DomaineCompetence::create(["nom" => "REGLEMENTAIRE"]);
        DomaineCompetence::create(["nom" => "COMPTA & BO"]);
        DomaineCompetence::create(["nom" => "ETRANGER"]);
        DomaineCompetence::create(["nom" => "DIGITAL"]);
        DomaineCompetence::create(["nom" => "TECHNIQUE"]);
    }
}
