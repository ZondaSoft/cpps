<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpps07 extends Model
{
    use HasFactory;

    protected $fillable = ['desc_os'];

    // protected $primaryKey = 'cod_os';

    
    public function scopeName($query, $name)
    {
        // dd("scope :" . $name);

        if ($name != "")
        {
        $query->where(\DB::raw("CONCAT(cod_os,' ', desc_os )"), "LIKE" , "%$name%");

        //dd($query);

        }
    }
}
