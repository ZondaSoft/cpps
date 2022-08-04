<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Datoempr;
use App\Models\Cpps07;  // Obras sociales
use App\Models\Cpps11;  // Convenios
use App\Models\Cpps12;  // tabla pivote os->convenios
use Carbon\Carbon;
use DB;

class ObrasadminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index($id = null, $direction = null)
    {
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 10;
        $fecha = null;
        $id_caja = 0;
        $nrolegajo = 0;
        $iconSearch = true;

        if ($id == null) {
            $legajo = Cpps07::Where('cod_os', '>', 0)
                ->orderBy('cod_os')
                ->first();      // find($id);

            if ($legajo != null) {
                $id = $legajo->id;
                $nrolegajo = $legajo->cod_os;
            }
        } else {
            $legajo = Cpps07::find($id);
            if ($legajo == null) {
                $legajo = Cpps07::Where('cod_os', '>', 0)
                    ->orderBy('cod_os')
                    ->first();
            }

            if ($legajo != null) {
                $nrolegajo = $legajo->cod_os;
            }
        }

        // Datos de la empresa
        $empresa = Datoempr::first();      // find($id);
        //if ($empresa == null) {
        //    return redirect('/empresa/');
        //}

        // Si a pesar de todos los controles $legajo es null es porque no hay registros
        if ($legajo == null)
            $legajo = new Cpps07;

        // Si la var. $direction muestra que el cursor s    e mueve (-1)
        if ($direction == -1) {
            $legajo = Cpps07::where('codigo', '<', $nrolegajo)
                ->Where('cod_os', '>', 0)
                ->orderBy('codigo', 'desc')
                ->first();

            if ($legajo == null)
                $legajo = Cpps07::Where('cod_os', '>', 0)
                    ->orderBy('cod_os')
                    ->first();
        }

        // Si la var. $direction muestra que el cursor se mueve (+1)
        if ($direction == 1) {
            $legajo = Cpps07::where('cod_os', '>', $nrolegajo)
                ->Where('cod_os', '>', 0)
                ->orderBy('cod_os')
                ->first();

            if ($legajo == null)
                $legajo = Cpps07::latest('id')
                    ->where('cod_os', '>', 0)
                    ->first();
        }


        // Si la var. $direction muestra que el cursor se mueve al final
        if ($direction == 9) {
            $legajo = Cpps07::latest('codigo')
                ->where('cod_os', '>', 0)
                ->first();
        }

        $convenios = Cpps11::orderBy('cod_conv')->get();
        $conv_os = Cpps12::where('cod_os', $legajo->cod_os)->get();

        $now = Carbon::now();

        return view('obras.index')->with(compact(
            'empresa',
            'legajo',
            'agregar',
            'edicion',
            'iconSearch',
            'active',
            'fecha',
            'id_caja',
            'convenios',
            'conv_os'
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
        $iconSearch = false;


        $legajo = new Cpps07;      // find($id);     // dd($legajo);
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $agregar = True;
        $active = 10;


        $convenios = Cpps11::orderBy('cod_conv')->get();
        $conv_os = [];

        return view('obras.index')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'active',
            'iconSearch',
            'fecha',
            'id_caja',
            'convenios',
            'conv_os'
        ));
    }


    public function store(Request $request)
    {
        // Validaciones
        $messages = [
            'cod_os.required' => 'El código de la obra social es obligatorio',
            'cod_os.unique' => 'El código ya existe',
            'desc_os.required' => 'El nombre de la obra social es obligatorio',
            'desc_os.min' => 'El nombre debe tener más de 2 letras'
        ];

        $rules = [
            'cod_os' => 'required|unique:cpps07s',
            'desc_os' => 'required|min:2'
        ];

        $this->validate($request, $rules, $messages);

        $legajo = new Cpps07();
        //$request->all();
        //$legajo = Cpps07::create($request->all()); // massives assignments : all() -> onLy() // only('name','description')
        
        $legajo->cod_os = $request->input('cod_os');
        $legajo->desc_os = $request->input('desc_os');
        $legajo->estado_os = $request->input('estado_os');
        //$legajo->fcha_alta = $request->input('fcha_alta');
        $legajo->contacto = $request->input('contacto');
        $legajo->direccion_os = $request->input('direccion_os');
        $legajo->cp = $request->input('cp');
        $legajo->localidad = $request->input('localidad');
        $legajo->provincia = $request->input('provincia'); 
        $legajo->telefono1 = $request->input('telefono1'); 
        $legajo->telefono2 = $request->input('telefono2'); 
        $legajo->telefono3 = $request->input('telefono3');
        $legajo->observacion = $request->input('observacion'); 
        $legajo->req_paciente = $request->input('req_paciente');
        $legajo->porcent_nino = $request->input('porcent_nino');
        if ($legajo->porcent_nino == null) {
            $legajo->porcent_nino = 0;
        }
        $legajo->cuit = $request->input('cuit');

        $legajo->save();   // INSERT INTO - SQL

        if ($legajo->cod_os > 0)
            return redirect('/obras-admin/' . $legajo->id)->with('success', 'El cliente fue creado con éxito');

        return redirect('/obras-admin/');
    }


    public function edit($id = 0)
    {
        $id_caja = 0;
        $fecha = null;
        $iconSearch = false;

        if ($id == 0) {
            return redirect('/obras-admin');
        }
        
        $legajo = Cpps07::find($id);
        if ($legajo == null) {
            return redirect('/obras-admin');
        }

        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 10;

        $convenios = Cpps11::orderBy('cod_conv')->get();
        $conv_os = Cpps12::where('cod_os', $legajo->cod_os)->get();
        
        // $legajo->fecha_naci = Carbon::parse($legajo->fecha_naci)->format('d/m/Y');
        // $legajo->alta = Carbon::parse($legajo->alta)->format('d/m/Y');
        // $legajo->fecha_vto = Carbon::parse($legajo->fecha_vto)->format('d/m/Y');
        // $legajo->ultima_act = Carbon::parse($legajo->ultima_act)->format('d/m/Y');

        //$bancos = Fza002::orderBy('detalle')->get();

        /* if ($legajo != null) {
            $familiares = Sue002::orderBy('paren')->Where('legajo', '=', $legajo->cod_os)->get();
        } else {
            $familiares = new Sue002;
        } */

        return view('obras.index')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'iconSearch',
            'active',
            'id_caja',
            'fecha',
            'convenios',
            'conv_os'
            
        ));    // Abrir form de modificacion
    }


    public function update(Request $request, $id)
    {
        // Validaciones
        $messages = [
            'desc_os.required' => 'La descripción es obligatoria',
            'desc_os.min' => 'La descripción debe tener más de 2 letras'
        ];

        $rules = [
            'desc_os' => 'required|min:2'
        ];

        // Validacion de campos
        $this->validate($request, $rules, $messages);

        // Grabar en bbdd los cambios del form de alta
        // dd($request->all());
        $legajo = Cpps07::find($id);

        $legajo->desc_os = $request->input('desc_os');
        $legajo->estado_os = $request->input('estado_os');
        //$legajo->fcha_alta = $request->input('fcha_alta');
        $legajo->contacto = $request->input('contacto');
        $legajo->direccion_os = $request->input('direccion_os');
        $legajo->cp = $request->input('cp');
        $legajo->localidad = $request->input('localidad');
        $legajo->provincia = $request->input('provincia'); 
        $legajo->telefono1 = $request->input('telefono1'); 
        $legajo->telefono2 = $request->input('telefono2'); 
        $legajo->telefono3 = $request->input('telefono3');
        $legajo->observacion = $request->input('observacion'); 
        $legajo->req_paciente = $request->input('req_paciente');
        $legajo->porcent_nino = $request->input('porcent_nino');
        $legajo->cuit = $request->input('cuit');
        

        $legajo->update($request->only('desc_os', 'cuenta'));

        // dd($legajo->cod_centro);

        return redirect('/obras-admin/' . $id);
    }


    public function delete($id)
    {
        $legajo = Cpps07::find($id);
        if ($legajo == null) {
            return "{\"result\":\"cancel\",\"id\":\"$legajo->id\"}";
        }

        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 10;
        
        $images = null;

        return "{\"result\":\"ok\",\"id\":\"$legajo->id\",\"codigo\":\"$legajo->cod_os\",\"detalle\":\"$legajo->detalle\",\"}";
        //return redirect("/obras-admin/");
    }

    public function baja(Request $request, $id = null)
    {
        // return "Mostrar form de edit $id";
        $legajo = Cpps07::find($id);
        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 10;

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
        return redirect("/obras-admin/");
    }


    public function search(Request $request)
    {
        $active = 10;
        $id_caja = 0;
        $fecha = null;
        $iconSearch = false;

        //$legajos = Cpps07::paginate(5);
        $legajos = Cpps07::name($request->get('name'))
            ->where('cod_os', '!=', null)
            ->orderBy('cod_os')
            ->paginate(12);

        //dd($legajos);

        $name = $request->get('name');

        return view('obras.search')->with(compact('legajos', 'iconSearch', 'active', 'name', 'id_caja', 'fecha'));
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

        
        $legajo = Cpps07::Where('cod_os', '>', 0)
                ->orderBy('cod_os')
                ->first();      // find($id);

        // Datos de la empresa
        $empresa = Datoempr::first();      // find($id);
        //if ($empresa == null) {
        //    return redirect('/empresa/');
        //}

        // Si a pesar de todos los controles $legajo es null es porque no hay registros
        if ($legajo == null)
            $legajo = new Cpps07;

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

        $conceptos = Cpps07::orderBy('mat_prov_cole')->get();

        return view('obras.print')->with(compact(
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
        $active = 10;
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
        
        //$novedades = Cpps07::orderBy('fecha')->where('id',0)->paginate(9);
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
        $pdf->loadview('obras.printpdf', compact('cajaAbierta', 'novedades', 'desde', 'hasta', 'cerrada'));
        
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
