<?php
// database/migrations/xxxx_xx_xx_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->integer('type')->default(0); // Ajouter une valeur par défaut
            $table->string('firstconnect')->default('NO'); // Ajouter une valeur par défaut
            $table->integer('conge')->default(60); // Ajouter une valeur par défaut
            $table->integer('recuperation')->default(60); // Ajouter une valeur par défaut
           
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
