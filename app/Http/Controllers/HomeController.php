<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datoempr;
use App\Models\Vta001;
use App\Models\Fza002;
use App\Models\Fza030;  // Movimientos
use App\Models\Fza020;  // Head de movimientos
use App\Models\Cpa010;  // Conceptos
use Carbon\Carbon;

class HomeController extends Controller
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
        
        $fecha = null;
        $legajo = null;
        $novedad = null;
        $legajoReadOnly = '';
        $agregar = true;
        $edicion = true;
        $active = 1;
        $cuenta = 0;
        $id_caja = 0;

        //------------------------------------------------------------------------
        // Controlo si hay caja abierta y voy a ella sino voy a apertura de caja
        //------------------------------------------------------------------------
        $cajaAbierta = Fza020::WhereNull('cerrada')->first();
        if ($cajaAbierta == null) {
            // Busco ultima caja cerrada
            $ultimaCaja = Fza020::first();

            // No hay cajas anteriores ?
            if ($ultimaCaja == null) {
                $legajo = new Fza020;
                $legajo->id = 1;
                $legajo->fecha = Carbon::Now()->format('d/m/Y');
                $legajo->apertura = 0.00;
                $legajo->cierre = 0.00;
                $id_caja = 1;
            }

            return view('caja-apertura')->with(compact('novedad','dni','fecha','cuenta',
                'legajoNew','legajo','agregar','edicion','active'));
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

        if ($periodo != null) {
            $periodo2 = substr($periodo->periodo,0,4) . substr($periodo->periodo,5,2);
            
            /*----------------------------------------------------*/
            /*                Carga de filtros
            /*----------------------------------------------------*/
            if ($request->has('dni2') or $request->has('cod_nov2') or $request->has('fecha5')) {
                $dni = $request->query('dni2');
				$cod_nov = $request->query('cod_nov2');
                $fecha_orig = $request->query('fecha5');
                $order = $request->query('order');

				if ($legajo == null) {
					$legajo = Vta001::where('codigo',$dni)->first();
				} else {
					if ($legajo->id == 0) {
						$legajo = Vta001::where('codigo',$dni)->first();

						if ($legajo == null) {
							$legajo = new Vta001;
							$legajo->id = 0;
							$legajo->detalle = '';
						}
					}
				}

                if ($fecha_orig != "") {
                    $date = str_replace('/', '-', $fecha_orig);
                    $fecha = Carbon::createFromFormat("d-m-Y", $date)->toDateString();
                }

                // fix error: SQLSTATE[42000]: Syntax error or access violation: 1055 Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column
                //config()->set('database.connections.your_connection.strict', false);

                if ($order == 2 or $order == null) {
                  $novedades = Fza030::Where('id_caja', $id_caja)   // Fza030::search($codsector, $cod_nov, $fecha, $order)
                      ->orderBy('numero','asc')
                      ->paginate(9)
                      ->appends(request()->query());

                      //->orderBy('fecha','asc')
                      
                } elseif ($order == 1 or $order == 0) {
                  $novedades = Fza030::Where('id_caja', $id_caja)
                      ->orderBy('fecha','desc')
                      ->paginate(9)
                      ->appends(request()->query());

                      //->orderBy('fecha','desc')

                } elseif ($order == 3) {
                  $novedades = Fza030::Where('id_caja', $id_caja)
                      ->orderBy('fecha','asc')
                      ->paginate(9)
                      ->appends(request()->query());

                      //->orderBy('fecha','asc')
                      
                } elseif ($order == 4) {
                  $novedades = Fza030::Where('id_caja', $id_caja)
                      ->orderBy('fecha','desc')
                      ->paginate(9)
                      ->appends(request()->query());

                      //->orderBy('fecha','desc')
                      
                } elseif ($order == 9) {
                  $novedades = Fza030::Where('id_caja', $id_caja)
                      ->orderBy('fecha','asc')
                      ->paginate(9)
                      ->appends(request()->query());
                } elseif ($order == 10) {
                  $novedades = Fza030::Where('id_caja', $id_caja)
                      ->paginate(9)
                      ->appends(request()->query());
                }

                $fecha = $fecha_orig;

                $novedades->periodo = $periodo->periodo;
                
            } else {
                //-----------------------------------------------------------
                //       CONTROL Y FILTRO SEGUN PERMISO DE USUARIO
                //-----------------------------------------------------------
                //if (auth()->user()->rol == "CARGA-TARJA-INFORMES") {
                //} else {
                    
                    // Sin filtros -> levanto todo el periodo - dax
                    // fix error: SQLSTATE[42000]: Syntax error or access violation: 1055 Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column
                    //config()->set('database.connections.mysql.strict', false);
                    
                    $novedades = Fza030::Where('id_caja', $id_caja)
                        ->orderBy('ciente','asc')
                        ->paginate(9)
                        ->appends(request()->query());

                        //->orderBy('fecha','asc')
                //}
            }

        } else  {
            // fix error: SQLSTATE[42000]: Syntax error or access violation: 1055 Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column
            //config()->set('database.connections.your_connection.strict', false);
            
            //$novedades = Cpa010::orderBy('fecha')->where('id',0)->paginate(9);
            $novedades = Fza030::Where('id_caja', $id_caja)
                      ->orderBy('fecha','asc')
                      ->paginate(9)
                      ->appends(request()->query());
            
            //->orderBy('fecha','asc')
        }

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

        $apertura = Fza020::whereNull('cerrada')->first();

        //dd($apertura);

        return view('ordenes')->with(compact('novedad','apertura','dni','cod_nov','fecha','legajo',
            'legajoNew','agregar','edicion','active','novedades','legajos', 'order','id_crud', 'legajoReadOnly','fecha5'));
    }

    
    function getClientes() {
        $clientes = Vta001::orderBy('detalle')
            ->where('id','<', 4)
            ->get();
        
        return response()->json($clientes);
    }


    // function getVehiculos($idCliente = null) {
    //     $clientes = null;

    //     if ($idCliente != null) {
    //         $clientes = Tal001::orderBy('detalle')
    //             ->where('cliente', $idCliente)
    //             ->get();
    //     }
        
    //     return response()->json($clientes);
    // }
}
