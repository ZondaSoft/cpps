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
        Schema::table('cpps40s', function (Blueprint $table) {
            $table->integer('num_liq')->nullable()->after('id');

            // FK
            //$table->foreign('num_liq')->references('num_liq')->on('cpps41s');
            //$table->foreign('num_liq')->references('num_liq')->on('cpps42s');
            //$table->foreign('mat_prov_cole')->references('mat_prov_cole')->on('cpps01s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cpps40s', function (Blueprint $table) {
            //
        });
    }
};
