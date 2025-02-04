<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UserTableSeeder::class);
         $this->call(NiveauCompetenceSeeder::class);
        $this->call(JoursFeriesSeeder::class);
         $this->call(ClientTableSeeder::class);
         $this->call(ModuleSeeder::class);
         $this->call(AgenceSeeder::class);
        $this->call(DomaineCompetenceSeeder::class);
        $this->call(UserCompetenceSeeder::class);
    }
}
