<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpps11 extends Model
{
    use HasFactory;

    protected $fillable = ['desc_conv'];

    protected $primaryKey = 'cod_conv';



    // Scope usado en las busquedas
	public function scopeName($query, $name)
	{
		// dd("scope :" . $name);

		if ($name != "")
		{
			$query->where(\DB::raw("CONCAT(cod_conv,' ', desc_conv)"), "LIKE" , "%$name%");

			//dd($query);
		}
	}
}
