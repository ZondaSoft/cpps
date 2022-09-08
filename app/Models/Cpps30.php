<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cpps01;   // Professionals

class Cpps30 extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // $legajos -> Centros de costo
	public function nom_profesional()
	{
		return $this->belongsTo(Cpps01::Class,'mat_prov_cole', 'mat_prov_cole');
	}
    
    public function getNomProfAttribute()
    {
        if ($this->nom_profesional != null) {
            return $this->nom_profesional->nom_ape;
        }

        return '0000';
	}

    
    public function CodProf3()
    {
        return $this->belongsTo(Cpps01::class);
	}
}
