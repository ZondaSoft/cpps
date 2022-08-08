<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpps14 extends Model
{
    use HasFactory;

    
    // Cinvenio  asociada
	public function convenio()
	{
		return $this->belongsTo(Cpps11::Class,'cod_convenio' , 'cod_conv');
	}

    public function getNomConvenioAttribute()
    {
		if ($this->convenio != null) {
            return $this->convenio->desc_conv;
        }

        return 'Sin convenio';
	}

    
    
    // Nomenclador asociado
	public function nomenclador()
	{
		return $this->belongsTo(Cpps09::Class,'cod_nomenclador' , 'cod_nomen');
	}

    public function getNomNomencladorAttribute()
    {
		if ($this->nomenclador != null) {
            return $this->nomenclador->nom_prest;
        }

        return 'Sin nomenclador';
	}

    // public function nomenclador2()
	// {
	// 	return $this->belongsTo(Cpps09::Class,'cod_nomenclador' , 'cod_nomen');
	// }
    
    // public function getCodNomencladorAttribute()
    // {
    //     if ($this->nomenclador2 != null) {
    //         return $this->nomenclador2->cod_nemotecnico;
    //     }

    //     return 'Sin cód. nemotecnico';
    // }
}
