<?php
// create_domaines_competences_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainesCompetencesTable extends Migration
{
    public function up()
    {
        Schema::create('domaines_competences', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('domaines_competences');
    }
}
