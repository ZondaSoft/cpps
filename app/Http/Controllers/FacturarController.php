<?php

namespace App\Http\Controllers;
use App\Exports\CajaExport;
use App\Models\Datoempr;
use App\Models\Vta001;
use App\Models\Fza002;
use App\Models\Fza030;  // Movimientos
use App\Models\Fza020;  // Head de movimientos
use App\Models\Cpa010;  // Conceptos
Use Maatwebsite\Excel\Sheet;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FacturarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $id = null, $direction = null)
    {
        $legajoNew = new Cpa010;
        $legajoNew->fecha = Carbon::Now()->format('d/m/Y');
        $legajoNew->hasta = Carbon::Now()->format('d/m/Y');
        $legajoNew->fecha_sin = Carbon::Now()->format('d/m/Y');
        $legajoNew->dias = 1;
        $periodo = null;
        $codsector = null;
        $cod_nov = null;
        $cerrada = false;
        $fecha = null;
        $legajo = null;
        $novedad = null;
        $legajoReadOnly = '';
        $agregar = true;
        $edicion = true;
        $active = 40;
        $anterior = 0;
        $cuenta = 0;
        $id_caja = 0;
        $apertura = null;
        $iconSearch = true;

        if ($id != null) {
            $id_caja = $id;

            $apertura = Fza020::Where('id', $id)->first();
            
            if ($apertura != null) {
                $id_caja = $apertura->id;
                $fecha = $apertura->fecha;
                $cerrada = $apertura->cerrada;
            } else {
                $apertura = Fza020::orderBy('id','asc')->first();

                if ($apertura != null) {
                    $id_caja = $apertura->id;
                    $fecha = $apertura->fecha;
                    $cerrada = $apertura->cerrada;
                }
            }
        } else {
            //-----------------------------------------------------------------------------------
            // Controlo si hay caja abierta y voy a ella sino voy a la ultima caja encontrada
            //------------------------------------------------------------------------------------
            $apertura = Fza020::WhereNull('cerrada')->first();

            if ($apertura == null) {
                // Busco ultima caja
                $ultimaCaja = Fza020::orderBy('id','desc')->first();
                $apertura = $ultimaCaja;

                // No hay cajas anteriores ? ABRO 1ER CAJA
                if ($apertura == null) {
                    $legajo = new Fza020;
                    $apertura = new Fza020;
                    
                    $legajo->id = 1;
                    $id_caja = 1;
                    $legajo->fecha = Carbon::Now()->format('Y-m-d');;     //->format('d/m/Y');
                    $cerrada = null;
                    $legajo->apertura = 0.00;
                    $legajo->cierre = 0.00;

                    return view('caja-apertura')->with(compact('novedad','apertura','id_caja','fecha','cerrada','cuenta',
                        'legajoNew','legajo','iconSearch','agregar','edicion','active'));            
                } else {
                    $id_caja = $apertura->id;
                    $fecha = $apertura->fecha;
                    $cerrada = $apertura->cerrada;
                }
        
            } else {
                $id_caja = $apertura->id;
                $fecha = $apertura->fecha;
                $cerrada = $apertura->cerrada;
                
            }
        }

        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 40;
        $novedad = null;
        $order = null;
        $fecha_orig = null;
        $fecha5 = null;
        $novedades = null;
        
        $novedades = Fza030::Where('id_caja', $id_caja)
            ->orderBy('id','asc')
            ->paginate(20)
            ->appends(request()->query());
        
            // ->orderBy('fecha','asc')
            // ->orderBy('numero','asc')

        $id_crud = 2;

        // Combos de tablas anexas              //$legajos   = Vta001::orderBy('codigo')->Where('codigo','>',0)->get();
        $legajos   = Vta001::select('vta001s.codigo', 'vta001s.detalle', 'nom_com')
            ->orderBy('detalle')
            ->Where('vta001s.codigo','>',0)
            ->get();
        
        // Filtro de sectores segun perfil del usuario
        if (auth()->user()->rol == "ADMINISTRADOR" ) {
            //$sectores  = Sue011::orderBy('detalle')->whereNotNull('codigo')->get();
        } elseif (auth()->user()->rol == "CARGA-TARJA-INFORMES") {
            /* $sectores  = Sue011::orderBy('detalle')
                ->whereNotNull('codigo')
                ->join('roles_sectores', function ($join) {
                    $join->on('roles_sectores.codsector', '=', 'sue011s.codigo')
                        ->where('user', auth()->user()->name);
                    })
                ->get(); */
        } else {
            // "TARJAS-INFORMES"
            //$sectores  = Sue011::orderBy('detalle')->whereNotNull('codigo')->get();
        }

        //dd($novedades);

        return view('facturacion.index')->with(compact('novedad','apertura','id_caja','cod_nov','fecha','legajo',
            'legajoNew','iconSearch', 'agregar','edicion','active','novedades','legajos', 'order','id_crud', 'legajoReadOnly','fecha5','id_caja','cerrada'));
    }
}
