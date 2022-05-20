<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fza020 extends Model
{
    use HasFactory;


    
    public function scopeName($query, $name)
    {
        // dd("scope :" . $name);

        if ($name != "")
        {
        $query->where(\DB::raw("CONCAT(id,' ', fecha )"), "LIKE" , "%$name%");

        //dd($query);

        }
    }
}
