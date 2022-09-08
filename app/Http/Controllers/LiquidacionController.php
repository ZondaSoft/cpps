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
        
        $facturas = Cpps30::select('mat_prov_cole')
            ->orderBy('mat_prov_cole','asc')
            ->groupBy('mat_prov_cole')
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

        return view('liquidacion.add')->with(compact('novedad','apertura','id_caja','fecha','legajo',
            'facturaNew','iconSearch', 'agregar','edicion','active','facturas','legajos', 'order','id_crud', 'legajoReadOnly','fecha5','id_caja','cerrada'));
    }
}
