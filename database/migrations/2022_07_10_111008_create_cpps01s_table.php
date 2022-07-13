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
        Schema::create('cpps01s', function (Blueprint $table) {
            $table->id();
            $table->Integer('cod_prof')->nullable();
            $table->Integer('mat_prov_cole')->nullable();
            $table->Integer('mat1')->nullable();
            $table->Integer('mat2')->nullable();
            $table->Integer('mat3')->nullable();
            $table->Integer('mat4')->nullable();
            $table->Integer('mat5')->nullable();
            $table->string('nom_ape',70)->nullable();
            $table->string('tipo_doc',70)->nullable();
            $table->string('num_doc',70)->nullable();
            $table->string('cond_iva',70)->nullable();
            $table->string('cuit',70)->nullable();
            $table->string('estado_civil',70)->nullable();
            $table->date('fcha_nac')->nullable();
            $table->date('fcha_mat1')->nullable();
            $table->date('fcha_mat2')->nullable();
            $table->date('fcha_mat_col')->nullable();
            $table->date('fcha_mat3')->nullable();
            $table->date('fcha_mat4')->nullable();
            $table->date('fcha_mat5')->nullable();
            $table->date('fcha_alta_cir')->nullable();
            $table->date('fcha_baja_cir')->nullable();
            $table->boolean('activo');
            $table->string('cat_soc',1)->default(0);
            $table->string('seg_mala_prax',1)->default(0);
            $table->string('tipo_atencion',1)->default(0);
            $table->string('especialidad',1)->default(0);
            $table->string('interior',1)->default(0);
            $table->string('forma_cobro',1)->default(0);
            $table->string('mail',60)->nullable();
            $table->string('cta_bancaria',70)->nullable();
            $table->string('cuota_col',1)->default(0);
            $table->string('com_dir',1)->default(0);
            $table->smallInteger('caja_ss')->nullable();
            $table->smallInteger('categ_ss')->default(0);
            $table->smallInteger('caja_reg_ss')->default(0);
            $table->smallInteger('cant_mes_reg_ss')->default(0);
            $table->smallInteger('cant_cap__reg_ss')->default(0);
            $table->smallInteger('listado')->default(1);
            $table->string('cta_lecop', 50)->nullable();
            $table->smallInteger('listadolecop')->nullable();
            $table->date('fcha_pasivo')->nullable();
            $table->date('fcha_egreso')->nullable();
            $table->date('fcha_especialidad')->nullable();
            $table->date('fcha_titulo')->nullable();
            $table->string('universidad', 50)->nullable();
            $table->smallInteger('cod_bco')->default(0);
            $table->smallInteger('cod_dom_web')->default(0);
            $table->string('sexo',1)->nullable();
            $table->string('area_laboral',50)->nullable();
            $table->string('ambito_laboral',50)->nullable();
            $table->boolean('cuota_col_deb_auto');
            $table->boolean('seg_mala_prax_deb_auto');
            $table->date('fecha_actualizacion')->nullable();
            $table->string('cbu',22)->nullable();
            $table->string('lugar_nacimiento',50)->nullable();
            $table->string('nacionalidad',50)->nullable();
            $table->date('licencia_desde')->nullable();
            $table->date('licencia_hasta')->nullable();
            $table->string('resolucion',50)->nullable();
            $table->string('pais',30)->nullable();
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
        Schema::dropIfExists('cpps01s');
    }
};
