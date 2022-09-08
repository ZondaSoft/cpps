<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpps40 extends Model
{
    use HasFactory;

    // $legajos -> Centros de costo
	public function nombre_os()
	{
		return $this->belongsTo(Cpps07::Class,'cod_os', 'cod_os');
	}
    
    public function getNomObraAttribute()
    {
        if ($this->nombre_os != null) {
            return $this->nombre_os->desc_os;
        }

        return '0000';
	}

    public function getDireccionAttribute()
    {
        if ($this->nombre_os != null) {
            return $this->nombre_os->direccion_os;
        }

        return '.';
	}

    public function getTelefonoAttribute()
    {
        if ($this->nombre_os != null) {
            return $this->nombre_os->telefono1;
        }

        return '.';
	}

    public function getIvaAttribute()
    {
        if ($this->nombre_os != null) {
            return $this->nombre_os->telefono2;
        }

        return '.';
	}
    
    public function getCuitAttribute()
    {
        if ($this->nombre_os != null) {
            return $this->nombre_os->cuit;
        }

        return '.';
	}
}
