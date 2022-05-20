<?php

namespace App\Http\Controllers;
use App\Models\Datoempr;
use App\Models\Cpa010;
use App\Models\Fza002;
use App\Models\Fza020;
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

        $legajo->fecha_naci = Carbon::parse($legajo->fecha_naci)->format('d/m/Y');
        $legajo->alta = Carbon::parse($legajo->alta)->format('d/m/Y');
        $legajo->fecha_vto = Carbon::parse($legajo->fecha_vto)->format('d/m/Y');
        $legajo->ultima_act = Carbon::parse($legajo->ultima_act)->format('d/m/Y');

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
            'detalle.required' => 'La Razon social es obligatoria',
            'detalle.min' => 'La Razon social debe tener más de 2 letras',
            'nom_com.required' => 'El nombre comercial es obligatorio'
        ];

        $rules = [
            'detalle' => 'required|min:2',
            'nom_com' => 'required'
        ];

        // Validacion de campos
        $this->validate($request, $rules, $messages);

        // Grabar en bbdd los cambios del form de alta
        // dd($request->all());
        $legajo = Cpa010::find($id);

        $legajo->detalle = $request->input('detalle');
        $legajo->cuit = $request->input('cuit');
        $legajo->domic = $request->input('domic');
        $legajo->dom_com = $request->input('dom_com');
        $legajo->localid = $request->input('localid');
        $legajo->codpostal = $request->input('codpostal');
        $legajo->tel1 = $request->input('tel1');
        $legajo->tel2 = $request->input('tel2');
        $legajo->tel3 = $request->input('tel3');
        $legajo->email = $request->input('email');
        $legajo->web = $request->input('web');
        
        // Pestaña forma de pago (update)
        $legajo->formap = $request->input('formap');
        $legajo->banco = $request->input('banco');
        $legajo->sucursal = $request->input('sucursal');
        $legajo->cuenta = $request->input('cuenta');
        $legajo->cbu = $request->input('cbu');

        $legajo->update($request->only('detalle', 'nom_com', 'cuit', 'domic', 'nom_com', 'localid', 'codpostal', 'tel1', 'tel2', 'tel3', 'email', 'web', 'forma_pago', 'banco', 'sucursal', 'cuenta', 'cbu'));

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
}
