<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpps09 extends Model
{
    use HasFactory;

    protected $fillable = ['cod_nemotecnico'];

    protected $primaryKey = 'id_nomen';
}
