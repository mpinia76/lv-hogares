<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHabitacionToResidente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('residentes', function (Blueprint $table) {
            $table->unsignedBigInteger('habitacion_id')->nullable();
            $table->foreign('habitacion_id')->references('id')->on('habitacions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('residentes', function (Blueprint $table) {
            //
        });
    }
}
