<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('abbreviation')->nullable(); // Ajout du champ abbreviation
            $table->integer('color')->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
