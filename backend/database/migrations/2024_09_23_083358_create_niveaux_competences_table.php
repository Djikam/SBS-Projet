<?php
// create_niveaux_competences_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNiveauxCompetencesTable extends Migration
{
    public function up()
    {
        Schema::create('niveaux_competences', function (Blueprint $table) {
            $table->id();
            $table->string('niveau');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('niveaux_competences');
    }
}
