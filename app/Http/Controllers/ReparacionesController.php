<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Datoempr;
use App\Models\Tal007;
use App\Models\Fza002;
use Carbon\Carbon;

class ReparacionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index($id = null, $direction = null)
    {
        $bancos = [];
        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 1;

        // Si no tiene permisos re-dirigo a carga de novedades
        if (auth()->user()->rol == "CARGA-TARJA-INFORMES") {
            $active = 4;

            $sector = Sue011::orderBy('detalle')
                ->whereNotNull('codigo')
                ->join('roles_sectores', function ($join) {
                    $join->on('roles_sectores.codsector', '=', 'sue011s.codigo')
                        ->where('user', auth()->user()->name);
                    })
                ->first();
            
            if ($sector != null) {
                return redirect('/reparaciones/');
            }

            return redirect('/reparaciones');
        } elseif (auth()->user()->rol == "TARJAS-INFORMES") {
            return redirect('/reparaciones');
        }


        //-----------------------------

        $nrolegajo = 0;

        if ($id == null) {
            $legajo = Tal007::Where('codigo', '!=', '')
                ->orderBy('codigo')
                ->first();      // find($id);

            //dd($legajo);

            if ($legajo != null) {
                $id = $legajo->id;
                $nrolegajo = $legajo->codigo;
            }
        } else {
            $legajo = Tal007::find($id);
            if ($legajo == null) {
                $legajo = Tal007::Where('codigo', '!=', '')
                    ->orderBy('codigo')
                    ->first();      // first(); // find($id);     // dd($legajo);   // $legajo = new Tal007;
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
            $legajo = new Tal007;

        // Si la var. $direction muestra que el cursor s    e mueve (-1)
        if ($direction == -1) {
            $legajo = Tal007::where('codigo', '<', $nrolegajo)
                ->Where('codigo', '!=', '')
                ->orderBy('codigo', 'desc')
                ->first();

            if ($legajo == null)
                $legajo = Tal007::Where('codigo', '!=', '')
                    ->orderBy('codigo')
                    ->first();
        }

        // Si la var. $direction muestra que el cursor se mueve (+1)
        if ($direction == 1) {
            $legajo = Tal007::where('codigo', '>', $nrolegajo)
                ->Where('codigo', '!=', '')
                ->WhereNull('baja')
                ->orderBy('codigo')
                ->first();

            if ($legajo == null)
                $legajo = Tal007::latest('id')
                    ->where('codigo', '!=', '')
                    ->first();
        }


        // Si la var. $direction muestra que el cursor se mueve al final
        if ($direction == 9) {
            $legajo = Tal007::latest('codigo')
                ->where('codigo', '!=', '')
                ->first();
        }

        $now = Carbon::now();

        // Combos de tablas anexas
        $bancos = Fza002::orderBy('detalle')->get();


        return view('reparaciones')->with(compact(
            'empresa',
            'legajo',
            'agregar',
            'edicion',
            'active',
            'bancos'
        ));
    }

    public function add()
    {
        $bancos = [];
        
        $legajo = new Tal007;      // find($id);     // dd($legajo);

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

        return view('reparaciones')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'active',
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
            'detalle.min' => 'La razón social debe tener más de 2 letras'
        ];

        $rules = [
            'codigo' => 'required|unique:tal007s',
            'detalle' => 'required|min:2'
        ];

        $this->validate($request, $rules, $messages);

        $legajo = new Tal007();
        //$request->all();
        //$legajo = Tal007::create($request->all()); // massives assignments : all() -> onLy() // only('name','description')

        $legajo->codigo = $request->input('codigo');
        $legajo->detalle = $request->input('detalle');

        $legajo->save();   // INSERT INTO - SQL

        if ($legajo->codigo > 0)
            return redirect('/reparaciones');
    }


    public function edit($id = 0)
    {
        if ($id == 0) {
            return redirect('/reparaciones');
        }

        $bancos = [];
        
        $legajo = Tal007::find($id);
        if ($legajo == null) {
            return redirect('/reparaciones');
        }

        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 1;

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

        return view('reparaciones')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'active',
            'bancos'
        ));    // Abrir form de modificacion
    }


    public function update(Request $request, $id)
    {
        // Validaciones
        $messages = [
            'detalle.required' => 'La Razon social es obligatoria',
            'detalle.min' => 'La Razon social debe tener más de 2 letras'
        ];

        $rules = [
            'detalle' => 'required|min:2'
        ];

        // Validacion de campos
        $this->validate($request, $rules, $messages);

        // Grabar en bbdd los cambios del form de alta
        // dd($request->all());
        $legajo = Tal007::find($id);

        $legajo->detalle = $request->input('detalle');
        $legajo->update($request->only('detalle'));

        // dd($legajo->cod_centro);

        return redirect('/reparaciones/' . $id);
    }


    public function delete($id)
    {
        $legajo = Tal007::find($id);
        if ($legajo == null) {
            return "{\"result\":\"cancel\",\"id\":\"$legajo->id\"}";
        }

        $legajo->baja = Carbon::parse($legajo->baja)->format('d/m/Y');

        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 17;

        $images = null;

        return "{\"result\":\"ok\",\"id\":\"$legajo->id\",\"codigo\":\"$legajo->codigo\",\"detalle\":\"$legajo->detalle\",\"nombres\":\"$legajo->nombres\",\"baja\":\"$legajo->baja\"}";
        //return redirect("/..../");
    }

    public function baja(Request $request, $id = null)
    {
        // return "Mostrar form de edit $id";
        $legajo = Tal007::find($id);
        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 17;

        $images = null;

        // Agrego el legajo por dar de baja en Sue070
        $legajoBaja = new Sue070;
        $legajoBaja->codigo = $legajo->codigo;
        $legajoBaja->detalle = $legajo->detalle;
        $legajoBaja->nombres = $legajo->nombres;
        $legajoBaja->cuit = $legajo->cuit;
        $legajoBaja->domic = $legajo->domic;
        $legajoBaja->localid = $legajo->localid;
        $legajoBaja->codpostal = $legajo->codpostal;
        $legajoBaja->tel1 = $legajo->tel1;
        $legajoBaja->tel2 = $legajo->tel2;
        $legajoBaja->tel3 = $legajo->tel3;
        $legajoBaja->forma_pago = $legajo->forma_pago;
        $legajoBaja->formap = $legajo->formap;
        $legajoBaja->banco = $legajo->banco;
        $legajoBaja->sucursal = $legajo->sucursal;
        $legajoBaja->cuenta = $legajo->cuenta;
        $legajoBaja->nuevo = $legajo->nuevo;
        $legajoBaja->error = $legajo->error;
        $legajoBaja->email = $legajo->email;
        $legajoBaja->web = $legajo->web;
        $legajoBaja->foto = $legajo->foto;
        $legajoBaja->foto_carn = $legajo->foto_carn;
        $legajoBaja->barrio = $legajo->barrio;
        $legajoBaja->mod_cto = $legajo->mod_cto;
        $legajoBaja->grupo_emp = $legajo->grupo_emp;
        $legajoBaja->cbu = $legajo->cbu;
        
        try {
            $legajoBaja->save();
        } catch (\Exception $e) {
            //throw $th;
            //$legajoBaja->save();
        }

        // Doy de Baa de activos

        if ($request->input('fec_baja') != null) {
            $baja = str_replace('/', '-', $request->input('fec_baja'));
            $legajo->baja = Carbon::createFromFormat("d-m-Y", $baja);
            $legajo->save();
        }

        // return "{\"result\":\"ok\",\"id\":\"$legajo->id\"}";
        return redirect("/reparaciones/");
    }


    public function search(Request $request)
    {
        $active = 1;
        //$legajos = Tal007::paginate(5);
        $legajos = Tal007::name($request->get('name'))
            ->where('codigo', '!=', null)
            ->WhereNull('baja')
            ->orderBy('codigo')
            ->paginate(10);

        //dd($legajos);

        $name = $request->get('name');

        return view('search')->with(compact('legajos', 'active', 'name'));
    }
}
