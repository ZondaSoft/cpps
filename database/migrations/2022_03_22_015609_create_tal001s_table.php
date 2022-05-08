<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tal001s', function (Blueprint $table) {
            $table->id();
            $table->string('cliente',6);
            $table->string('codigo',7)->unique();
            $table->string('detalle',50);
            $table->string('modelo',50)->nullable();
            $table->string('anio',4)->nullable();
            $table->string('motor',15)->nullable();
            $table->string('chasis',50)->nullable();
            $table->string('acop_det',30)->nullable();
            $table->string('acop_dom',30)->nullable();
            $table->timestamps();

            // FK
            $table->foreign('cliente')->references('codigo')->on('vta001s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tal001s');
    }
};
