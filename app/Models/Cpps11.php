<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpps11 extends Model
{
    use HasFactory;

    protected $fillable = ['desc_conv'];

    protected $primaryKey = 'cod_conv';

}
