<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Datoempr;
use App\Models\Cpps07;  // Obras social
use App\Models\Cpps11;  // Convenios de OS
use App\Models\Cpps12;  // Pivote table betwwen OS & convenios
use App\Models\Cpps14;  // Pivote table convenios & nomencladores
use Carbon\Carbon;


class ConveniosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id = null, $direction = null)
    {
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 12;
        $fecha = Carbon::now()->format('Y-m-d');
        $id_caja = 0;
        $nrolegajo = 0;
        $cerrada = false;
        $iconSearch = true;
        $cod_conv = 0;

        if ($id == null) {
            $legajo = Cpps11::Where('cod_conv', '>', 0)
                ->orderBy('cod_conv')
                ->first();      // find($id);

            if ($legajo != null) {
                $id = $legajo->cod_conv;
                $nrolegajo = $legajo->codigo;
            }
        } else {
            $legajo = Cpps11::find($id);
            if ($legajo == null) {
                $legajo = Cpps11::Where('cod_conv', '>', 0)
                    ->orderBy('cod_conv')
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
            $legajo = new Cpps11;

        // Si la var. $direction muestra que el cursor s    e mueve (-1)
        if ($direction == -1) {
            $legajo = Cpps11::where('codigo', '<', $nrolegajo)
                ->Where('cod_conv', '>', 0)
                ->orderBy('codigo', 'desc')
                ->first();

            if ($legajo == null)
                $legajo = Cpps11::Where('cod_conv', '>', 0)
                    ->orderBy('cod_conv')
                    ->first();
        }

        // Si la var. $direction muestra que el cursor se mueve (+1)
        if ($direction == 1) {
            $legajo = Cpps11::where('cod_conv', '>', $nrolegajo)
                ->Where('cod_conv', '>', 0)
                ->orderBy('cod_conv')
                ->first();

            if ($legajo == null)
                $legajo = Cpps11::latest('id')
                    ->where('cod_conv', '>', 0)
                    ->first();
        }


        // Si la var. $direction muestra que el cursor se mueve al final
        if ($direction == 9) {
            $legajo = Cpps11::latest('codigo')
                ->where('cod_conv', '>', 0)
                ->first();
        }

        $now = Carbon::now();

        if ($legajo != null) {
            $cod_conv = $legajo->cod_conv;
            $obraSeleccionada = Cpps12::where('cod_conv', $cod_conv)->first();

            if ($obraSeleccionada != null) {
                $legajo->cod_os = $obraSeleccionada->cod_os;
            }
        }
        
        $firstDate = Cpps14::where('cod_convenio', $legajo->cod_conv)->orderBy('fecha', 'Desc')->first();
        if ($firstDate != null) {
            $fecha = $firstDate->fecha;
        }
        $legajo->fecha = $fecha;

        $obras = Cpps07::orderBy('cod_os')->get();
        $convNomenclador = Cpps14::where('cod_convenio', $legajo->cod_conv)
            ->where('fecha', $fecha)
            ->get();
        
        return view('convenios.index')->with(compact(
            'empresa',
            'legajo',
            'agregar',
            'edicion',
            'obras',
            'iconSearch',
            'active',
            'fecha',
            'id_caja',
            'cerrada',
            'convNomenclador'
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

        $legajo = new Cpps11;      // find($id);     // dd($legajo);
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $agregar = True;
        $active = 12;

        $obras = Cpps07::orderBy('cod_os')->get();
        $convNomenclador = Cpps14::get();

        return view('convenios.index')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'active',
            'iconSearch',
            'fecha',
            'obras',
            'convNomenclador'
        ));
    }


    public function store(Request $request)
    {
        // Validaciones
        $messages = [
            'cod_conv.required' => 'El código del convenio es obligatorio',
            'cod_conv.unique' => 'El código del convenio ya existe',
            'desc_conv.required' => 'El nombre es obligatorio',
            'desc_conv.min' => 'El nombre debe tener más de 2 letras',
            'cod_os.required' => 'Debe seleccionar una Obra Social'
        ];

        $rules = [
            'cod_conv' => 'required|unique:cpps11s',
            'desc_conv' => 'required|min:2',
            'cod_os' => 'required'
        ];

        $this->validate($request, $rules, $messages);

        $legajo = new Cpps11();
        //$request->all();
        //$legajo = Cpps11::create($request->all()); // massives assignments : all() -> onLy() // only('name','description')
        
        $legajo->cod_conv = $request->input('cod_conv');
        $legajo->desc_conv = $request->input('desc_conv');
        $legajo->observacion_conv = $request->input('observacion_conv');
        $legajo->estado_conv = $request->input('estado_conv');
        //$legajo->fecha_alta = $request->input('fecha_alta');

        $legajo->save();   // INSERT INTO - SQL

        
        // Agrego la relacion entre OS y convenio
        $relation = new Cpps12();
        $relation->cod_conv = $legajo->cod_conv;
        $relation->cod_os = $request->input('cod_os');
        $relation->estado_convos = 0;
        //$relation->fecha_inicio = $legajo->fecha_inicio;
        //$relation->fecha_baja = $legajo->fecha_baja;
        $relation->cod_categoria = 0;   // A - 0, 1, 2 ??????
        $relation->save();


        if ($legajo->cod_conv_nomen > 0)
            return redirect('/convenios/' . $legajo->cod_conv)->with('success', 'El cliente fue creado con éxito');

        return redirect('/convenios/');
    }


    public function edit($id = 0)
    {
        $fecha = null;
        $iconSearch = false;

        if ($id == 0) {
            return redirect('/convenios');
        }
        
        $legajo = Cpps11::find($id);
        if ($legajo == null) {
            return redirect('/convenios');
        }

        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 12;
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

        $obras = Cpps07::orderBy('cod_os')->get();
        $convNomenclador = Cpps14::get();

        return view('convenios.index')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'iconSearch',
            'active',
            'fecha',
            'obras',
            'convNomenclador'
        ));    // Abrir form de modificacion
    }


    public function update(Request $request, $id)
    {
        // Validaciones
        $messages = [
            'desc_conv.required' => 'El nombre del convenio es obligatorio',
            'desc_conv.min' => 'El nombre del convenio debe tener más de 2 letras'
        ];

        $rules = [
            'desc_conv' => 'required|min:2'
        ];

        // Validacion de campos
        $this->validate($request, $rules, $messages);

        // Grabar en bbdd los cambios del form de alta
        // dd($request->all());
        $legajo = Cpps11::find($id);

        $legajo->desc_conv = $request->input('desc_conv');
        $legajo->observacion_conv = $request->input('observacion_conv');
        $legajo->estado_conv = $request->input('estado_conv');
        $legajo->fecha_alta = $request->input('fecha_alta');

        $legajo->update($request->only('desc_conv', 'nom_prest'. 'observacion_conv', 'estado_conv', 'fecha_alta'));

        // dd($legajo->cod_centro);

        return redirect('/convenios/' . $id);
    }


    public function delete($id)
    {
        $legajo = Cpps11::find($id);
        if ($legajo == null) {
            return "{\"result\":\"cancel\",\"id\":\"$legajo->cod_conv\"}";
        }

        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 12;
        $cerrada = false;

        $images = null;

        return "{\"result\":\"ok\",\"id\":\"$legajo->cod_conv\",\"codigo\":\"$legajo->codigo\",\"detalle\":\"$legajo->detalle\",\"}";
        //return redirect("/convenios/");
    }

    public function baja(Request $request, $id = null)
    {
        // return "Mostrar form de edit $id";
        $legajo = Cpps11::find($id);
        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 12;

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

        // return "{\"result\":\"ok\",\"id\":\"$legajo->cod_conv\"}";
        return redirect("/convenios/");
    }


    public function search(Request $request)
    {
        $active = 12;
        $id_caja = 0;
        $cerrada = false;
        $fecha = null;
        $iconSearch = false;

        //$legajos = Cpps11::paginate(5);
        $legajos = Cpps11::name($request->get('name'))
            ->where('cod_conv', '!=', null)
            ->orderBy('cod_conv')
            ->paginate(10);

        //dd($legajos);

        $name = $request->get('name');

        return view('convenios.search')->with(compact('legajos', 'iconSearch', 'active', 'name', 'cerrada', 'id_caja', 'fecha'));
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

        
        $legajo = Cpps11::Where('cod_conv', '>', 0)
                ->orderBy('cod_conv')
                ->first();      // find($id);

        // Datos de la empresa
        $empresa = Datoempr::first();      // find($id);
        //if ($empresa == null) {
        //    return redirect('/empresa/');
        //}

        // Si a pesar de todos los controles $legajo es null es porque no hay registros
        if ($legajo == null)
            $legajo = new Cpps11;

        // 1ro buscamos la apertura de la caja actual
        $apertura = Fza020::whereNull('cerrada')->first();

        // Si no hay aperturas redirijo a apertura
        if ($apertura != null) {
            $fechaActual = $apertura->fecha;
            $fecha = $apertura->fecha;
            $id_caja = $apertura->cod_conv;
        }

        $origen = Fza030::orderBy('fecha')->first();
        if ($origen != null) {
            $ddesde = $origen->fecha;   //Carbon::parse($origen->fecha)->format('d/m/Y');
        }
        
        $origen = Fza030::orderBy('fecha', 'Desc')->first();
        if ($origen != null) {
            $dhasta = $origen->fecha;   //Carbon::parse($origen->fecha)->format('d/m/Y');
        }

        $conceptos = Cpps11::orderBy('cod_conv')->get();

        return view('convenios.print')->with(compact(
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
        $active = 12;
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
        
        //$novedades = Cpps11::orderBy('fecha')->where('id',0)->paginate(9);
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
        $pdf->loadview('convenios.printpdf', compact('cajaAbierta', 'novedades', 'desde', 'hasta', 'cerrada'));
        
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
}
