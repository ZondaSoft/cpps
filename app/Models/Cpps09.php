<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpps09 extends Model
{
    use HasFactory;

    protected $fillable = ['cod_nemotecnico'];

    protected $primaryKey = 'id_nomen';


    // Scope usado en las busquedas
	public function scopeName($query, $name)
	{
		// dd("scope :" . $name);

		if ($name != "")
		{
			$query->where(\DB::raw("CONCAT(cod_nomen,' ', cod_nemotecnico,' ', nom_prest)"), "LIKE" , "%$name%");

			//dd($query);
		}
	}
}
