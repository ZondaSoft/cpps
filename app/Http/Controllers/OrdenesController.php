<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datoempr;
use App\Models\Fza030;  // Movimientos
use App\Models\Fza020;  // Head de movimientos
use App\Models\Cpps01;  // Profesionales
use App\Models\Cpps07;  // Obras sociales
use App\Models\Cpps09;  // Nomencladores
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
            $legajo = Cpps01::Where('mat_prov_cole', '>', 0)
                ->orderBy('mat_prov_cole')
                ->first();      // find($id);

            if ($legajo != null) {
                $id = $legajo->id;
                $nrolegajo = $legajo->codigo;
            }
        } else {
            $legajo = Cpps01::find($id);
            if ($legajo == null) {
                $legajo = Cpps01::Where('mat_prov_cole', '>', 0)
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
            $legajo = new Cpps01;

        // Si la var. $direction muestra que el cursor s    e mueve (-1)
        if ($direction == -1) {
            $legajo = Cpps01::where('codigo', '<', $nrolegajo)
                ->Where('mat_prov_cole', '>', 0)
                ->orderBy('codigo', 'desc')
                ->first();

            if ($legajo == null)
                $legajo = Cpps01::Where('mat_prov_cole', '>', 0)
                    ->orderBy('mat_prov_cole')
                    ->first();
        }

        // Si la var. $direction muestra que el cursor se mueve (+1)
        if ($direction == 1) {
            $legajo = Cpps01::where('mat_prov_cole', '>', $nrolegajo)
                ->Where('mat_prov_cole', '>', 0)
                ->orderBy('mat_prov_cole')
                ->first();

            if ($legajo == null)
                $legajo = Cpps01::latest('id')
                    ->where('mat_prov_cole', '>', 0)
                    ->first();
        }


        // Si la var. $direction muestra que el cursor se mueve al final
        if ($direction == 9) {
            $legajo = Cpps01::latest('codigo')
                ->where('mat_prov_cole', '>', 0)
                ->first();
        }
        
        $profesionales = Cpps01::orderBy('mat_prov_cole')->get();
        $obras = Cpps07::orderBy('cod_os')->get();
        $now = Carbon::now();

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

        $legajo = new Cpps01;      // find($id);     // dd($legajo);
        $legajo->periodo = '07/2022';

        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $agregar = True;
        $active = 1;
        
        $profesionales = Cpps01::orderBy('mat_prov_cole')->get();
        $obras = Cpps07::orderBy('cod_os')->get();

        return view('ordenes.index')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'active',
            'iconSearch',
            'fecha',
            'id_caja',
            'profesionales',
            'obras'
        ));
    }


    public function store(Request $request)
    {
        // Validaciones
        $messages = [
            'mat_prov_cole.required' => 'El Nro. de matricula es obligatoria',
            'mat_prov_cole.unique' => 'El Nro. de matricula ya existe',
            'nom_ape.required' => 'El nombre y apellido es obligatorio',
            'nom_ape.min' => 'El nombre y apellido debe tener más de 2 letras'
        ];

        $rules = [
            'mat_prov_cole' => 'required|unique:cpps01s',
            'nom_ape' => 'required|min:2'
        ];

        $this->validate($request, $rules, $messages);

        $legajo = new Cpps01();
        //$request->all();
        //$legajo = Cpps01::create($request->all()); // massives assignments : all() -> onLy() // only('name','description')
        
        $legajo->mat_prov_cole = $request->input('mat_prov_cole');
        $legajo->nom_ape = $request->input('nom_ape');
        $legajo->sexo = $request->input('sexo');
        $legajo->lugar_nacimiento = $request->input('lugar_nacimiento');
        $legajo->nacionalidad = $request->input('nacionalidad');
        $legajo->tipo_doc = $request->input('tipo_doc');
        $legajo->num_doc = $request->input('num_doc');
        $legajo->cond_iva = $request->input('cond_iva');
        $legajo->cuit = $request->input('cuit');
        
        $legajo->universidad = $request->input('universidad');
        $legajo->especialidad = $request->input('especialidad'); 
        $legajo->mat1 = $request->input('mat1'); 
        $legajo->mat2 = $request->input('mat2'); 
        $legajo->mat3 = $request->input('mat3'); 
        $legajo->mat4 = $request->input('mat4'); 
        $legajo->mat5 = $request->input('mat5'); 

        $legajo->cat_soc = $request->input('cat_soc'); 
        $legajo->forma_cobro = $request->input('forma_cobro'); 
        $legajo->cod_banco = $request->input('cod_banco'); 
        $legajo->cta_bancaria = $request->input('cta_bancaria'); 
        $legajo->cbu = $request->input('cbu'); 
        $legajo->cuota_col_deb_auto = $request->input('cuota_col_deb_auto'); 
        $legajo->seg_mala_prax = $request->input('seg_mala_prax'); 
        $legajo->seg_mala_prax_deb_auto = $request->input('seg_mala_prax_deb_auto'); 
        $legajo->cuota_col = $request->input('cuota_col'); 
        $legajo->caja_SS = $request->input('caja_SS'); 
        $legajo->categ_ss = $request->input('categ_ss'); 
        $legajo->caja_reg_ss = $request->input('caja_reg_ss'); 

        $legajo->activo = true;

        $legajo->save();   // INSERT INTO - SQL
        
        if ($legajo->mat_prov_cole > 0)
            return redirect('/profesionales/' . $legajo->id)->with('success', 'El cliente fue creado con éxito');

        return redirect('/profesionales/');
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
}
