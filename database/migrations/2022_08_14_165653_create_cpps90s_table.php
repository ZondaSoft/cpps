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
        Schema::create('cpps90s', function (Blueprint $table) {
            $table->id();
            $table->string('periodo',7)->nullable();
            $table->Integer('cod_os');
            $table->Integer('mat_prov_cole')->nullable();
            $table->string('ordennro', 20)->nullable();
            $table->integer('dni_afiliado')->nullable();
            $table->string('nom_afiliado', 50)->nullable();
            $table->date('fecha')->nullable();
            $table->unsignedBigInteger('plan');          // cod_conv en cpps11 as foreign key
            //$table->integer('id_nomen')->nullable();        // 1
            $table->string('cod_nemotecnico',10)->nullable();
            $table->string('cod_nomen',15)->nullable();     // 330101
            $table->smallInteger('cantidad')->nullable();
            $table->float('precio')->nullable();
            $table->double('importe')->nullable();
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
        Schema::dropIfExists('cpps90s');
    }
};
