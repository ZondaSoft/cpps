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
        Schema::create('vta001s', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo',6)->unique();
            $table->string('detalle',50);
            $table->string('domic',50)->nullable();
            $table->string('localid',50)->nullable();
            $table->string('codpostal',15)->nullable();
            $table->string('nom_com',50)->nullable();
            $table->string('dom_com',50)->nullable();
            $table->string('contactos',50)->nullable();
            $table->string('tel1',30)->nullable();
            $table->string('tel2',30)->nullable();
            $table->string('tel3',30)->nullable();
            $table->string('email',45)->nullable();
            $table->string('web',45)->nullable();
            $table->string('coment',254)->nullable();
            $table->string('cuit',13)->nullable();
            $table->string('ib',20)->nullable();
            $table->string('grupo_emp',2)->nullable();
            $table->string('formap',1)->nullable();
            $table->string('banco',3)->nullable();
            $table->string('sucursal',20)->nullable();
            $table->string('cuenta',20)->nullable();
            $table->string('cbu',22)->nullable();
            $table->string('moneda',2)->nullable();
            $table->double('sant',12,2)->nullable();
            $table->double('srea',12,2)->nullable();
            $table->double('boni',12,2)->nullable();
            $table->date('baja')->nullable();
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
        Schema::dropIfExists('vta001s');
    }
};
