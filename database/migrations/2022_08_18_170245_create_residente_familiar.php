<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidenteFamiliar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residente_familiar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('residente_id')->nullable();
            $table->foreign('residente_id')->references('id')->on('residentes');
            $table->unsignedBigInteger('familiar_id')->nullable();
            $table->foreign('familiar_id')->references('id')->on('familiars');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('residente_familiar');
    }
}
