<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpps12 extends Model
{
    use HasFactory;

    
    // Obra Social asociada
	public function obrasocial()
	{
		return $this->belongsTo(Cpps07::Class,'cod_os' , 'cod_os');
	}

    public function getNomObraAttribute()
    {
		if ($this->obrasocial != null) {
            return $this->obrasocial->desc_os;
        }

        return 'Sin obra social';
	}


    // Obra Social asociada
	public function convenio()
	{
		return $this->belongsTo(Cpps11::Class,'cod_conv' , 'cod_conv');
	}

    public function getNomConvenioAttribute()
    {
		if ($this->convenio != null) {
            return $this->convenio->desc_conv;
        }

        return 'Sin convenio';
	}

    public function getObservacionConvAttribute()
    {
		if ($this->convenio != null) {
            return $this->convenio->observacion_conv;
        }

        return '...';
	}
}
