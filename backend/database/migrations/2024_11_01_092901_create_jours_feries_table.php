<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJoursFeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jours_feries', function (Blueprint $table) {
            $table->id();
            $table->date('date_ferie'); // La date du jour férié, unique pour éviter les doublons
            $table->string('nom_ferie');          // Un nom descriptif du jour férié (ex: "Nouvel An")
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jours_feries');
    }
}
