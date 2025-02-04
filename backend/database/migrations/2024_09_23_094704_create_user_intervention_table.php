<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInterventionTable extends Migration
{
    public function up()
    {
        Schema::create('user_intervention', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('intervention_id')->constrained('interventions')->onDelete('cascade');
            $table->timestamps(); // created_at and updated_at columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_intervention');
    }
}
