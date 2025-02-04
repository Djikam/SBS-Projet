<?php
// create_user_competence_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCompetenceTable extends Migration
{
    public function up()
    {
        Schema::create('user_competence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('domaine_competence_id')->constrained('domaines_competences')->onDelete('cascade');
            $table->foreignId('niveau_competence_id')->constrained('niveaux_competences')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_competence');
    }
}
