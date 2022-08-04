<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Datoempr;
use App\Models\Cpps09;
use Carbon\Carbon;
use DB;

class NomencladorController extends Controller
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
        $fecha = null;
        $id_caja = 0;
        $nrolegajo = 0;
        $cerrada = false;
        $iconSearch = true;

        if ($id == null) {
            $legajo = Cpps09::Where('id_nomen', '>', 0)
                ->orderBy('id_nomen')
                ->first();      // find($id);

            if ($legajo != null) {
                $id = $legajo->id_nomen;
                $nrolegajo = $legajo->codigo;
            }
        } else {
            $legajo = Cpps09::find($id);
            if ($legajo == null) {
                $legajo = Cpps09::Where('id_nomen', '>', 0)
                    ->orderBy('id_nomen')
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
            $legajo = new Cpps09;

        // Si la var. $direction muestra que el cursor s    e mueve (-1)
        if ($direction == -1) {
            $legajo = Cpps09::where('codigo', '<', $nrolegajo)
                ->Where('id_nomen', '>', 0)
                ->orderBy('codigo', 'desc')
                ->first();

            if ($legajo == null)
                $legajo = Cpps09::Where('id_nomen', '>', 0)
                    ->orderBy('id_nomen')
                    ->first();
        }

        // Si la var. $direction muestra que el cursor se mueve (+1)
        if ($direction == 1) {
            $legajo = Cpps09::where('id_nomen', '>', $nrolegajo)
                ->Where('id_nomen', '>', 0)
                ->orderBy('id_nomen')
                ->first();

            if ($legajo == null)
                $legajo = Cpps09::latest('id')
                    ->where('id_nomen', '>', 0)
                    ->first();
        }


        // Si la var. $direction muestra que el cursor se mueve al final
        if ($direction == 9) {
            $legajo = Cpps09::latest('codigo')
                ->where('id_nomen', '>', 0)
                ->first();
        }

        $now = Carbon::now();

        return view('nomencladores.index')->with(compact(
            'empresa',
            'legajo',
            'agregar',
            'edicion',
            'iconSearch',
            'active',
            'fecha',
            'id_caja',
            'cerrada'
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

        $legajo = new Cpps09;      // find($id);     // dd($legajo);
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $agregar = True;
        $active = 12;

        return view('nomencladores.index')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'active',
            'iconSearch',
            'fecha',
            'id_caja',
            'cerrada'
        ));
    }


    public function store(Request $request)
    {
        // Validaciones
        $messages = [
            'cod_nomen.required' => 'El código de nomenclador es obligatorio',
            'cod_nomen.unique' => 'El código de nomenclador ya existe',
            'nom_prest.required' => 'El nombre es obligatorio',
            'nom_prest.min' => 'El nombre debe tener más de 2 letras'
        ];

        $rules = [
            'cod_nomen' => 'required|unique:cpps09s',
            'nom_prest' => 'required|min:2'
        ];

        $this->validate($request, $rules, $messages);

        $legajo = new Cpps09();
        //$request->all();
        //$legajo = Cpps09::create($request->all()); // massives assignments : all() -> onLy() // only('name','description')
        
        $legajo->cod_nomen = $request->input('cod_nomen');
        $legajo->cod_nemotecnico = $request->input('cod_nemotecnico');
        $legajo->nom_prest = $request->input('nom_prest');
        $legajo->observacion = $request->input('observacion');
        $legajo->desc_variante = $request->input('desc_variante');
        $legajo->estado_nomen = $request->input('estado_nomen');
        $legajo->ips = $request->input('ips');

        $legajo->save();   // INSERT INTO - SQL

        if ($legajo->id_nomen_nomen > 0)
            return redirect('/nomenclador/' . $legajo->id_nomen_nomen)->with('success', 'El cliente fue creado con éxito');

        return redirect('/nomenclador/');
    }


    public function edit($id = 0)
    {
        $fecha = null;
        $iconSearch = false;

        if ($id == 0) {
            return redirect('/nomenclador');
        }
        
        $legajo = Cpps09::find($id);
        if ($legajo == null) {
            return redirect('/nomenclador');
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

        return view('nomencladores.index')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'iconSearch',
            'active',
            'fecha'
            
        ));    // Abrir form de modificacion
    }


    public function update(Request $request, $id)
    {
        // Validaciones
        $messages = [
            'nom_prest.required' => 'El nombre del nomenclador es obligatorio',
            'nom_prest.min' => 'El nombre del nomenclador debe tener más de 2 letras'
        ];

        $rules = [
            'nom_prest' => 'required|min:2'
        ];

        // Validacion de campos
        $this->validate($request, $rules, $messages);

        // Grabar en bbdd los cambios del form de alta
        // dd($request->all());
        $legajo = Cpps09::find($id);

        $legajo->cod_nemotecnico = $request->input('cod_nemotecnico');
        $legajo->nom_prest = $request->input('nom_prest');
        $legajo->observacion = $request->input('observacion');
        $legajo->desc_variante = $request->input('desc_variante');
        $legajo->estado_nomen = $request->input('estado_nomen');
        $legajo->ips = $request->input('ips');

        $legajo->update($request->only('cod_nemotecnico', 'nom_prest'. 'observacion', 'desc_variante', 'estado_nomen', 'ips'));

        // dd($legajo->cod_centro);

        return redirect('/nomenclador/' . $id);
    }


    public function delete($id)
    {
        $legajo = Cpps09::find($id);
        if ($legajo == null) {
            return "{\"result\":\"cancel\",\"id\":\"$legajo->id_nomen\"}";
        }

        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 12;
        $cerrada = false;

        $images = null;

        return "{\"result\":\"ok\",\"id\":\"$legajo->id_nomen\",\"codigo\":\"$legajo->codigo\",\"detalle\":\"$legajo->detalle\",\"}";
        //return redirect("/nomenclador/");
    }

    public function baja(Request $request, $id = null)
    {
        // return "Mostrar form de edit $id";
        $legajo = Cpps09::find($id);
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

        // return "{\"result\":\"ok\",\"id\":\"$legajo->id_nomen\"}";
        return redirect("/nomenclador/");
    }


    public function search(Request $request)
    {
        $active = 12;
        $id_caja = 0;
        $cerrada = false;
        $fecha = null;
        $iconSearch = false;

        //$legajos = Cpps09::paginate(5);
        $legajos = Cpps09::name($request->get('name'))
            ->where('cod_nomen', '!=', null)
            ->orderBy('cod_nomen')
            ->paginate(12);

        //dd($legajos);

        $name = $request->get('name');

        return view('nomencladores.search')->with(compact('legajos', 'iconSearch', 'active', 'name', 'cerrada', 'id_caja', 'fecha'));
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

        
        $legajo = Cpps09::Where('id_nomen', '>', 0)
                ->orderBy('id_nomen')
                ->first();      // find($id);

        // Datos de la empresa
        $empresa = Datoempr::first();      // find($id);
        //if ($empresa == null) {
        //    return redirect('/empresa/');
        //}

        // Si a pesar de todos los controles $legajo es null es porque no hay registros
        if ($legajo == null)
            $legajo = new Cpps09;

        // 1ro buscamos la apertura de la caja actual
        $apertura = Fza020::whereNull('cerrada')->first();

        // Si no hay aperturas redirijo a apertura
        if ($apertura != null) {
            $fechaActual = $apertura->fecha;
            $fecha = $apertura->fecha;
            $id_caja = $apertura->id_nomen;
        }

        $origen = Fza030::orderBy('fecha')->first();
        if ($origen != null) {
            $ddesde = $origen->fecha;   //Carbon::parse($origen->fecha)->format('d/m/Y');
        }
        
        $origen = Fza030::orderBy('fecha', 'Desc')->first();
        if ($origen != null) {
            $dhasta = $origen->fecha;   //Carbon::parse($origen->fecha)->format('d/m/Y');
        }

        $conceptos = Cpps09::orderBy('id_nomen')->get();

        return view('nomencladores.print')->with(compact(
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
        
        //$novedades = Cpps09::orderBy('fecha')->where('id',0)->paginate(9);
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
        $pdf->loadview('nomencladores.printpdf', compact('cajaAbierta', 'novedades', 'desde', 'hasta', 'cerrada'));
        
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
