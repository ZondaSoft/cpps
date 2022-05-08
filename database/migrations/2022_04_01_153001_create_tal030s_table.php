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
        Schema::create('tal030s', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cliente',6);
            $table->integer('numero')->unique();
            $table->float('importe')->nullable();
            $table->date('fecha')->nullable();
            $table->string('hora',5)->nullable();
            $table->string('confecciono',4)->nullable();
            $table->string('detalle',254)->nullable();
            $table->boolean('anula')->nullable();
            $table->boolean('isprint')->nullable();
            $table->string('estado',20)->nullable();
            $table->date('fecha_conf')->nullable();
            $table->date('fecha_entr')->nullable();
            $table->date('fecha_coti')->nullable();
            $table->date('fecha_repa')->nullable();
            $table->date('vence_gtia')->nullable();
            $table->boolean('es_service')->nullable();
            $table->float('tiempo_hs')->nullable();
            $table->float('costo_mat')->nullable();
            $table->float('repar_cost')->nullable();
            $table->float('import_fac')->nullable();
            $table->float('kms_actual')->nullable();
            $table->float('kms_anter')->nullable();
            $table->date('ultim_serv')->nullable();
            $table->string('falla',500)->nullable();
            $table->string('comenta',500)->nullable();
            $table->string('dominio',6);                    // fk -> tal001
            $table->string('modelo',50)->nullable();
            $table->string('anio',4)->nullable();
            $table->timestamps();

            
            // FK
            $table->foreign('cliente')->references('codigo')->on('vta001s');
            $table->foreign('dominio')->references('codigo')->on('tal001s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tal030s');
    }
};
