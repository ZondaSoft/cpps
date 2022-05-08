<?php

namespace App\Http\Controllers;
use App\Models\Datoempr;
use App\Models\Cpa010;
use App\Models\Fza002;
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
        $provincias = [];
        $localidades = [];
        $ccostos = [];
        $jerarquias = [];
        $categorias = [];
        $sectores = [];
        $cuadrillas = [];
        $obras = [];
        $sindicatos = [];
        $convenios = [];
        $contratos = [];
        $capacidades = [];
        $horarios = [];
        $actividades = [];
        $condiciones = [];
        $contrataciones = [];
        $situaciones = [];
        $obras2 = [];
        $zonas = [];
        $familiares = [];
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 1;

        // Si no tiene permisos re-dirigo a carga de novedades
        if (auth()->user()->rol == "CARGA-TARJA-INFORMES") {
            $active = 26;

            $sector = Sue011::orderBy('detalle')
                ->whereNotNull('codigo')
                ->join('roles_sectores', function ($join) {
                    $join->on('roles_sectores.codsector', '=', 'sue011s.codigo')
                        ->where('user', auth()->user()->name);
                    })
                ->first();
            
            if ($sector != null) {
                return redirect('/novedadeslist/?codsector=' . $sector->codigo);
            }

            return redirect('/infpresentismo');
        } elseif (auth()->user()->rol == "TARJAS-INFORMES") {
            return redirect('/infpresentismo');
        }


        //-----------------------------

        $nrolegajo = 0;

        if ($id == null) {
            $legajo = Cpa001::Where('codigo', '>', 0)
                ->orderBy('codigo')
                ->first();      // find($id);

            //dd($legajo);

            if ($legajo != null) {
                $id = $legajo->id;
                $nrolegajo = $legajo->codigo;
            }
        } else {
            $legajo = Cpa001::find($id);
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

        $now = Carbon::now();

        // Combos de tablas anexas
        $bancos = Fza002::orderBy('detalle')->get();


        return view('conceptos.index')->with(compact(
            'empresa',
            'legajo',
            'agregar',
            'edicion',
            'active',
            'bancos',
            'provincias',
            'localidades',
            'ccostos',
            'jerarquias',
            'categorias',
            'sectores',
            'cuadrillas',
            'obras',
            'sindicatos',
            'convenios',
            'contratos',
            'capacidades',
            'horarios',
            'actividades',
            'condiciones',
            'contrataciones',
            'situaciones',
            'obras2',
            'zonas',
            'familiares'
        ));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $provincias = [];
        $localidades = [];
        $ccostos = [];
        $jerarquias = [];
        $categorias = [];
        $sectores = [];
        $cuadrillas = [];
        $obras = [];
        $sindicatos = [];
        $convenios = [];
        $contratos = [];
        $capacidades = [];
        $horarios = [];
        $actividades = [];
        $condiciones = [];
        $contrataciones = [];
        $situaciones = [];
        $obras2 = [];
        $zonas = [];
        $familiares = [];
        $sinie = [];
        
        $legajo = new Cpa010;      // find($id);     // dd($legajo);

        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $agregar = True;
        $active = 1;

        $legajo->foto = '/img/personal/none.png';
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
        $bancos = Fza002::orderBy('detalle')->get();

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
            'localidades',
            'sectores',
            'ccostos',
            'jerarquias',
            'categorias',
            'cuadrillas',
            'obras',
            'sindicatos',
            'convenios',
            'contratos',
            'horarios',
            'familiares',
            'actividades',
            'condiciones',
            'contrataciones',
            'situaciones',
            'obras2',
            'sinie',
            'zonas',
            'provincias',
            'capacidades',
            'bancos'
        ));
    }


    public function store(Request $request)
    {
        // Validaciones
        $messages = [
            'codigo.required' => 'El Código del cliente es obligatorio',
            'codigo.unique' => 'El Código del cliente ya existe',
            'detalle.required' => 'La razón social es obligatorio',
            'detalle.min' => 'La razón social debe tener más de 2 letras',
            'nom_com.required' => 'El nombre de fantasia es obligatorios'
        ];

        $rules = [
            'codigo' => 'required|unique:vta001s',
            'detalle' => 'required|min:2',
            'nom_com' => 'required'
        ];

        $this->validate($request, $rules, $messages);

        $legajo = new Cpa010();
        //$request->all();
        //$legajo = Cpa010::create($request->all()); // massives assignments : all() -> onLy() // only('name','description')

        $legajo->codigo = $request->input('codigo');
        $legajo->detalle = $request->input('detalle');
        $legajo->nom_com = $request->input('nom_com');
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
        
        // Pestaña forma de pago
        $legajo->formap = $request->input('formap');
        $legajo->banco = $request->input('banco');
        $legajo->sucursal = $request->input('sucursal');
        $legajo->cuenta = $request->input('cuenta');
        $legajo->cbu = $request->input('cbu');

        $legajo->save();   // INSERT INTO - SQL

        if ($legajo->codigo > 0)
            return redirect('/home');
    }


    public function edit($id = 0)
    {
        if ($id == 0) {
            return redirect('/home');
        }

        $provincias = [];
        $localidades = [];
        $ccostos = [];
        $jerarquias = [];
        $categorias = [];
        $sectores = [];
        $cuadrillas = [];
        $obras = [];
        $sindicatos = [];
        $convenios = [];
        $contratos = [];
        $capacidades = [];
        $horarios = [];
        $actividades = [];
        $condiciones = [];
        $contrataciones = [];
        $situaciones = [];
        $obras2 = [];
        $zonas = [];
        $familiares = [];
        $sinie = [];

        $legajo = Cpa010::find($id);
        if ($legajo == null) {
            return redirect('/home');
        }

        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 1;

        $legajo->fecha_naci = Carbon::parse($legajo->fecha_naci)->format('d/m/Y');
        $legajo->alta = Carbon::parse($legajo->alta)->format('d/m/Y');
        $legajo->fecha_vto = Carbon::parse($legajo->fecha_vto)->format('d/m/Y');
        $legajo->ultima_act = Carbon::parse($legajo->ultima_act)->format('d/m/Y');

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
        $bancos = Fza002::orderBy('detalle')->get();

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
            'localidades',
            'sectores',
            'ccostos',
            'jerarquias',
            'categorias',
            'cuadrillas',
            'obras',
            'sindicatos',
            'convenios',
            'contratos',
            'horarios',
            'familiares',
            'actividades',
            'condiciones',
            'contrataciones',
            'situaciones',
            'obras2',
            'sinie',
            'zonas',
            'provincias',
            'capacidades',
            'bancos'
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

        return redirect('/home/' . $id);
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

        $images = null;

        return "{\"result\":\"ok\",\"id\":\"$legajo->id\",\"codigo\":\"$legajo->codigo\",\"detalle\":\"$legajo->detalle\",\"nombres\":\"$legajo->nombres\"}";
        //return redirect("/home/");
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
        return redirect("/home/");
    }


    public function search(Request $request)
    {
        $active = 1;
        //$legajos = Cpa010::paginate(5);
        $legajos = Cpa010::name($request->get('name'))
            ->where('codigo', '!=', null)
            ->orderBy('codigo')
            ->paginate(10);

        //dd($legajos);

        $name = $request->get('name');

        return view('conceptos.search')->with(compact('legajos', 'active', 'name'));
    }
}
