<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vta001;
use \DB;

class Fza030 extends Model
{
    use HasFactory;


    // Filtros y busquedas
    public function scopeSearch($query, $codsector, $cod_nov, $fecha, $order)
    {
        // fix error: SQLSTATE[42000]: Syntax error or access violation: 1055 Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column
        //config()->set('database.connections.your_connection.strict', false);

        if ($codsector == null and $cod_nov == null and $fecha == null) {
            return $query->select(DB::raw('fza030s.fecha,fza030s.cuenta,fza030s.numero,fza030s.id,importe,fza030s.comentarios,fza030s.concepto'))
                            ->orderBy('id_caja')
                            ->join('cpa010s', function ($join) {
                                $join->on('cpa010s.codigo', '=', 'fza030s.concepto');
                            });
        }

        // Solo Cliente
        if ($codsector == null and $cod_nov == null and $fecha == null) {
            //$query->where('legajo', 'LIKE', "%{$dni}%");
            return $query->select('fza030s.fecha','fza030s.cuenta','fza030.numero','fza030s.id','fza030s.importe','fza030s.comentarios','fza030s.concepto');
        }
        // Legajo y fecha
        if ($codsector == null and $cod_nov == null and $fecha != null) {
            return $query->select('fza030s.fecha','fza030s.cuenta','fza030.numero','fza030s.id','fza030s.importe','fza030s.comentarios','fza030s.concepto')
                        ->where('fecha', 'LIKE', "%{$fecha}%");
        }
        // Solo sector
        elseif ($codsector != null and $cod_nov == null and $fecha == null) {
            return $query->select(DB::raw('fza030s.fecha,fza030s.cuenta,fza030s.id,fza030s.importe,cantidad,fza030s.comentarios,fza030s.concepto'))
                        ->orderBy('legajo');
            
            
        // Sector y fecha
        } elseif ($codsector != null and $cod_nov == null and $fecha != null) {
            return $query->select('fza030s.fecha','fza030s.cuenta','fza030.numero','fza030s.id','fza030s.importe','fza030s.comentarios','fza030s.concepto')
                        ->Where('fecha', 'LIKE', "%{$fecha}%");

        // Legajo y sector
        } elseif ($codsector != null and $cod_nov == null and $fecha == null) {
            return $query->select('fza030s.fecha','fza030s.cuenta','fza030.numero','fza030s.id','fza030s.importe','fza030s.comentarios','fza030s.concepto');

        // Legajo,  sector y fecha
        } elseif ($codsector != null and $cod_nov == null and $fecha != null) {
            return $query->select('fza030s.fecha','fza030s.cuenta','fza030.numero','fza030s.id','fza030s.importe','fza030s.comentarios','fza030s.concepto')
                        ->Where('fecha', 'LIKE', "%{$fecha}%")
                        ->Where('fza030s.importe', 'LIKE', "%{$cod_nov}%");
        // Sector y novedad
        } elseif ($codsector != null and $cod_nov != null and $fecha == null) {
            return $query->select('fza030s.fecha','fza030s.cuenta','fza030.numero','fza030s.id','fza030s.importe','fza030s.comentarios','fza030s.concepto')
                        ->Where('fza030s.importe', 'LIKE', "%{$cod_nov}%");

        // Sector y novedad y fecha
        } elseif ($codsector != null and $cod_nov != null and $fecha != null) {
            return $query->select('fza030s.fecha','fza030s.cuenta','fza030.numero','fza030s.id','fza030s.importe','fza030s.comentarios','fza030s.concepto')
                        ->Where('fza030s.importe', 'LIKE', "%{$cod_nov}%")
                        ->Where('fecha', 'LIKE', "%{$fecha}%");
                        
        // Legajo, Sector y novedad
        } elseif ($codsector != null and $cod_nov != null and $fecha == null) {
            return $query->select('fza030s.fecha','fza030s.cuenta','fza030.numero','fza030s.id','fza030s.importe','fza030s.concepto','estado','fza030s.comentarios','fza030s.concepto')
                        ->Where('fza030s.importe', 'LIKE', "%{$cod_nov}%");

        // Legajo, Sector y novedad Y fecha
        } elseif ($codsector != null and $cod_nov != null and $fecha != null) {
            return $query->select('fza030s.fecha','fza030s.cuenta','fza030.numero','fza030s.id','fza030s.importe','fza030s.concepto','estado','fza030s.comentarios','fza030s.concepto')
                        ->Where('fza030s.importe', 'LIKE', "%{$cod_nov}%")
                        ->Where('fecha', 'LIKE', "%{$fecha}%");

        // Legajo y novedad
        } elseif ($codsector == null and $cod_nov != null and $fecha == null) {
            return $query->select('fza030s.fecha','fza030s.cuenta','fza030.numero','fza030s.id','fza030s.importe','fza030s.concepto','estado','fza030s.comentarios','fza030s.concepto')
                        ->Where('fza030s.importe', 'LIKE', "%{$cod_nov}%");

        // Legajo, novedad y fecha
        } elseif ($codsector == null and $cod_nov != null and $fecha != null) {
            return $query->select('fza030s.fecha','fza030s.cuenta','fza030.numero','fza030s.id','fza030s.importe','fza030s.concepto','estado','fza030s.comentarios','fza030s.concepto')
                        ->Where('fza030s.importe', 'LIKE', "%{$cod_nov}%")
                        ->Where('fecha', 'LIKE', "%{$fecha}%");

        // Solo Novedad
        } elseif ($codsector == null and $cod_nov != null and $fecha == null) {
            return $query->select('fza030s.fecha','fza030s.cuenta','fza030.numero','fza030s.id','fza030s.importe','fza030s.concepto','estado','fza030s.comentarios','fza030s.concepto')
                        ->Where('cod_nov', 'LIKE', "%{$cod_nov}%");

        // Fecha
        } elseif ($codsector == null and $cod_nov == null and $fecha != null) {
            return $query->select('fza030s.fecha','fza030s.cuenta','fza030.numero','fza030s.id','fza030s.importe','fza030s.concepto','estado','fza030s.comentarios','fza030s.concepto')
                        ->Where('fecha', 'LIKE', "%{$fecha}%");

        // Novedad y fecha
        } elseif ($codsector == null and $cod_nov != null and $fecha != null) {
            return $query->select('fza030s.fecha','fza030s.cuenta','fza030.numero','fza030s.id','fza030s.importe','fza030s.concepto','estado','fza030s.comentarios','fza030s.concepto')
                        ->Where('cod_nov', 'LIKE', "%{$cod_nov}%")
                        ->Where('fecha', 'LIKE', "%{$fecha}%");
        }
    }


    // $novedades->ClientDetail
    public function buscoConcepto()
    {
    return $this->hasMany(Cpa010::class, 'codigo' , 'concepto');
    }
    
    // Accessors
    public function getnomConceptoAttribute()
    {
        if ($this->buscoConcepto) {
            if ($this->buscoConcepto->first() != null)
            return $this->buscoConcepto->first()->detalle;
        }

        return 'Cliente no encontrado';
    }
}
