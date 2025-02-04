<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agence;

class AgenceSeeder extends Seeder
{
    public function run()
    {
        Agence::create([
            'name' => 'AFO',
            'pays' => 'Côte d\'Ivoire',
        ]);

        Agence::create([
            'name' => 'AFC',
            'pays' => 'Cameroun',
        ]);
        Agence::create([
            'name' => 'Tour',
            'pays' => 'France',
        ]);
    }
}
