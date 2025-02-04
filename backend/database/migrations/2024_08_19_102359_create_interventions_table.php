<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterventionsTable extends Migration
{
    public function up()
    {
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->text('commentaire')->nullable();  // anciennement 'description'
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('cote_importance')->default(1);
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('interventions');
    }
}
