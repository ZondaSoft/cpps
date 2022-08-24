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
        Schema::table('cpps30s', function (Blueprint $table) {
            $table->unsignedBigInteger('id_fact')->after('importe');

            
            // FK
            //$table->foreignId('id_fact')->references('id')->on('cpps40s');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cpps30s', function (Blueprint $table) {
            //
        });
    }
};
