<?php

namespace App\Http\Controllers;
use App\Models\Datoempr;
use App\Models\Cpa010;
use App\Models\Fza002;
use App\Models\Fza020;
use App\Models\Fza030;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConceptosController extends Controller
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

        if ($id == null) {
            $legajo = Cpa010::Where('codigo', '>', 0)
                ->orderBy('codigo')
                ->first();      // find($id);

            //dd($legajo);

            if ($legajo != null) {
                $id = $legajo->id;
                $nrolegajo = $legajo->codigo;
            }
        } else {
            $legajo = Cpa010::find($id);
            if ($legajo == null) {
                $legajo = Cpa010::Where('codigo', '>', 0)
                    ->orderBy('codigo')
                    ->first();      // first(); // find($id);     // dd($legajo);   // $legajo = new Cpa010;
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
            $legajo = new Cpa010;

        // Si la var. $direction muestra que el cursor s    e mueve (-1)
        if ($direction == -1) {
            $legajo = Cpa010::where('codigo', '<', $nrolegajo)
                ->Where('codigo', '>', 0)
                ->orderBy('codigo', 'desc')
                ->first();

            if ($legajo == null)
                $legajo = Cpa010::Where('codigo', '>', 0)
                    ->orderBy('codigo')
                    ->first();
        }

        // Si la var. $direction muestra que el cursor se mueve (+1)
        if ($direction == 1) {
            $legajo = Cpa010::where('codigo', '>', $nrolegajo)
                ->Where('codigo', '>', 0)
                ->orderBy('codigo')
                ->first();

            if ($legajo == null)
                $legajo = Cpa010::latest('id')
                    ->where('codigo', '>', 0)
                    ->first();
        }


        // Si la var. $direction muestra que el cursor se mueve al final
        if ($direction == 9) {
            $legajo = Cpa010::latest('codigo')
                ->where('codigo', '>', 0)
                ->first();
        }

        // 1ro buscamos la apertura de la caja actual
        $apertura = Fza020::whereNull('cerrada')->first();

        // Si no hay aperturas redirijo a apertura
        if ($apertura != null) {
            $fechaActual = $apertura->fecha;
            $fecha = $apertura->fecha;
            $id_caja = $apertura->id;
        }

        $now = Carbon::now();

        return view('conceptos.index')->with(compact(
            'empresa',
            'legajo',
            'agregar',
            'edicion',
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

        // 1ro buscamos la apertura de la caja actual
        $apertura = Fza020::whereNull('cerrada')->first();

        // Si no hay aperturas redirijo a apertura
        if ($apertura != null) {
            $fechaActual = $apertura->fecha;
            $fecha = $apertura->fecha;
            $id_caja = $apertura->id;
        }

        $legajo = new Cpa010;      // find($id);     // dd($legajo);

        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $agregar = True;
        $active = 1;

        return view('conceptos.index')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'active',
            'fecha',
            'id_caja',
            'cerrada'
        ));
    }


    public function store(Request $request)
    {
        // Validaciones
        $messages = [
            'codigo.required' => 'El Código del cliente es obligatorio',
            'codigo.unique' => 'El Código del cliente ya existe',
            'detalle.required' => 'La razón social es obligatorio',
            'detalle.min' => 'La razón social debe tener más de 2 letras'
        ];

        $rules = [
            'codigo' => 'required|unique:vta001s',
            'detalle' => 'required|min:2'
        ];

        $this->validate($request, $rules, $messages);

        $legajo = new Cpa010();
        //$request->all();
        //$legajo = Cpa010::create($request->all()); // massives assignments : all() -> onLy() // only('name','description')
        
        $legajo->cuenta = $request->input('cuenta');
        $legajo->codigo = $request->input('codigo');
        $legajo->detalle = $request->input('detalle');

        $legajo->save();   // INSERT INTO - SQL

        if ($legajo->codigo > 0)
            return redirect('/conceptos/' . $legajo->id)->with('success', 'El cliente fue creado con éxito');
    }


    public function edit($id = 0)
    {
        $id_caja = 0;
        $fecha = null;

        if ($id == 0) {
            return redirect('/conceptos');
        }
        
        $legajo = Cpa010::find($id);
        if ($legajo == null) {
            return redirect('/conceptos');
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

        return view('conceptos.index')->with(compact(
            'legajo',
            'agregar',
            'edicion',
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
            'cuenta.required' => 'El Código de cuenta es obligatorio',
            'detalle.required' => 'La descripción es obligatoria',
            'detalle.min' => 'La Razon social debe tener más de 2 letras'
        ];

        $rules = [
            'cuenta' => 'required',
            'detalle' => 'required|min:2'
        ];

        // Validacion de campos
        $this->validate($request, $rules, $messages);

        // Grabar en bbdd los cambios del form de alta
        // dd($request->all());
        $legajo = Cpa010::find($id);

        $legajo->cuenta = $request->input('cuenta');
        //$legajo->codigo = $request->input('codigo');
        $legajo->detalle = $request->input('detalle');
        

        $legajo->update($request->only('detalle', 'cuenta'));

        // dd($legajo->cod_centro);

        return redirect('/conceptos/' . $id);
    }


    public function delete($id)
    {
        $legajo = Cpa010::find($id);
        if ($legajo == null) {
            return "{\"result\":\"cancel\",\"id\":\"$legajo->id\"}";
        }

        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 17;
        $cerrada = false;

        $images = null;

        return "{\"result\":\"ok\",\"id\":\"$legajo->id\",\"codigo\":\"$legajo->codigo\",\"detalle\":\"$legajo->detalle\",\"}";
        //return redirect("/conceptos/");
    }

    public function baja(Request $request, $id = null)
    {
        // return "Mostrar form de edit $id";
        $legajo = Cpa010::find($id);
        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 17;

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
        return redirect("/conceptos/");
    }


    public function search(Request $request)
    {
        $active = 1;
        $id_caja = 0;
        $cerrada = false;
        $fecha = null;

        //$legajos = Cpa010::paginate(5);
        $legajos = Cpa010::name($request->get('name'))
            ->where('codigo', '!=', null)
            ->orderBy('codigo')
            ->paginate(10);

        //dd($legajos);

        $name = $request->get('name');

        return view('conceptos.search')->with(compact('legajos', 'active', 'name', 'cerrada', 'id_caja', 'fecha'));
    }


    public function print()
    {
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 10;
        $fecha = null;
        $id_caja = 0;
        $nrolegajo = 0;
        $cerrada = false;
        $ddesde = Carbon::parse(Carbon::today())->format('d/m/Y');
        $dhasta = Carbon::parse(Carbon::today())->format('d/m/Y');
        
        $legajo = Cpa010::Where('codigo', '>', 0)
                ->orderBy('codigo')
                ->first();      // find($id);

        // Datos de la empresa
        $empresa = Datoempr::first();      // find($id);
        //if ($empresa == null) {
        //    return redirect('/empresa/');
        //}

        // Si a pesar de todos los controles $legajo es null es porque no hay registros
        if ($legajo == null)
            $legajo = new Cpa010;

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
            $ddesde = Carbon::parse($origen->fecha)->format('d/m/Y');
        }
        
        $origen = Fza030::orderBy('fecha', 'Desc')->first();
        if ($origen != null) {
            $dhasta = Carbon::parse($origen->fecha)->format('d/m/Y');
        }

        $conceptos = Cpa010::orderBy('codigo')->get();

        return view('conceptos.print')->with(compact(
            'empresa',
            'legajo',
            'conceptos',
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
        $active = 26;
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
        
        //$novedades = Cpa010::orderBy('fecha')->where('id',0)->paginate(9);
        $concepto1 = $request->input('concepto');
        $concepto2 = $request->input('concepto2');
        
        $novedades = Fza030::where('concepto', $concepto1)
            ->orderBy('fecha','asc')
            ->orderBy('numero','asc')
            ->get();
            
        $desde = $request->input('ddesde');
        $hasta = $request->input('dhasta');
        $cerrada = $request->input('cerrada');

        //->join('mdl003s', function ($join) {
        //    $join->on('mdl060s.prestador', '=', 'mdl003s.id');
        //  })

        // ->orderBy('mdl060s.fecha')

        // Tamano hoja
        //$pdf->setPaper('A4', 'landscape');

        // Cargar view
        $pdf->loadview('conceptos.printpdf', compact('cajaAbierta', 'novedades', 'desde', 'hasta', 'cerrada'));
        
        // Generar el PDF al navegador

        return $pdf->stream();
   }
}
