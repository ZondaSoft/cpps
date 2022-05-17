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
        Schema::create('fza030s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_caja');
            $table->tinyInteger('cuenta');
            $table->date('fecha')->nullable();
            $table->string('concepto',6);
            $table->tinyInteger('tipo');
            $table->Integer('numero')->nullable();
            $table->decimal('importe',11,2)->default(0);
            $table->text('comentarios')->nullable();
            $table->timestamps();

            // FK
            $table->foreign('concepto')->references('codigo')->on('cpa010s');
            $table->foreign('id_caja')->references('id')->on('fza020s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fza030s');
    }
};
