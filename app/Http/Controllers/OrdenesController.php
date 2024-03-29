<?php

namespace App\Http\Controllers;

use App\Exports\OrdenesExport;
use Illuminate\Http\Request;
use App\Models\Datoempr;
use App\Models\Fza030;  // Movimientos
use App\Models\Fza020;  // Head de movimientos
use App\Models\Cpps01;  // Profesionales
use App\Models\Cpps07;  // Obras sociales
use App\Models\Cpps09;  // Nomencladores
use App\Models\Cpps12;  // Convenios-Planes
use App\Models\Cpps30;  // Ordenes
use App\Models\Cpps40;  // Facturas
use App\Models\Cpps90;  // Ordenes de baja y/o editadas
use App\Models\Vta001;
use Carbon\Carbon;
//use Maatwebsite\Excel\Facades\Excel;
use Excel;
use App;

class OrdenesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id = null, $direction = null)
    {
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 1;
        $fecha = null;
        $id_caja = 0;
        $nrolegajo = 0;
        $cerrada = false;
        $iconSearch = true;

        if ($id == null) {
            $legajo = Cpps30::Where('mat_prov_cole', '>', 0)
                ->orderBy('mat_prov_cole')
                ->first();      // find($id);

            if ($legajo != null) {
                $id = $legajo->id;
                $nrolegajo = $legajo->codigo;
            }
        } else {
            $legajo = Cpps30::find($id);
            if ($legajo == null) {
                $legajo = Cpps30::Where('mat_prov_cole', '>', 0)
                    ->orderBy('mat_prov_cole')
                    ->first();
            }

            if ($legajo != null) {
                $nrolegajo = $legajo->codigo;
            }
        }

        // Datos de la empresa
        $empresa = Datoempr::first();      // find($id);
        //if ($empresa == null) {
        //    return redirect('/empresa/');
        //}

        // Si a pesar de todos los controles $legajo es null es porque no hay registros
        if ($legajo == null)
            $legajo = new Cpps30;

        // Si la var. $direction muestra que el cursor s    e mueve (-1)
        if ($direction == -1) {
            $legajo = Cpps30::where('codigo', '<', $nrolegajo)
                ->Where('mat_prov_cole', '>', 0)
                ->orderBy('codigo', 'desc')
                ->first();

            if ($legajo == null)
                $legajo = Cpps30::Where('mat_prov_cole', '>', 0)
                    ->orderBy('mat_prov_cole')
                    ->first();
        }

        // Si la var. $direction muestra que el cursor se mueve (+1)
        if ($direction == 1) {
            $legajo = Cpps30::where('mat_prov_cole', '>', $nrolegajo)
                ->Where('mat_prov_cole', '>', 0)
                ->orderBy('mat_prov_cole')
                ->first();

            if ($legajo == null)
                $legajo = Cpps30::latest('id')
                    ->where('mat_prov_cole', '>', 0)
                    ->first();
        }


        // Si la var. $direction muestra que el cursor se mueve al final
        if ($direction == 9) {
            $legajo = Cpps30::latest('codigo')
                ->where('mat_prov_cole', '>', 0)
                ->first();
        }

        //$legajo->cod_os = "";
        $legajo->ordenes2 = 0;
        $legajo->plan == "";
        $legajo->mat_prov_cole = 0;
        $legajo->precio = 0.00;
        $legajo->total = 0.00;
        
        $ordenes = new Cpps30;
        
        $profesionales = Cpps01::orderBy('nom_ape')->get();
        $obras = Cpps07::orderBy('cod_os')->get();
        $conv_os = Cpps12::where('cod_os', $legajo->cod_os)->get();
        $nomencladores = Cpps09::orderBy('cod_nomen')->get();
        $prestaciones = Cpps09::orderBy('nom_prest')->get();

        return view('ordenes.index')->with(compact(
            'empresa',
            'legajo',
            'agregar',
            'edicion',
            'iconSearch',
            'active',
            'fecha',
            'profesionales',
            'obras',
            'conv_os',
            'cerrada',
            'nomencladores',
            'prestaciones',
            'ordenes'
        ));
    }


    public function laodorders($obra = null, $matricula = null, $periodo = null)
    {
        if ($obra === null) {
            return null;
        }

        if ($matricula === null) {
            return null;
        }

        if ($periodo === null) {
            return null;
        }

        $sumOrders = Cpps30::where('cod_os', $obra)
            ->Where('periodo', $periodo)
            ->Where('mat_prov_cole', $matricula)
            ->orderBy('ordennro', 'desc')
            ->get()->sum('importe');
        
        $countOrders = Cpps30::where('cod_os', $obra)
            ->Where('periodo', $periodo)
            ->Where('mat_prov_cole', $matricula)
            ->orderBy('ordennro', 'desc')
            ->get()->count('cantidad');
        
        $orders = Cpps30::where('cod_os', $obra)
                ->Where('periodo', $periodo)
                ->Where('mat_prov_cole', $matricula)
                ->orderBy('ordennro', 'asc')
                ->get();

        $orders->put('cuenta', $countOrders);
        $orders->put('suma', $sumOrders);

        return $orders;
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $fechaActual = null;
        $fecha = null;
        $id_caja = 0;
        $cerrada = false;
        $iconSearch = false;

        $legajo = new Cpps01;      // find($id);     // dd($legajo);
        $legajo->periodo = '2022-07';
        $legajo->fecha = Carbon::Now()->format('Y-m-d');
        $legajo->cod_os = '1050';  // 1050

        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $agregar = True;
        $active = 1;
        
        $profesionales = Cpps01::orderBy('nom_ape')->get();
        $obras = Cpps07::orderBy('desc_os')->get();
        $nomencladores = Cpps09::orderBy('cod_nomen')->get();
        $prestaciones = Cpps09::orderBy('nom_prest')->get();

        return view('ordenes.index')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'active',
            'iconSearch',
            'fecha',
            'id_caja',
            'profesionales',
            'obras',
            'nomencladores',
            'prestaciones'
        ));
    }


    public function store(Request $request)
    {
        // Validaciones
        $messages = [
            'cod_prof.required' => 'El Nro. de matricula es obligatoria',
            'profesional.required' => 'El nombre y apellido es obligatorio',
            'det_os.required' => 'La Obra social es obligatoria'
        ];

        $rules = [
            'cod_prof' => 'required',
            'profesional' => 'required',
            'det_os' => 'required'
        ];

        $this->validate($request, $rules, $messages);

        $legajo = new Cpps30();
        //$request->all();
        //$legajo = Cpps01::create($request->all()); // massives assignments : all() -> onLy() // only('name','description')

        $legajo->periodo = $request->input('periodo');
        $legajo->cod_os = $request->input('cod_os');
        $legajo->ordenes = $request->input('ordenes'); 
        $legajo->importe = $request->input('importe'); 
        $legajo->caja_reg_ss = $request->input('caja_reg_ss'); 
        $legajo->mat_prov_cole = $request->input('mat_prov_cole'); 
        $legajo->orden_nro = $request->input('orden_nro');
        $legajo->nom_afiliado = $request->input('nom_afiliado');
        $legajo->fecha = $request->input('fecha');
        $legajo->id_nomen = $request->input('id_nomen');
        $legajo->nomenclador = $request->input('nomenclador');
        $legajo->prestacion = $request->input('prestacion');
        $legajo->cantidad = $request->input('cantidad');
        $legajo->precio = $request->input('precio');
        $legajo->total = $request->input('total');

        $legajo->activo = true;

        $legajo->save();   // INSERT INTO - SQL
        
        if ($legajo->mat_prov_cole > 0)
            return redirect('/carga-ordenes/' . $legajo->id)->with('success', 'La orden ya fue registrada con éxito');

        return redirect('/carga-ordenes/');
    }

    public function saveorder(Request $request) {
        
        $order = new Cpps30();

        $order->periodo = $request->input('periodo');
        $order->cod_os = $request->input('cod_os');
        $order->mat_prov_cole = $request->input('mat_prov_cole');
        $order->ordennro = $request->input('ordennro');
        $order->dni_afiliado = $request->input('dni_afiliado');
        $order->nom_afiliado = $request->input('nom_afiliado');
        $order->fecha = $request->input('fecha');
        $order->plan = $request->input('plan');
        $order->cod_nemotecnico = $request->input('cod_nemotecnico');
        $order->cod_nomen = $request->input('cod_nomen');
        $order->cantidad = $request->input('cantidad');
        $order->precio = $request->input('precio');
        $order->importe = $request->input('importe');

        $order->save();

        return "{\"result\":\"ok\",\"id\":\"$order->ordennro\",\"ordennro\":\"$order->nom_afiliado\",\"nom_afiliado\":\"$order->nom_afiliado\",\"}";
    }


    public function edit($id = 0)
    {
        $id_caja = 0;
        $fecha = null;
        $iconSearch = false;

        if ($id == 0) {
            return redirect('/profesionales');
        }
        
        $legajo = Cpps01::find($id);
        if ($legajo == null) {
            return redirect('/profesionales');
        }

        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 1;
        $cerrada = false;

        // $legajo->fecha_naci = Carbon::parse($legajo->fecha_naci)->format('d/m/Y');
        // $legajo->alta = Carbon::parse($legajo->alta)->format('d/m/Y');
        // $legajo->fecha_vto = Carbon::parse($legajo->fecha_vto)->format('d/m/Y');
        // $legajo->ultima_act = Carbon::parse($legajo->ultima_act)->format('d/m/Y');

        //$bancos = Fza002::orderBy('detalle')->get();

        /* if ($legajo != null) {
            $familiares = Sue002::orderBy('paren')->Where('legajo', '=', $legajo->codigo)->get();
        } else {
            $familiares = new Sue002;
        } */

        return view('ordenes.index')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'iconSearch',
            'active',
            'cerrada',
            'id_caja',
            'fecha'
            
        ));    // Abrir form de modificacion
    }


    public function update(Request $request, $id)
    {
        // Validaciones
        $messages = [
            'nom_ape.required' => 'El nombre y apellido es obligatorio',
            'nom_ape.min' => 'El nombre y apellido debe tener más de 2 letras'
        ];

        $rules = [
            'nom_ape' => 'required|min:2'
        ];

        // Validacion de campos
        $this->validate($request, $rules, $messages);

        // Grabar en bbdd los cambios del form de alta
        // dd($request->all());
        $legajo = Cpps01::find($id);

        $legajo->nom_ape = $request->input('nom_ape');
        

        $legajo->update($request->only('detalle', 'cuenta'));

        // dd($legajo->cod_centro);

        return redirect('/profesionales/' . $id);
    }

    
    public function updateorder(Request $request, $id = null) {
        
        //$id = $request->input('id');
        $order = Cpps30::find($id);

        if ($order == null) {
            return "{\"result\":\"no id :\"$id}";
        }

        $order->periodo = $request->input('periodo');
        $order->cod_os = $request->input('cod_os');
        $order->mat_prov_cole = $request->input('mat_prov_cole');
        $order->ordennro = $request->input('ordennro');
        $order->dni_afiliado = $request->input('dni_afiliado');
        $order->nom_afiliado = $request->input('nom_afiliado');
        $order->fecha = $request->input('fecha');
        $order->plan = $request->input('plan');
        $order->cod_nemotecnico = $request->input('cod_nemotecnico');
        $order->cod_nomen = $request->input('cod_nomen');
        $order->cantidad = $request->input('cantidad');
        $order->precio = $request->input('precio');
        $order->importe = $request->input('importe');

        $order->update($request->only('ordennro', 'dni_afiliado', 'nom_afiliado', 'fecha', 'plan', 'cod_nemotecnico', 'cod_nomen', 'cantidad', 'precio', 'importe'));

        return "{\"result\":\"ok\",\"id\":\"$order->ordennro\",\"ordennro\":\"$order->nom_afiliado\",\"nom_afiliado\":\"$order->nom_afiliado\",\"}";
    }
    
    
    
    public function delete($id)
    {
        $legajo = Cpps01::find($id);
        if ($legajo == null) {
            return "{\"result\":\"cancel\",\"id\":\"$legajo->id\"}";
        }

        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 1;
        $cerrada = false;

        return "{\"result\":\"ok\",\"id\":\"$legajo->id\",\"codigo\":\"$legajo->codigo\",\"detalle\":\"$legajo->detalle\",\"}";
        //return redirect("/profesionales/");
    }

    public function baja(Request $request, $id = null)
    {
        // return "Mostrar form de edit $id";
        $legajo = Cpps01::find($id);
        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 1;

        // Agrego el legajo por dar de baja en Sue070
        $legajoBaja = new Sue070;
        try {
            $legajoBaja->save();
        } catch (\Exception $e) {
            //throw $th;
            //$legajoBaja->save();
        }

        // Doy de Baa de activos

        if ($request->input('fec_baja') != null) {
            $baja = str_replace('/', '-', $request->input('fec_baja'));
            $legajo->save();
        }

        // return "{\"result\":\"ok\",\"id\":\"$legajo->id\"}";
        return redirect("/profesionales/");
    }


    public function editorder(Request $request, $id = null)
    {
        $order = Cpps30::find($id);

        if ($order == null) {
            return "{\"result\":\"fail\"}";
        }

        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 1;
        
        $orderArray = $order->toArray();

        //"{\"result\":\"ok\",\"id\":\"$orderArray\"}"
        return $orderArray;
    }



    public function deleteorder(Request $request, $id = null)
    {
        $order = Cpps30::find($id);

        if ($order == null) {
            return "{\"result\":\"fail\"}";
        }

        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 1;

        // Agrego el legajo por dar de baja en Sue070
        $orderBaja = new Cpps90;
        try {
            $orderBaja->id = $order->id;
            $orderBaja->ordennro = $order->ordennro;

            $orderBaja->save();
        } catch (\Exception $e) {
            //throw $th;
            //$legajoBaja->save();
        }

        // Delete from cpps30 Active Orders
        $order->delete();

        return "{\"result\":\"ok\",\"id\":\"$order->id\"}";
    }

    public function vieworders(Request $request, $id = null)
    {
        $facturaNew = new Cpps30;
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
        $anterior = 0;
        $cuenta = 0;
        $id_caja = 0;
        $cerrada = null;
        $apertura = null;
        $iconSearch = true;
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 42;
        $novedad = null;
        $order = null;
        $fecha_orig = null;
        $fecha5 = null;
        $facturas = null;
        
        $facturas = Cpps30::orderBy('id','asc')
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

        return view('facturacion.ordenes')->with(compact('novedad','apertura','id_caja','fecha','legajo',
            'facturaNew','iconSearch', 'agregar','edicion','active','facturas','legajos', 'order','id_crud', 'legajoReadOnly','fecha5','id_caja','cerrada'));
    }


    public function search(Request $request)
    {
        $active = 1;
        $id_caja = 0;
        $cerrada = false;
        $fecha = null;
        $iconSearch = false;

        //$legajos = Cpps01::paginate(5);
        $legajos = Cpps01::name($request->get('name'))
            ->where('codigo', '!=', null)
            ->orderBy('mat_prov_cole')
            ->paginate(10);

        //dd($legajos);

        $name = $request->get('name');

        return view('ordenes.search')->with(compact('legajos', 'iconSearch', 'active', 'name', 'cerrada', 'id_caja', 'fecha'));
    }


    public function search2(Request $request)
    {
        $active = 1;
        $id_caja = 0;
        $cerrada = false;
        $fecha = null;
        $iconSearch = false;

        //$legajos = Cpps01::paginate(5);
        $legajos = Cpps07::name($request->get('name'))
            ->where('cod_os', '!=', null)
            ->orderBy('cod_os')
            ->paginate(10);

        //dd($legajos);

        $name = $request->get('name');

        return view('ordenes.search2')->with(compact('legajos', 'iconSearch', 'active', 'name', 'cerrada', 'id_caja', 'fecha'));
    }

    public function search3(Request $request) {

        $data = Cpps07::get();

        return json_encode($data);
    }


    public function print()
    {
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 52;
        $fecha = null;
        $id_caja = 0;
        $nrolegajo = 0;
        $cerrada = false;
        $ddesde = Carbon::parse(Carbon::today())->format('d/m/Y');
        $dhasta = Carbon::parse(Carbon::today())->format('d/m/Y');
        $iconSearch = false;

        
        $legajo = Cpps01::Where('mat_prov_cole', '>', 0)
                ->orderBy('mat_prov_cole')
                ->first();      // find($id);

        // Datos de la empresa
        $empresa = Datoempr::first();      // find($id);
        //if ($empresa == null) {
        //    return redirect('/empresa/');
        //}

        // Si a pesar de todos los controles $legajo es null es porque no hay registros
        if ($legajo == null)
            $legajo = new Cpps01;

        // 1ro buscamos la apertura de la caja actual
        $apertura = Fza020::whereNull('cerrada')->first();

        // Si no hay aperturas redirijo a apertura
        if ($apertura != null) {
            $fechaActual = $apertura->fecha;
            $fecha = $apertura->fecha;
            $id_caja = $apertura->id;
        }

        $origen = Fza030::orderBy('fecha')->first();
        if ($origen != null) {
            $ddesde = $origen->fecha;   //Carbon::parse($origen->fecha)->format('d/m/Y');
        }
        
        $origen = Fza030::orderBy('fecha', 'Desc')->first();
        if ($origen != null) {
            $dhasta = $origen->fecha;   //Carbon::parse($origen->fecha)->format('d/m/Y');
        }

        $conceptos = Cpps01::orderBy('mat_prov_cole')->get();

        return view('ordenes.print')->with(compact(
            'empresa',
            'legajo',
            'conceptos',
            'iconSearch',
            'agregar',
            'edicion',
            'active',
            'fecha',
            'id_caja',
            'cerrada',
            'ddesde',
            'dhasta'
        ));
    }

    public function printpdf(Request $request)
    {
        $pdf = \App::make('dompdf.wrapper');

        $codsector = null;
        $cod_nov = null;
        $cerrada = false;
        $fecha = null;
        $legajo = null;
        $novedad = null;
        $agregar = true;
        $edicion = true;
        $active = 1;
        $anterior = 0;
        $cuenta = 0;
        $id_caja = 0;
        $cajaAbierta = [];
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $novedad = null;
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

        // fix error: SQLSTATE[42000]: Syntax error or access violation: 1055 Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column
        //config()->set('database.connections.your_connection.strict', false);
        
        //$novedades = Cpps01::orderBy('fecha')->where('id',0)->paginate(9);
        $desde = $request->input('ddesde');
        $hasta = $request->input('dhasta');
        $cerrada = $request->input('cerrada');
        $concepto1 = $request->input('concepto');
        $concepto2 = $request->input('concepto2');
        
        $novedades = Fza030::where('concepto', $concepto1)
            ->whereBetween('fecha', [$desde, $hasta])
            ->orderBy('fecha','asc')
            ->orderBy('numero','asc')
            ->get();
            
        
        //->join('mdl003s', function ($join) {
        //    $join->on('mdl060s.prestador', '=', 'mdl003s.id');
        //  })

        // ->orderBy('mdl060s.fecha')

        // Tamano hoja
        //$pdf->setPaper('A4', 'landscape');

        // Cargar view
        $pdf->loadview('ordenes.printpdf', compact('cajaAbierta', 'novedades', 'desde', 'hasta', 'cerrada'));
        
        // Generar el PDF al navegador

        return $pdf->stream();
    }


    public function excel(Request $request)
    {
        $desde = $request->input('ddesde');
        $hasta = $request->input('dhasta');
        $cerrada = $request->input('cerrada');
        $concepto1 = $request->input('concepto');
        $concepto2 = $request->input('concepto2');

        return \Excel::download(new ProfesionalesExport($desde, $hasta, $concepto1, $concepto2), 'Conceptos_fecha.xlsx');
    }



    //---------------------------------------------------------------------------------
    public function print2()
    {
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 52;
        $fecha = null;
        $id_caja = 0;
        $nrolegajo = 0;
        $cerrada = false;
        $ddesde = Carbon::parse(Carbon::today())->format('d/m/Y');
        $dhasta = Carbon::parse(Carbon::today())->format('d/m/Y');
        $iconSearch = false;

        
        $legajo = Cpps01::Where('mat_prov_cole', '>', 0)
                ->orderBy('mat_prov_cole')
                ->first();      // find($id);

        // Datos de la empresa
        $empresa = Datoempr::first();      // find($id);
        //if ($empresa == null) {
        //    return redirect('/empresa/');
        //}

        // Si a pesar de todos los controles $legajo es null es porque no hay registros
        if ($legajo == null)
            $legajo = new Cpps01;

        // 1ro buscamos la apertura de la caja actual
        $apertura = Fza020::whereNull('cerrada')->first();

        // Si no hay aperturas redirijo a apertura
        if ($apertura != null) {
            $fechaActual = $apertura->fecha;
            $fecha = $apertura->fecha;
            $id_caja = $apertura->id;
        }

        $origen = Fza030::orderBy('fecha')->first();
        if ($origen != null) {
            $ddesde = $origen->fecha;   //Carbon::parse($origen->fecha)->format('d/m/Y');
        }
        
        $origen = Fza030::orderBy('fecha', 'Desc')->first();
        if ($origen != null) {
            $dhasta = $origen->fecha;   //Carbon::parse($origen->fecha)->format('d/m/Y');
        }

        // Defaults values in form
        $legajo->cod_os = '1050';
        $legajo->nom_ape = 1;
        $legajo->nom_ape2 = 1;

        $firstProfesional = Cpps01::select('mat_prov_cole', 'nom_ape')->orderBy('nom_ape', 'asc')->first();
        
        if ($firstProfesional != null) {
            $legajo->nom_ape = $firstProfesional->nom_ape;
        }

        $lastProfesional = Cpps01::select('mat_prov_cole', 'nom_ape')->orderBy('nom_ape', 'desc')->first();
        
        if ($lastProfesional != null) {
            $legajo->nom_ape2 = $lastProfesional->nom_ape;
        }

        $profesionales = Cpps01::orderBy('nom_ape')->get();     // mat_prov_cole 
        $obras = Cpps07::orderBy('cod_os')->get();
        $nomencladores = Cpps09::orderBy('cod_nomen')->get();
        $prestaciones = Cpps09::orderBy('nom_prest')->get();

        return view('ordenes.print')->with(compact(
            'empresa',
            'legajo',
            'iconSearch',
            'agregar',
            'edicion',
            'active',
            'fecha',
            'id_caja',
            'cerrada',
            'ddesde',
            'dhasta',
            'profesionales',
            'nomencladores',
            'prestaciones',
            'obras'
        ));
    }
    
    
    
    public function printpdf2(Request $request)
    {
        $pdf = \App::make('dompdf.wrapper');

        $codsector = null;
        $fecha = null;
        $legajo = null;
        $active = 1;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $novedades = null;
        $nombreObra = '';
        
        //$fecha_orig = Carbon::parse( Carbon::now() )->format('d/m/Y');

        //if ($nrolegajo != null) {
        //  $legajoNew->legajo = $id;
        // Busco el legajo seleccionado
        //  $legajoNew->detalle = $legajoNew->Apynom;
        //}
        //$legajo->fecha_naci = Carbon::parse($legajo->fecha_naci)->format('d/m/Y');
        //$legajo->alta = Carbon::parse($legajo->alta)->format('d/m/Y');

        // fix error: SQLSTATE[42000]: Syntax error or access violation: 1055 Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column
        $periodo = $request->input('periodo');
        $obra = $request->input('det_os');

        $searchObra = Cpps07::where('cod_os', $obra)->first();
        if ($searchObra != null) {
            $nombreObra = $searchObra->desc_os;
        }
        
        $desde = $request->input('ddesde');
        $hasta = $request->input('dhasta');
        
        $profesional1 = $request->input('profesional');
        $profesional2 = $request->input('profesional2');

        $novedades = Cpps30::where('periodo', $periodo)
            ->join('cpps01s', function ($join) {
                    $join->on('cpps01s.mat_prov_cole', '=', 'cpps30s.mat_prov_cole');
                  })
            ->join('cpps09s', function ($join) {
                    $join->on('cpps09s.cod_nemotecnico', '=', 'cpps30s.cod_nemotecnico');
                  })
            ->join('cpps14s', function ($join) {
                $join->on('cpps14s.cod_nemotecnico', '=', 'cpps30s.cod_nemotecnico')->on('cpps14s.cod_convenio', '=', 'cpps30s.plan');
                })
            ->select('cpps30s.*', 'cpps01s.mat_prov_cole', 'cpps01s.nom_ape', 'cpps09s.nom_prest')
            ->whereBetween('cpps01s.nom_ape', [$profesional1, $profesional2])
            ->orderBy('cpps01s.nom_ape','asc')
            ->orderBy('cpps30s.ordennro','asc')
            ->get();
        
        //
        //->join('mdl003s', function ($join) {
        //    $join->on('mdl060s.prestador', '=', 'mdl003s.id');
        //  })

        // ->orderBy('mdl060s.fecha')

        // Tamano hoja
        //$pdf->setPaper('A4', 'landscape');

        //dd($novedades);

        // Cargar view
        //$pdf->loadview('ordenes.printpdf', compact('periodo', 'novedades', 'desde', 'hasta', 'obra', 'nombreObra', 'profesional1', 'profesional2'));
        
        // Generar el PDF al navegador
        //return $pdf->stream();

        return view('ordenes.printpdf')->with(compact(
            'periodo',
            'novedades',
            'desde',
            'hasta',
            'obra',
            'nombreObra',
            'profesional1',
            'profesional2'
        ));
    }


    public function excel2(Request $request)
    {
        $periodo = $request->input('periodo');
        $profesional1 = $request->input('profesional');
        $profesional2 = $request->input('profesional2');
        $obra = $request->input('det_os');
        $obra = substr($obra, 0, 11);

        return \Excel::download(new OrdenesExport($periodo, $obra, $profesional1, $profesional2), 'Ordenes.xlsx');
    }


    public function importar(Request $request)
    {
        $cod_os = '1050';
        $iconSearch = true;
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 1;
        $fecha = null;
        $id_caja = 0;
        $cerrada = false;
        $ddesde = Carbon::parse(Carbon::today())->format('d/m/Y');
        $dhasta = Carbon::parse(Carbon::today())->format('d/m/Y');
        $periodo = '2022-09';

        $legajo = Cpps30::Where('mat_prov_cole', '>', 0)
                ->orderBy('mat_prov_cole')
                ->first();
        $empresa = Datoempr::first();      // find($id);
        $profesionales = Cpps01::orderBy('nom_ape')->get();
        $obras = Cpps07::orderBy('cod_os')->get();
        $conv_os = Cpps12::where('cod_os', $cod_os)->get();
        $nomencladores = Cpps09::orderBy('cod_nomen')->get();
        $prestaciones = Cpps09::orderBy('nom_prest')->get();

        return view('ordenes.importar')->with(compact(
            'empresa',
            'legajo',
            'iconSearch',
            'agregar',
            'edicion',
            'active',
            'fecha',
            'id_caja',
            'cerrada',
            'ddesde',
            'dhasta',
            'profesionales',
            'nomencladores',
            'prestaciones',
            'obras',
            'conv_os'
        ));
    }


    public function importar2(Request $request)
    {
        if ($request->hasFile('xls_import1')) {
            $path = $request->file('xls_import1')->getRealPath();

            $datos = Excel::import(new CsvImport, function($reader) {
            })->get();

            if (!empty($datos) && $datos->count()) {
                $datos = $datos->toArray();

                dd($datos);

                for ($i=0; $i < count($datos); $i++) { 
                    $renglon = $datos[$i];
                }
            }
            
            Cpps30::insert($renglon);
        }

        return back();
    }
    
    
    
    public function test()
    {
        $iconSearch = false;
        $active = 1;

        return view('ordenes.test')->with(compact(
            'iconSearch',
            'active'
        ));
    }
}
