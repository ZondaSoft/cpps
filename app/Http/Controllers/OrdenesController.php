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

        //-------------------------------------------------------------------------
        //    Si se paso la vble dni usada en el nro. de legajo en los modales
        //-------------------------------------------------------------------------
        if ($request->has('dni')) {
            $dni = $request->query('dni');

            $legajo = Vta001::where('codigo',$dni)->first();

			if ($legajo != null) {
                $legajoNew->detalle = $legajo->detalle . ' ' . $legajo->nom_com;
                //$legajoNew->sector = $legajo->codsector;
            }

            $legajoNew->legajo = $dni;

            // Traigo datos almacenados en la sesion (Nro.legajo)
            $novedad = (\Session::get('cod_nov_new'));

            if ($novedad != null) {
                $legajoNew->cod_nov = $novedad;
            }

            // Traigo datos almacenados en la sesion (Nro.legajo)
            $fecha = (\Session::get('fecha_add'));

            if ($fecha != null) {
                $legajoNew->fecha = $fecha;
            } else {
                // Si la fecha no fue traida por la sesion carho fecha de hoy
                $legajoNew->fecha = Carbon::Now()->format('d/m/Y');
            }

            // Traigo datos almacenados en la sesion (ApyNom)
            //$apynom_object = (\Session::get('legajo_name_add'));
            //$apynom = $apynom_object[0];

            //if ($apynom_object != null) {
            //    $legajoNew->detalle = $apynom;
            //}

            // Creo la session con el helper
            \Session::put('legajo_add',$legajoNew->legajo);
            \Session::put('legajo_name_add',$legajoNew->detalle);
        } else {
            $legajo = new Vta001;
			$legajo->id = 0;
			$legajo->detalle = '';
        }

        if ($request->has('cod_nov2')) {
            $cod_nov = $request->query('cod_nov2');

            // Traigo datos almacenados en la sesion (Nro.legajo)
            $dni = (\Session::get('legajo_add'));

            if ($dni != null) {
                $legajoNew->legajo = $dni;
            }

            // Traigo datos almacenados en la sesion (ApyNom)
            $apynom = (\Session::get('legajo_name_add'));

            if ($apynom != null) {
                $legajoNew->detalle = $apynom;
            }

            // Traigo datos almacenados en la sesion (Nro.legajo)
            $fecha = (\Session::get('fecha_add'));

            if ($fecha != null) {
                $legajoNew->fecha = $fecha;
            }

            // Creo la session con el helper
            \Session::put('cod_nov_new',$legajoNew->cod_nov);
            \Session::put('cod_nov_name_new',$legajoNew->CodNovName);
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
            if ($request->has('dni2') or $request->has('cod_nov2') or $request->has('fecha5') or $request->has('cliente')) {
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
                  $novedades = Tal030::search($dni, $codsector, $cod_nov, $fecha, $order)
                      ->orderBy('numero','asc')
                      ->paginate(9)
                      ->appends(request()->query());

                      //->orderBy('fecha','asc')
                      
                } elseif ($order == 1 or $order == 0) {
                  $novedades = Tal030::search($dni, $codsector, $cod_nov, $fecha, $order)
                      ->orderBy('cliente','desc')
                      ->paginate(9)
                      ->appends(request()->query());

                      //->orderBy('fecha','desc')

                } elseif ($order == 3) {
                  $novedades = Tal030::search($dni, $codsector, $cod_nov, $fecha, $order)
                      ->orderBy('cliente','asc')
                      ->paginate(9)
                      ->appends(request()->query());

                      //->orderBy('fecha','asc')
                      
                } elseif ($order == 4) {
                  $novedades = Tal030::search($dni, $codsector, $cod_nov, $fecha, $order)
                      ->orderBy('cliente','desc')
                      ->paginate(9)
                      ->appends(request()->query());

                      //->orderBy('fecha','desc')
                      
                } elseif ($order == 9) {
                  $novedades = Tal030::search($dni, $codsector, $cod_nov, $fecha, $order)
                      ->orderBy('cliente','asc')
                      ->paginate(9)
                      ->appends(request()->query());
                } elseif ($order == 10) {
                  $novedades = Tal030::search($dni, $codsector, $cod_nov, $fecha, $order)
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
                    
                    $novedades = Tal030::search($dni, $codsector, $cod_nov, $fecha, $order)
                        ->orderBy('ciente','asc')
                        ->paginate(9)
                        ->appends(request()->query());

                        //->orderBy('fecha','asc')
                //}
            }

        } else  {
            // fix error: SQLSTATE[42000]: Syntax error or access violation: 1055 Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column
            //config()->set('database.connections.your_connection.strict', false);
            
            $periodo2 = "";

            //$novedades = Tal030::orderBy('fecha')->where('id',0)->paginate(9);
            $novedades = Tal030::search($dni, $codsector, $cod_nov, $fecha, $order)
                      ->orderBy('cliente','asc')
                      ->paginate(9)
                      ->appends(request()->query());
            
            //->orderBy('fecha','asc')

            $novedades->periodo = "  /    ";
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

        //dd(session()->pull('origen_novedad'));
        \Session::put('origen_novedad',2);
        
        return view('ordenes')->with(compact('novedad','dni','cod_nov','fecha','legajo',
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
