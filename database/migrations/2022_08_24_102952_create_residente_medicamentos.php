<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidenteMedicamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residente_medicamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('residente_id')->nullable();
            $table->foreign('residente_id')->references('id')->on('residentes');
            $table->unsignedBigInteger('medicamento_id')->nullable();
            $table->foreign('medicamento_id')->references('id')->on('medicamentos');
            $table->string('toma')->nullable();
            $table->double('dosis')->nullable();
            $table->integer('stock')->nullable();
            $table->dateTime('alta')->nullable();
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
        Schema::dropIfExists('residente_medicamentos');
    }
}
