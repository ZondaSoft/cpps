<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpps01 extends Model
{
    use HasFactory;

    // Scope usado en las busquedas
	public function scopeName($query, $name)
	{
		// dd("scope :" . $name);

		if ($name != "")
		{
			$query->where(\DB::raw("CONCAT(mat_prov_cole,' ', nom_ape,' ', cuit)"), "LIKE" , "%$name%");

			//dd($query);
		}
	}
}
