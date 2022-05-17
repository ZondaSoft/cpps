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
        Schema::create('fza020s', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->unique();
            $table->boolean('cerrada')->nullable();
            $table->decimal('apertura',11,2)->default(0);
            $table->decimal('cierre',11,2)->default(0);
            $table->text('comentarios')->nullable(); 
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
        Schema::dropIfExists('fza020s');
    }
};
