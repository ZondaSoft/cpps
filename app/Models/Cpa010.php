<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpa010 extends Model
{
    use HasFactory;
    
    public function scopeName($query, $name)
    {
        // dd("scope :" . $name);

        if ($name != "")
        {
        $query->where(\DB::raw("CONCAT(codigo,' ', detalle )"), "LIKE" , "%$name%");

        //dd($query);

        }
    }

}