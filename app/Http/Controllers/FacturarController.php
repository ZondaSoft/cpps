<?php

namespace App\Http\Controllers;
use App\Exports\CajaExport;
use App\Models\Datoempr;
use App\Models\Vta001;
use App\Models\Fza002;
use App\Models\Cpps30;  // Ordenes
use App\Models\Cpps40;  // Facturas
use App\Models\edi_Facturas;  // Facturas Web
use App\Models\edi_Ordenes;  // Ordenes Web
use App\Models\Fza020;  // Head de movimientos
Use Maatwebsite\Excel\Sheet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;


class FacturarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $id = null, $direction = null)
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
        $active = 40;
        $anterior = 0;
        $cuenta = 0;
        $id_caja = 0;
        $cerrada = null;
        $apertura = null;
        $iconSearch = true;
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 40;
        $novedad = null;
        $order = null;
        $fecha_orig = null;
        $fecha5 = null;
        $facturas = null;
        
        $facturas = Cpps40::orderBy('id','asc')
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

        //dd($facturas);

        return view('facturacion.index')->with(compact('novedad','apertura','id_caja','fecha','legajo',
            'facturaNew','iconSearch', 'agregar','edicion','active','facturas','legajos', 'order','id_crud', 'legajoReadOnly','fecha5','id_caja','cerrada'));
    }

    public function edit(Request $request, $id = null)
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
        $active = 40;
        $anterior = 0;
        $cuenta = 0;
        $id_caja = 0;
        $cerrada = null;
        $apertura = null;
        $iconSearch = true;
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 40;
        $novedad = null;
        $order = null;
        $fecha_orig = null;
        $fecha5 = null;
        $facturas = null;
        
        $factura = Cpps40::find($id);
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

        //dd($facturas);

        return view('facturacion.edit')->with(compact('novedad','apertura','id_caja','fecha','legajo',
            'facturaNew','iconSearch', 'agregar','edicion','active','factura','legajos', 'order','id_crud', 'legajoReadOnly','fecha5','id_caja','cerrada'));
    }

    function webupload(Request $request, $id)
    {
        $factura = Cpps40::find($id);

        if ($factura != null) {
            
            // 1-Si existe ya la factura en la tabla para exportar (edi_Facturas) la borro
            $deleteFactura = edi_Facturas::where('id_fact', $id)->first();

            if ($deleteFactura != null) {
                $deleteFactura::where('id_fact', $id)->delete();
            }

            // 2-Agrego datos de la factura (Cpps30) en la tabla para exportar (edi_Facturas)
            $nuevaFactura = new edi_Facturas();
            $nuevaFactura->id_fact = $id;
            $nuevaFactura->periodo_fac = $factura->fecha;
            $nuevaFactura->cod_os = $factura->cod_os;
            $nuevaFactura->concepto = $factura->concepto;
            
            $nuevaFactura->save();

            // 3-Borro ordenes pre-existentes
            $deleteOrders = edi_Ordenes::where('id_fact', $id)
                ->delete();

            // 4-Agrego ordenes a edi_Facturas
            $nroRenglon = 1;
            
            DB::statement(DB::raw("SET @row = '0'"));

            $ordenes = Cpps30::select('id_fact',
                DB::raw("@row:=@row+1 AS nro_renglon"),
                'ordennro',
                'profesionales.cod_prof',
                DB::raw('cod_nemotecnico as id_nomen'),'nom_afiliado','importe','cantidad',DB::raw('0 as estado_orden'),'dni_afiliado')
                ->join('cpps01s AS profesionales', 'profesionales.mat_prov_cole', '=', 'cpps30s.mat_prov_cole')
                ->where('id_fact', $id)
                ->where('periodo', $factura->periodo)
                ->where('cod_os', $factura->cod_os)
                ->get()->toArray();
            
            //dd($ordenes->count());
            
            if ($ordenes != null) {
                edi_Ordenes::insert($ordenes);

                // foreach ($ordenes as $order) {
                //     $newOrder = new edi_Ordenes();

                //     $newOrder->id_fact = $id;
                //     $newOrder->nro_renglon = $nroRenglon;
                //     $newOrder->ordennro = $order->ordennro;
                    
                //     $newOrder->cod_prof = $order->CodProf;
                    
                //     $newOrder->id_nomen = $order->cod_nemotecnico;
                //     $newOrder->nom_afiliado = $order->nom_afiliado;
                //     $newOrder->importe = $order->importe;
                //     $newOrder->cantidad = $order->cantidad;
                //     $newOrder->estado_orden = 0;
                //     $newOrder->dni_afiliado = $order->dni_afiliado;
                //     $newOrder->save();

                //     $nroRenglon++;
                // }

                return "{\"result\":\"ok\",\"id\":\"$id,\"ordenes\":\"$nroRenglon}";
            }
            
            return "{\"result\":\"error\",\"id\":\"$id}";
        }

        return "{\"result\":\"error\",\"id\":\"$id}";
    }
}
