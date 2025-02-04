<?php
// database/migrations/xxxx_xx_xx_create_login_audits_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginAuditsTable extends Migration
{
    public function up()
    {
        Schema::create('login_audits', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('ip_address')->nullable();
            $table->boolean('success')->default(false);
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_audits');
    }
}
