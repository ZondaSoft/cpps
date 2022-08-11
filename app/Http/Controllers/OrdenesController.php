<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datoempr;
use App\Models\Fza030;  // Movimientos
use App\Models\Fza020;  // Head de movimientos
use App\Models\Cpps01;  // Profesionales
use App\Models\Cpps07;  // Obras sociales
use App\Models\Cpps09;  // Nomencladores
use App\Models\Cpps30;  // Ordenes
use Carbon\Carbon;

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
        
        $profesionales = Cpps01::orderBy('mat_prov_cole')->get();
        $obras = Cpps07::orderBy('cod_os')->get();
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
            'cerrada',
            'nomencladores',
            'prestaciones'
        ));
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
        $legajo->periodo = '08/2022';
        $legajo->fecha = Carbon::Now()->format('Y-m-d');
        $legajo->cod_os = '1050';

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

        $images = null;

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

        $images = null;

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
        $legajo->mat_prov_cole = 1;
        $legajo->mat_prov_cole2 = 1;

        $lastProfesional = Cpps01::orderBy('mat_prov_cole', 'desc')->first();
        if ($lastProfesional != null) {
            $legajo->mat_prov_cole2 = $lastProfesional->mat_prov_cole;
        }

        $profesionales = Cpps01::orderBy('mat_prov_cole')->get();
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
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $novedad = null;
        $order = null;
        $fecha_orig = null;
        $fecha5 = null;
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
        
        $novedades = Cpps30::where('periodo', $periodo)->whereBetween('cpps30s.mat_prov_cole', [$profesional1, $profesional2])
            ->orderBy('cpps30s.mat_prov_cole','asc')
            ->orderBy('cpps30s.ordennro','asc')
            ->join('cpps01s', function ($join) {
                    $join->on('cpps01s.mat_prov_cole', '=', 'cpps30s.mat_prov_cole');
                  })
            ->join('cpps09s', function ($join) {
                    $join->on('cpps09s.cod_nemotecnico', '=', 'cpps30s.cod_nemotecnico');
                  })
            ->join('cpps14s', function ($join) {
                $join->on('cpps14s.cod_nemotecnico', '=', 'cpps30s.cod_nemotecnico');
                })
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
        $pdf->loadview('ordenes.printpdf', compact('periodo', 'novedades', 'desde', 'hasta', 'obra', 'nombreObra', 'profesional1', 'profesional2'));
        
        // Generar el PDF al navegador

        return $pdf->stream();
    }


    public function excel2(Request $request)
    {
        $desde = $request->input('ddesde');
        $hasta = $request->input('dhasta');
        $periodo = $request->input('periodo');
        $profesional1 = $request->input('profesional');
        $profesional2 = $request->input('profesional2');

        return \Excel::download(new ProfesionalesExport($desde, $hasta, $profesional1, $profesional2), 'Ordenes.xlsx');
    }
}
