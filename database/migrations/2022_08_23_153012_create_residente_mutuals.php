<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidenteMutuals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residente_mutuals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('residente_id')->nullable();
            $table->foreign('residente_id')->references('id')->on('residentes');
            $table->unsignedBigInteger('mutual_id')->nullable();
            $table->foreign('mutual_id')->references('id')->on('mutuals');
            $table->text('credencial')->nullable();
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
        Schema::dropIfExists('residente_mutuals');
    }
}
