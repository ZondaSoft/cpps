<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datoempr;
use App\Models\Tal001;
use App\Models\Vta001;
use App\Models\Fza002;
use Carbon\Carbon;

class VehiculosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index($id = null, $direction = null)
    {
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 3;
        $clientes = [];

        //-----------------------------

        $nrolegajo = 0;

        if ($id == null) {
            $legajo = Tal001::orderBy('codigo')
                ->first();      // find($id);

            //dd($legajo);

            if ($legajo != null) {
                $id = $legajo->id;
                $nrolegajo = $legajo->codigo;
            }
        } else {
            $legajo = Tal001::find($id);
            if ($legajo == null) {
                $legajo = Tal001::Where('codigo', '>', 0)
                    ->orderBy('codigo')
                    ->first();      // first(); // find($id);     // dd($legajo);   // $legajo = new Tal001;
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
            $legajo = new Tal001;

        // Si la var. $direction muestra que el cursor s    e mueve (-1)
        if ($direction == -1) {
            $legajo = Tal001::where('codigo', '<', $nrolegajo)
                ->Where('codigo', '>', 0)
                ->orderBy('codigo', 'desc')
                ->first();

            if ($legajo == null)
                $legajo = Tal001::Where('codigo', '>', 0)
                    ->orderBy('codigo')
                    ->first();
        }

        // Si la var. $direction muestra que el cursor se mueve (+1)
        if ($direction == 1) {
            $legajo = Tal001::where('codigo', '>', $nrolegajo)
                ->Where('codigo', '>', 0)
                ->orderBy('codigo')
                ->first();

            if ($legajo == null)
                $legajo = Tal001::latest('id')
                    ->where('codigo', '>', 0)
                    ->first();
        }


        // Si la var. $direction muestra que el cursor se mueve al final
        if ($direction == 9) {
            $legajo = Tal001::latest('codigo')
                ->where('codigo', '>', 0)
                ->first();
        }

        $now = Carbon::now();

        // Combos de tablas anexas
        $clientes = Vta001::orderBy('codigo')->get();
        $bancos = Fza002::orderBy('detalle')->get();

        return view('vehiculos')->with(compact(
            'empresa',
            'legajo',
            'agregar',
            'edicion',
            'active',
            'clientes'
        ));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $clientes = [];
        
        $legajo = new Tal001;      // find($id);     // dd($legajo);

        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $agregar = True;
        $active = 3;

        $legajo->foto = '/img/personal/none.png';
        
        $clientes = Vta001::orderBy('codigo')->get();
        $bancos = Fza002::orderBy('detalle')->get();

        /* if ($legajo != null) {
            $familiares = Sue002::orderBy('paren')->Where('legajo', '=', $legajo->codigo)->get();
        } else {
            $familiares = new Sue002;
        } */

        return view('vehiculos')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'active',
            'clientes',
            'bancos'
        ));
    }


    public function store(Request $request)
    {
        // Validaciones
        $messages = [
            'codigo.required' => 'El Dominio/Patente del vehiculo es obligatorio',
            'codigo.unique' => 'El Dominio/Patente del vehiculo ya existe',
            'detalle.required' => 'El vehiculo es obligatorio',
            'detalle.min' => 'El vehiculo debe tener más de 2 letras'
        ];

        $rules = [
            'codigo' => 'required|unique:vta001s',
            'detalle' => 'required|min:2'
        ];

        $this->validate($request, $rules, $messages);

        $legajo = new Tal001();
        //$request->all();
        //$legajo = Tal001::create($request->all()); // massives assignments : all() -> onLy() // only('name','description')

        $legajo->cliente = $request->input('cliente'); 
        $legajo->codigo = $request->input('codigo');
        $legajo->detalle = $request->input('detalle');
        $legajo->modelo = $request->input('modelo');
        $legajo->anio = $request->input('anio');
        $legajo->motor = $request->input('motor');
        $legajo->chasis = $request->input('chasis');

        $legajo->acop_det = $request->input('acop_det');
        $legajo->acop_dom = $request->input('acop_dom');
        
        $legajo->save();   // INSERT INTO - SQL

        if ($legajo->codigo > 0)
            return redirect('/vehiculos');
    }


    public function edit($id = 0)
    {
        if ($id == 0) {
            return redirect('/vehiculos');
        }

        $clientes = [];

        $legajo = Tal001::find($id);
        if ($legajo == null) {
            return redirect('/vehiculos');
        }

        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 3;

        /* $legajo->fecha_naci = Carbon::parse($legajo->fecha_naci)->format('d/m/Y');
        $legajo->alta = Carbon::parse($legajo->alta)->format('d/m/Y');
        $legajo->fecha_vto = Carbon::parse($legajo->fecha_vto)->format('d/m/Y');
        $legajo->ultima_act = Carbon::parse($legajo->ultima_act)->format('d/m/Y'); */

        /* $foto = Sue111::orderBy('legajo')
            ->where('legajo', '=', $legajo->codigo)
            ->where('concepto', '=', '1.4')
            ->first();

        if ($foto != null) {
            $legajo->foto = $foto->nom_arch;
        } else {
            $legajo->foto = '/img/personal/none.png';
        } */

        /* $localidades = Sue019::orderBy('codigo')->get();
        $sectores   = Sue011::orderBy('detalle')->whereNotNull('codigo')->get();
        $ccostos    = Sue030::orderBy('detalle')->get();
        $jerarquias = Sue014::orderBy('detalle')->get();
        $categorias = Sue006::orderBy('detalle')->get();
        $cuadrillas = Sue054::orderBy('detalle')->get();
        $obras      = Sue009::orderBy('detalle')->get();
        $sindicatos = Sue015::orderBy('detalle')->get();
        $convenios  = Sue007::orderBy('detalle')->get();
        $contratos  = Sue107::orderBy('detalle')->get();
        $horarios   = Sue094::orderBy('detalle')->get();
        $actividades = Sicoss01::orderBy('codigo')->paginate(6);
        $condiciones = Sicoss05::orderBy('codigo')->paginate(6);
        $contrataciones = Sicoss08::orderBy('codigo')->paginate(6);
        $situaciones = Sicoss12::orderBy('codigo')->paginate(6);
        $obras2 = SicossObras::orderBy('codigo')->paginate(6);
        $sinie = SicossSinie::orderBy('codigo')->paginate(6);
        $zonas = SicossZona::orderBy('codigo')->paginate(6);
        $provincias = Sue012::orderBy('id')->where('codigo','!=','')->paginate(8);
        $capacidades = Sue052::orderBy('id')->paginate(8); */
        $clientes = Vta001::orderBy('codigo')->get();
        $bancos = Fza002::orderBy('detalle')->get();

        /* if ($legajo != null) {
            $familiares = Sue002::orderBy('paren')->Where('legajo', '=', $legajo->codigo)->get();
        } else {
            $familiares = new Sue002;
        } */

        return view('vehiculos')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'active',
            'clientes',
            'bancos'
        ));    // Abrir form de modificacion
    }


    public function update(Request $request, $id)
    {
        // Validaciones
        $messages = [
            'detalle.required' => 'El tipo de vehiculo es obligatorio',
            'detalle.min' => 'El tipo de vehiculo debe tener más de 2 letras'
        ];

        $rules = [
            'detalle' => 'required|min:2'
        ];

        // Validacion de campos
        $this->validate($request, $rules, $messages);

        // Grabar en bbdd los cambios del form de alta
        // dd($request->all());
        $legajo = Tal001::find($id);
        
        $legajo->cliente = $request->input('cliente');
        $legajo->detalle = $request->input('detalle');
        $legajo->modelo = $request->input('modelo');
        $legajo->anio = $request->input('anio');
        $legajo->motor = $request->input('motor');
        $legajo->chasis = $request->input('chasis');
        $legajo->acop_det = $request->input('acop_det');
        $legajo->acop_dom = $request->input('acop_dom');
        
        $legajo->update($request->only('detalle', 'cliente', 'modelo', 'anio', 'motor', 'chasis', 'acop_det', 'acop_dom' ));

        // dd($legajo->cod_centro);

        return redirect('/vehiculos/' . $id);
    }


    public function delete($id)
    {
        $legajo = Tal001::find($id);
        if ($legajo == null) {
            return "{\"result\":\"cancel\",\"id\":\"$legajo->id\"}";
        }

        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 3;

        $images = null;

        return "{\"result\":\"ok\",\"id\":\"$legajo->id\",\"codigo\":\"$legajo->codigo\",\"detalle\":\"$legajo->detalle\",\"nombres\":\"$legajo->nombres\",\"baja\":\"$legajo->baja\"}";
        //return redirect("/home/");
    }

    public function baja(Request $request, $id = null)
    {
        // return "Mostrar form de edit $id";
        $legajo = Tal001::find($id);
        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 3;

        $images = null;

        // Agrego el legajo por dar de baja en Sue070
        $legajoBaja = new Sue070;
        $legajoBaja->cliente = $legajo->cliente;
        $legajoBaja->codigo = $legajo->codigo;
        $legajoBaja->detalle = $legajo->detalle;
        $legajoBaja->modelo = $legajo->modelo;
        $legajoBaja->anio = $legajo->anio;
        $legajoBaja->motor = $legajo->motor;
        $legajoBaja->motor = $legajo->motor;
        $legajoBaja->chasis = $legajo->cuenta;
        $legajoBaja->acop_det = $legajo->acop_det;
        $legajoBaja->acop_dom = $legajo->cbu;
        
        try {
            $legajoBaja->save();
        } catch (\Exception $e) {
            //throw $th;
            //$legajoBaja->save();
        }

        // Doy de Baa de activos

        // return "{\"result\":\"ok\",\"id\":\"$legajo->id\"}";
        return redirect("/vehiculos/");
    }


    public function search(Request $request)
    {
        $active = 3;
        //$legajos = Tal001::paginate(5);
        $legajos = Tal001::name($request->get('name'))
            ->where('codigo', '!=', null)
            ->orderBy('codigo')
            ->paginate(10);

        //dd($legajos);

        $name = $request->get('name');

        return view('search')->with(compact('legajos', 'active', 'name'));
    }
}
