<?php

namespace App\Http\Controllers;
use App\Exports\CajaExport;
use App\Models\Datoempr;
use App\Models\Vta001;
use App\Models\Fza002;
use App\Models\Cpps30;  // Ordenes
use App\Models\Cpps40;  // Facturas
use App\Models\Cpps41;  // Detalle
use App\Models\Cpps42;  // Detalle2
use App\Models\edi_Facturas;  // Facturas Web
use App\Models\edi_Ordenes;  // Ordenes Web
use App\Models\Fza020;  // Head de movimientos
Use Maatwebsite\Excel\Sheet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class LiquidacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add(Request $request, $id = null)
    {
        $facturaNew = new Cpps40;
        $facturaNew->fecha = Carbon::Now()->format('d/m/Y');
        $facturaNew->hasta = Carbon::Now()->format('d/m/Y');
        $facturaNew->fecha_sin = Carbon::Now()->format('d/m/Y');
        $facturaNew->dias = 1;
        $periodo = null;
        $fecha = null;
        $legajo = null;
        $novedad = null;
        $legajoReadOnly = '';
        $agregar = true;
        $edicion = true;
        $active = 45;
        $anterior = 0;
        $cuenta = 0;
        $id_caja = 0;
        $cerrada = null;
        $apertura = null;
        $iconSearch = true;
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $novedad = null;
        $order = null;
        $fecha_orig = null;
        $fecha5 = null;
        $facturas = null;
        
        $facturas = Cpps30::select('mat_prov_cole', DB::raw('SUM(importe) as facturacion'))
            ->where('periodo', '2022-09')
            ->orderBy('mat_prov_cole','asc')
            ->groupBy('mat_prov_cole')
            ->get();
        
            // ->orderBy('fecha','asc')
            // ->orderBy('numero','asc')
        
        $id_crud = 2;

        // Combos de tablas anexas              //$legajos   = Vta001::orderBy('codigo')->Where('codigo','>',0)->get();
        $legajos   = Vta001::select('vta001s.codigo', 'vta001s.detalle', 'nom_com')
            ->orderBy('detalle')
            ->Where('vta001s.codigo','>',0)
            ->get();

        // $legajos = DB::table('cpps30s')
        //     ->select(DB::raw('cpps30s.mat_prov_cole,cpps01s.nom_ape,sum(cantidad) as cantidad,sum(importe) as total,0.00 as msr,0.00 as egreso'))   //cpps01s.nom_ape,sum(cpps30s.cantidad) as 
        //     ->where('periodo', '2022-09')
        //     ->join('cpps01s', function ($join) {
        //         $join->on('cpps30s.mat_prov_cole', '=', 'cpps01s.mat_prov_cole');
        //     })
        //     ->groupBy('mat_prov_cole')
        //     ->get();    //->orderBy('cpps01s.nom_ape')

        // ->join('sue011s', function ($join) {
        //     $join->on('cpps01s.codsector', '=', 'sue011s.codigo');
        //     })
        // ->join('sue031s', function ($join) {
        //     $join->on('cpps30s.cod_nov', '=', 'sue031s.codigo');
        //     })
        
        
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

        //dd($facturas);

        return view('liquidacion.add')->with(compact('novedad','apertura','id_caja','fecha','legajo',
            'facturaNew','iconSearch', 'agregar','edicion','active','facturas','legajos', 'order','id_crud', 'legajoReadOnly','fecha5','id_caja','cerrada'));
    }

    public function store(Request $request, $id = null)
    {
        $facturaNew = new Cpps40;
        $facturaNew->fecha = Carbon::Now()->format('d/m/Y');
        $facturaNew->hasta = Carbon::Now()->format('d/m/Y');
        $facturaNew->fecha_sin = Carbon::Now()->format('d/m/Y');
        $facturaNew->dias = 1;
        $periodo = null;
        $fecha = null;
        $legajo = null;
        $novedad = null;
        $legajoReadOnly = '';
        $agregar = true;
        $edicion = true;
        $active = 45;
        $anterior = 0;
        $cuenta = 0;
        $id_caja = 0;
        $cerrada = null;
        $apertura = null;
        $iconSearch = true;
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $novedad = null;
        $order = null;
        $fecha_orig = null;
        $fecha5 = null;
        $facturas = null;
        
        $facturas = Cpps30::select('mat_prov_cole', DB::raw('SUM(importe) as facturacion'))
            ->where('periodo', '2022-09')
            ->orderBy('mat_prov_cole','asc')
            ->groupBy('mat_prov_cole')
            ->get();
        
        // Grabo detalle de la liquidacion en 1er tabla Cpps41
        foreach ($facturas as $factura) {
            $linea = new Cpps41();
            
            $linea->mat_prov_cole = $factura->mat_prov_cole;
            $linea->num_liq = 1000;
            $linea->fecha_liq = '2022-10-19';
            $linea->total = $factura->facturacion;
            $linea->msr = $factura->facturacion;
            $linea->egreso = $factura->facturacion * 0.04;
            $linea->ultima_num_liq = 798;
            $linea->fecha_ultima_liq = '2022-09-09';
            //$linea->fecha_alta = $request->input('fecha_alta');

            $linea->save();   // INSERT INTO - SQL

            // Grabo detalle de la liquidacion en 2da tabla Cpps42
            $renglon = 1;

            for ($i = 1; $i <= 2; $i++) {
                $linea_detallado = new Cpps42();
                
                $linea_detallado->num_liq = 1000;
                $linea_detallado->num_renglon = $i;
                $linea_detallado->mat_prov_cole = $factura->mat_prov_cole;

                if ($i == 1) {
                    $linea_detallado->operacion = 'O';
                    $linea_detallado->cod_operacion = 4991;
                    $linea_detallado->desc_operacion = 'Asociación Mutual SANCOR';
                    $linea_detallado->importe_op= $factura->facturacion;
                }

                if ($i == 2) {
                    $linea_detallado->operacion = 'R';
                    $linea_detallado->cod_operacion = 3;
                    $linea_detallado->desc_operacion = 'Gastos Administrativos (4%)';
                    $linea_detallado->importe_op= $factura->facturacion * 0.04;
                }
                
                $linea_detallado->save();   // INSERT INTO - SQL
            }
        }

        return redirect('/facturacion') ;
    }
}
