<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgenceIdToJoursFeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jours_feries', function (Blueprint $table) {
            $table->unsignedBigInteger('agence_id')->nullable();
            $table->foreign('agence_id')->references('id')->on('agences')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jours_feries', function (Blueprint $table) {
            $table->dropForeign(['agence_id']);
            $table->dropColumn('agence_id');
        });
    }
}

