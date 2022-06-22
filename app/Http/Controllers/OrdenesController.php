<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datoempr;
use App\Models\Vta001;
use App\Models\Fza002;
use App\Models\Tal001;
use App\Models\Tal030;
use Carbon\Carbon;

class OrdenesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $id = null, $direction = null)
    {
        $legajoNew = new Tal030;
        $legajoNew->fecha = Carbon::Now()->format('d/m/Y');
        $legajoNew->hasta = Carbon::Now()->format('d/m/Y');
        $legajoNew->fecha_sin = Carbon::Now()->format('d/m/Y');
        $legajoNew->dias = 1;
        $periodo = null;
        
        $fecha = null;
        $legajoReadOnly = '';
        $dni = null;

        // si no se pasan ninguno de los dos parametros, limpio la sesion
        if ($request->has('dni') or $request->has('cod_nov2')) {
        } else {
            \Session::put('legajo_add',0);
            \Session::put('legajo_name_add','');
            \Session::put('cod_nov_new','');
            \Session::put('cod_nov_name_new','');
            \Session::put('fecha_add','');
        }

        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 26;
        $novedad = null;
        $dni = null;
        $codsector = null;
        $cod_nov = null;
        $fecha = null;
        $order = null;
        $fecha_orig = null;
        $fecha5 = null;
        $novedades = null;
        
        //$fecha_orig = Carbon::parse( Carbon::now() )->format('d/m/Y');

        //if ($nrolegajo != null) {
        //  $legajoNew->legajo = $id;
        // Busco el legajo seleccionado
        //  $legajoNew->detalle = $legajoNew->Apynom;
        //}
        //$legajo->fecha_naci = Carbon::parse($legajo->fecha_naci)->format('d/m/Y');
        //$legajo->alta = Carbon::parse($legajo->alta)->format('d/m/Y');

        //$novedades = Tal030::orderBy('fecha')->where('id',0)->paginate(9);
            $novedades = Tal030::search($dni, $codsector, $cod_nov, $fecha, $order)
                      ->orderBy('cliente','asc')
                      ->paginate(9)
                      ->appends(request()->query());
            
            //->orderBy('fecha','asc')

            $novedades->periodo = "  /    ";
        
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

        //dd(session()->pull('origen_novedad'));
        \Session::put('origen_novedad',2);
        
        return view('obras.ordenes')->with(compact('novedad','dni','cod_nov','fecha','legajo',
            'legajoNew','agregar','edicion','active','novedades','periodo','periodo2','legajos', 'order','id_crud', 'legajoReadOnly','fecha5'));
    }

    
    public function add()
    {
        $clientes = [];
        $vehiculos = [];
        
        $legajo = new Vta001;      // find($id);     // dd($legajo);

        $legajo->numero = 1;

        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $agregar = True;
        $active = 1;

        $legajo->foto = '/img/personal/none.png';
        $clientes = Vta001::orderBy('detalle')->get();
        $vehiculos = Tal001::orderBy('detalle')->get();
        
        /* if ($legajo != null) {
            $familiares = Sue002::orderBy('paren')->Where('legajo', '=', $legajo->codigo)->get();
        } else {
            $familiares = new Sue002;
        } */

        return view('orden-add')->with(compact(
            'legajo',
            'clientes',
            'agregar',
            'edicion',
            'active',
            'vehiculos'
        ));
    }


    function getClientes() {
        $clientes = Vta001::orderBy('detalle')
            ->where('id','<', 4)
            ->get();
        
        return response()->json($clientes);
    }


    function getVehiculos($idCliente = null) {
        $clientes = null;

        if ($idCliente != null) {
            $clientes = Tal001::orderBy('detalle')
                ->where('cliente', $idCliente)
                ->get();
        }
        
        return response()->json($clientes);
    }
}
