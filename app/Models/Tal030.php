<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vta001;
use \DB;

class Tal030 extends Model
{
    use HasFactory;


    // Filtros y busquedas
    public function scopeSearch($query, $dni, $codsector, $cod_nov, $fecha, $order)
    {
        // fix error: SQLSTATE[42000]: Syntax error or access violation: 1055 Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column
        //config()->set('database.connections.your_connection.strict', false);

        if ($dni == null and $codsector == null and $cod_nov == null and $fecha == null) {
            return $query->select(DB::raw('tal030s.fecha,tal030s.numero,tal030s.id,tal030s.cliente,importe,tal030s.detalle,tal030s.dominio,tal030s.estado'))
                            ->orderBy('cliente')
                            ->join('vta001s', function ($join) {
                                $join->on('vta001s.codigo', '=', 'tal030s.cliente');
                            });
        }

        // Solo Cliente
        if ($dni != null and $codsector == null and $cod_nov == null and $fecha == null) {
            //$query->where('legajo', 'LIKE', "%{$dni}%");
            return $query->select('tal030s.fecha','tal030.numero','tal030s.id','tal030s.cliente','tal030s.importe','tal030s.detalle','tal030s.dominio','tal030.estado')
                        ->where('legajo', 'LIKE', "%{$dni}%");
        }
        // Legajo y fecha
        if ($dni != null and $codsector == null and $cod_nov == null and $fecha != null) {
            return $query->select('tal030s.fecha','tal030.numero','tal030s.id','tal030s.cliente','tal030s.importe','tal030s.detalle','tal030s.dominio','tal030s.estado')
                        ->where('legajo', 'LIKE', "%{$dni}%")
                        ->where('fecha', 'LIKE', "%{$fecha}%");
        }
        // Solo sector
        elseif ($dni == null and $codsector != null and $cod_nov == null and $fecha == null) {
            return $query->select(DB::raw('tal030s.fecha,tal030s.id,tal030s.cliente,tal030s.importe,cantidad,tal030s.detalle,tal030s.dominio,tal030.estado'))
                        ->orderBy('legajo')
                        ->join('vta001s', function ($join) {
                                $join->on('vta001s.codigo', '=', 'tal030s.cliente');
                            });
            
            //return $query->select('tal030s.fecha','tal030.numero','tal030s.id','tal030s.cliente','tal030s.importe','tal030s.dominio','estado','tal030s.detalle','tal030s.dominio','estado')
            //->Where('codsector', 'LIKE', "%{$codsector}%")
            //->join('vta001s', function ($join) {
            //      $join->on('vta001s.codigo', '=', 'tal030s.cliente');
            // });
        // Sector y fecha
        } elseif ($dni == null and $codsector != null and $cod_nov == null and $fecha != null) {
            return $query->select('tal030s.fecha','tal030.numero','tal030s.id','tal030s.cliente','tal030s.importe','tal030s.detalle','tal030s.dominio','tal030s.estado')
                        ->Where('fecha', 'LIKE', "%{$fecha}%")
                        ->join('vta001s', function ($join) {
                                $join->on('vta001s.codigo', '=', 'tal030s.cliente');
                            });
        // Legajo y sector
        } elseif ($dni != null and $codsector != null and $cod_nov == null and $fecha == null) {
            return $query->select('tal030s.fecha','tal030.numero','tal030s.id','tal030s.cliente','tal030s.importe','tal030s.detalle','tal030s.dominio','tal030s.estado')
                        ->where('legajo', 'LIKE', "%{$dni}%")
                        ->join('vta001s', function ($join) {
                                $join->on('vta001s.codigo', '=', 'tal030s.cliente');
                            });
        // Legajo,  sector y fecha
        } elseif ($dni != null and $codsector != null and $cod_nov == null and $fecha != null) {
            return $query->select('tal030s.fecha','tal030.numero','tal030s.id','tal030s.cliente','tal030s.importe','tal030s.detalle','tal030s.dominio','tal030s.estado')
                        ->where('legajo', 'LIKE', "%{$dni}%")
                        ->Where('fecha', 'LIKE', "%{$fecha}%")
                        ->Where('tal030s.importe', 'LIKE', "%{$cod_nov}%")
                        ->join('vta001s', function ($join) {
                            $join->on('vta001s.codigo', '=', 'tal030s.cliente');
                        });
        // Sector y novedad
        } elseif ($dni == null and $codsector != null and $cod_nov != null and $fecha == null) {
            return $query->select('tal030s.fecha','tal030.numero','tal030s.id','tal030s.cliente','tal030s.importe','tal030s.detalle','tal030s.dominio','tal030s.estado')
                        ->Where('tal030s.importe', 'LIKE', "%{$cod_nov}%")
                        ->join('vta001s', function ($join) {
                                $join->on('vta001s.codigo', '=', 'tal030s.cliente');
                            });
        // Sector y novedad y fecha
        } elseif ($dni == null and $codsector != null and $cod_nov != null and $fecha != null) {
            return $query->select('tal030s.fecha','tal030.numero','tal030s.id','tal030s.cliente','tal030s.importe','tal030s.detalle','tal030s.dominio','tal030s.estado')
                        ->Where('tal030s.importe', 'LIKE', "%{$cod_nov}%")
                        ->Where('fecha', 'LIKE', "%{$fecha}%")
                        ->join('vta001s', function ($join) {
                                $join->on('vta001s.codigo', '=', 'tal030s.cliente');
                            });
        // Legajo, Sector y novedad
        } elseif ($dni != null and $codsector != null and $cod_nov != null and $fecha == null) {
            return $query->select('tal030s.fecha','tal030.numero','tal030s.id','tal030s.cliente','tal030s.importe','tal030s.dominio','estado','tal030s.detalle','tal030s.dominio','tal030s.estado')
                        ->where('tal030s.cliente', 'LIKE', "%{$dni}%")
                        ->Where('tal030s.importe', 'LIKE', "%{$cod_nov}%")
                        ->join('vta001s', function ($join) {
                                $join->on('vta001s.codigo', '=', 'tal030s.cliente');
                            });
        // Legajo, Sector y novedad Y fecha
        } elseif ($dni != null and $codsector != null and $cod_nov != null and $fecha != null) {
            return $query->select('tal030s.fecha','tal030.numero','tal030s.id','tal030s.cliente','tal030s.importe','tal030s.dominio','estado','tal030s.detalle','tal030s.dominio','tal030s.estado')
                        ->where('tal030s.cliente', 'LIKE', "%{$dni}%")
                        ->Where('tal030s.importe', 'LIKE', "%{$cod_nov}%")
                        ->Where('fecha', 'LIKE', "%{$fecha}%")
                        ->join('vta001s', function ($join) {
                                $join->on('vta001s.codigo', '=', 'tal030s.cliente');
                            });
        // Legajo y novedad
        } elseif ($dni != null and $codsector == null and $cod_nov != null and $fecha == null) {
            return $query->select('tal030s.fecha','tal030.numero','tal030s.id','tal030s.cliente','tal030s.importe','tal030s.dominio','estado','tal030s.detalle','tal030s.dominio','tal030s.estado')
                        ->where('tal030s.cliente', 'LIKE', "%{$dni}%")
                        ->Where('tal030s.importe', 'LIKE', "%{$cod_nov}%");
        // Legajo, novedad y fecha
        } elseif ($dni != null and $codsector == null and $cod_nov != null and $fecha != null) {
            return $query->select('tal030s.fecha','tal030.numero','tal030s.id','tal030s.cliente','tal030s.importe','tal030s.dominio','estado','tal030s.detalle','tal030s.dominio','tal030s.estado')
                        ->where('tal030s.cliente', 'LIKE', "%{$dni}%")
                        ->Where('tal030s.importe', 'LIKE', "%{$cod_nov}%")
                        ->Where('fecha', 'LIKE', "%{$fecha}%");
        // Solo Novedad
        } elseif ($dni == null and $codsector == null and $cod_nov != null and $fecha == null) {
            return $query->select('tal030s.fecha','tal030.numero','tal030s.id','tal030s.cliente','tal030s.importe','tal030s.dominio','estado','tal030s.detalle','tal030s.dominio','tal030s.estado')
                        ->Where('cod_nov', 'LIKE', "%{$cod_nov}%");

        // Fecha
        } elseif ($dni == null and $codsector == null and $cod_nov == null and $fecha != null) {
            return $query->select('tal030s.fecha','tal030.numero','tal030s.id','tal030s.cliente','tal030s.importe','tal030s.dominio','estado','tal030s.detalle','tal030s.dominio','tal030s.estado')
                        ->Where('fecha', 'LIKE', "%{$fecha}%");
        // Novedad y fecha
        } elseif ($dni == null and $codsector == null and $cod_nov != null and $fecha != null) {
            return $query->select('tal030s.fecha','tal030.numero','tal030s.id','tal030s.cliente','tal030s.importe','tal030s.dominio','estado','tal030s.detalle','tal030s.dominio','tal030s.estado')
                        ->Where('cod_nov', 'LIKE', "%{$cod_nov}%")
                        ->Where('fecha', 'LIKE', "%{$fecha}%");
        }
    }


    // $novedades->ClientDetail
    public function buscoClient()
    {
      return $this->hasMany(Vta001::class, 'codigo' , 'cliente');
    }
    
    // Accessors
    public function getClientDetailAttribute()
    {
        if ($this->buscoClient) {
            if ($this->buscoClient->first() != null)
              return $this->buscoClient->first()->detalle . " " . $this->buscoClient->first()->nombres;
        }

        return 'Cliente no encontrado';
    }
}
