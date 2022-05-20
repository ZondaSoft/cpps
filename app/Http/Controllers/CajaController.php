<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datoempr;
use App\Models\Vta001;
use App\Models\Fza002;
use App\Models\Fza030;  // Movimientos
use App\Models\Fza020;  // Head de movimientos
use App\Models\Cpa010;  // Conceptos
use Carbon\Carbon;

class CajaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $id = null, $direction = null)
    {
        $legajoNew = new Cpa010;
        $legajoNew->fecha = Carbon::Now()->format('d/m/Y');
        $legajoNew->hasta = Carbon::Now()->format('d/m/Y');
        $legajoNew->fecha_sin = Carbon::Now()->format('d/m/Y');
        $legajoNew->dias = 1;
        $periodo = null;
        $codsector = null;
        $cod_nov = null;
        $cerrada = false;
        $fecha = null;
        $legajo = null;
        $novedad = null;
        $legajoReadOnly = '';
        $agregar = true;
        $edicion = true;
        $active = 1;
        $anterior = 0;
        $cuenta = 0;
        $id_caja = 0;

        //------------------------------------------------------------------------
        // Controlo si hay caja abierta y voy a ella sino voy a apertura de caja
        //------------------------------------------------------------------------
        $cajaAbierta = Fza020::WhereNull('cerrada')->first();

        if ($cajaAbierta == null) {
            // Busco ultima caja cerrada
            $ultimaCaja = Fza020::orderBy('id','asc')->first();

            // No hay cajas anteriores ? ABRO 1ER CAJA
            if ($ultimaCaja == null) {
                $legajo = new Fza020;
                $legajo->id = 1;
                $id_caja = 1;
                $legajo->fecha = Carbon::Now()->format('d/m/Y');
                $legajo->apertura = 0.00;
                $legajo->cierre = 0.00;

                return view('caja-apertura')->with(compact('novedad','id_caja','fecha','cuenta',
                    'legajoNew','legajo','agregar','edicion','active'));            
            }
    
        } else {
            $id_caja = $cajaAbierta->id;
            $fecha = $cajaAbierta->fecha;
        }


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
        $novedades = Fza030::search($codsector, $cod_nov, $fecha, $order)
            ->orderBy('fecha','asc')
            ->orderBy('id','asc')
            ->paginate(9)
            ->appends(request()->query());
        
        //->orderBy('fecha','asc')

        $id_crud = 2;

        // Combos de tablas anexas              //$legajos   = Vta001::orderBy('codigo')->Where('codigo','>',0)->get();
        $legajos   = Vta001::select('vta001s.codigo', 'vta001s.detalle', 'nom_com')
            ->orderBy('detalle')
            ->Where('vta001s.codigo','>',0)
            ->get();
        
        // Filtro de sectores segun perfil del usuario
        if (auth()->user()->rol == "ADMINISTRADOR" ) {
            //$sectores  = Sue011::orderBy('detalle')->whereNotNull('codigo')->get();
        } elseif (auth()->user()->rol == "CARGA-TARJA-INFORMES") {
            /* $sectores  = Sue011::orderBy('detalle')
                ->whereNotNull('codigo')
                ->join('roles_sectores', function ($join) {
                    $join->on('roles_sectores.codsector', '=', 'sue011s.codigo')
                        ->where('user', auth()->user()->name);
                    })
                ->get(); */
        } else {
            // "TARJAS-INFORMES"
            //$sectores  = Sue011::orderBy('detalle')->whereNotNull('codigo')->get();
        }

        // Busco ultima caja abierta
        $apertura = Fza020::whereNull('cerrada')->first();
        if ($apertura == null) {
            // Si no hay cajas abierta vaya a la ultima usada aunque este cerrada
            $apertura = Fza020::orderBy('id', 'asc')->first();

            if ($apertura != null) {
                
                $id_caja = $apertura->id;
                $fecha = $apertura->fecha;
                $cerrada = true;

            } else {
                // Si todo falla redirijo a apertura inicial
                $legajo = new Fza020;
                $id_caja = $legajo->id;
                $legajo->fecha = Carbon::Now()->format('d/m/Y');
                $legajo->apertura = 0.00;
                $legajo->cierre = 0.00;

                return view('caja-apertura')->with(compact('novedad','id_caja','fecha','cuenta',
                    'legajoNew','legajo','agregar','edicion','active'));  
            }
        }

        return view('ordenes')->with(compact('novedad','apertura','id_caja','cod_nov','fecha','legajo',
            'legajoNew','agregar','edicion','active','novedades','legajos', 'order','id_crud', 'legajoReadOnly','fecha5','id_caja','cerrada'));
    }


    
    public function preapertura()
    {
        //------------------------------------------------------------------------
        // Controlo si hay caja abierta y rechazo en caso de que exista
        //------------------------------------------------------------------------
        $agregar = true;
        $edicion = false;
        $active = true;
        $cerrada = false;
        $legajoNew = null;
        $cuenta = null;
        $fecha = null;
        $novedad = null;
        $cajaAbierta = Fza020::WhereNull('cerrada')->first();

        if ($cajaAbierta == null) {
            // Busco ultima caja cerrada
            $ultimaCaja = Fza020::orderBy('id','asc')->first();

            // No hay cajas anteriores ? ABRO 1ER CAJA
            if ($ultimaCaja != null) {
                $legajo = new Fza020;
                $legajo->id = 1;
                $id_caja = 1;
                $legajo->fecha = Carbon::Now()->format('d/m/Y');
                $legajo->apertura = $ultimaCaja->cierre;
                $legajo->cierre = $ultimaCaja->cierre;

                $fecha = $legajo->fecha;

                return view('caja-apertura')->with(compact('novedad','id_caja','fecha','cuenta',
                    'legajoNew','legajo','agregar','edicion','active','cerrada'));            
            }

        } else {
            $id_caja = $cajaAbierta->id;
            $fecha = $cajaAbierta->fecha;
        }
    }


    public function add()
    {
        $fechaActual = null;
        $id_caja = 0;
        $fecha = null;
        $cerrada = false;

        // 1ro buscamos la apertura de la caja actual
        $apertura = Fza020::whereNull('cerrada')->first();

        // Si no hay aperturas redirijo a apertura
        if ($apertura == null) {
            $legajo = new Fza020;
            $legajo->fecha = Carbon::Now()->format('d/m/Y');
            $legajo->apertura = 0.00;
            $legajo->cierre = 0.00;

            return view('caja-apertura')->with(compact('novedad','fecha','cuenta',
                'legajoNew','legajo','agregar','edicion','active'));
        } else {

            $fechaActual = $apertura->fecha;
            $fecha = $apertura->fecha;
            $id_caja = $apertura->id;
        
        }

        $legajo = new Fza030;      // find($id);     // dd($legajo);
        $legajo->numero = '';
        if ($fechaActual != null) {
            $legajo->fecha = Carbon::parse($fechaActual)->format('d/m/Y');
        }
        $legajo->id_caja = $id_caja;

        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Modificar, Borrar
        $agregar = True;
        $active = 1;

        $conceptos = Cpa010::orderBy('detalle')->get();
        
        /* if ($legajo != null) {
            $familiares = Sue002::orderBy('paren')->Where('legajo', '=', $legajo->codigo)->get();
        } else {
            $familiares = new Sue002;
        } */

        return view('orden-add')->with(compact(
            'legajo',
            'conceptos',
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
            'fecha2.required' => 'La fecha del comprobante es obligatoria',
            'concepto.required' => 'El concepto es obligatorio.',
            'concepto.string' => 'El concepto es obligatorio..',
            'concepto.numeric' => 'El concepto es obligatorio...',
            'importe.required' => 'El importe es obligatorio'
        ];

        $rules = [
            'fecha2' => 'required',
            'concepto' => 'required|min:1',
            'importe' => 'required'
        ];

        $this->validate($request, $rules, $messages);

        $comprobante = new Fza030();
        //$request->all();
        //$comprobante = Cpa010::create($request->all()); // massives assignments : all() -> onLy() // only('name','description')

        $comprobante->cuenta = $request->input('cuenta');
        $comprobante->concepto = $request->input('concepto');
        $comprobante->tipo = $request->input('tipo');
        $comprobante->numero = $request->input('numero');
        $fecha = str_replace('/', '-', $request->input('fecha2'));
        $comprobante->fecha = Carbon::createFromFormat("d-m-Y", $fecha);
        $comprobante->importe = $request->input('importe');
        $comprobante->comentarios = $request->input('comentarios');
        $comprobante->id_caja = $request->input('id_caja');

        $comprobante->save();   // INSERT INTO - SQL

        return redirect('/home/')->with('success', 'El comprobante fue creado con éxito');
    }


    public function edit($id = 0)
    {
        if ($id == 0) {
            return redirect('/home');
        }

        $fecha = null;
        $id_caja = 0;
        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 1;
        $cerrada = false;

        // 1ro buscamos la apertura de la caja actual
        $apertura = Fza020::whereNull('cerrada')->first();

        // Si no hay aperturas redirijo a apertura
        if ($apertura == null) {
            $legajo = new Fza020;
            $legajo->fecha = Carbon::Now()->format('d/m/Y');
            $legajo->apertura = 0.00;
            $legajo->cierre = 0.00;

            return view('caja-apertura')->with(compact('novedad','fecha','cuenta',
                'legajoNew','legajo','agregar','edicion','active'));
        } else {

            $fechaActual = $apertura->fecha;
            $fecha = $apertura->fecha;
            $id_caja = $apertura->id;
        
        }
        
        $legajo = Fza030::find($id);
        if ($legajo == null) {
            return redirect('/home');
        }

        

        $legajo->fecha = Carbon::parse($legajo->fecha)->format('d/m/Y');
        $conceptos = Cpa010::orderBy('detalle')->get();

        return view('orden-add')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'active',
            'fecha',
            'id_caja',
            'conceptos',
            'cerrada'
        ));    // Abrir form de modificacion
    }


    public function update(Request $request, $id)
    {
        // Validaciones
        $messages = [
            'concepto.required' => 'La Razon social es obligatoria',
            'concepto.min' => 'La Razon social debe tener más de 2 letras',
            'importe.required' => 'El importe es obligatorio'
        ];

        $rules = [
            'concepto' => 'required|min:2',
            'importe' => 'required'
        ];

        // Validacion de campos
        $this->validate($request, $rules, $messages);

        // Grabar en bbdd los cambios del form de alta
        // dd($request->all());
        $legajo = Fza030::find($id);

        $legajo->concepto = $request->input('concepto');
        $legajo->tipo = $request->input('tipo');
        $legajo->importe = $request->input('importe');
        $legajo->comentarios = $request->input('comentarios');

        $legajo->update($request->only('concepto', 'tipo', 'importe', 'comentarios'));

        // dd($legajo->cod_centro);

        return redirect('/home/' . $id);
    }


    public function delete($id)
    {
        $legajo = Fza030::find($id);
        if ($legajo == null) {
            return "{\"result\":\"cancel\",\"id\":\"$legajo->id\"}";
        }

        $fecha = null;
        $id_caja = 0;
        $agregar = False;
        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 1;
        $cerrada = false;

        // 1ro buscamos la apertura de la caja actual
        $apertura = Fza020::whereNull('cerrada')->first();

        // Si no hay aperturas redirijo a apertura
        if ($apertura == null) {
            $legajo = new Fza020;
            $legajo->fecha = Carbon::Now()->format('d/m/Y');
            $legajo->apertura = 0.00;
            $legajo->cierre = 0.00;

            return view('caja-apertura')->with(compact('novedad','fecha','cuenta',
                'legajoNew','legajo','agregar','edicion','active'));
        } else {

            $fechaActual = $apertura->fecha;
            $fecha = $apertura->fecha;
            $id_caja = $apertura->id;
        
        }

        $legajo->fecha = Carbon::parse($legajo->fecha)->format('d/m/Y');
        $conceptos = Cpa010::orderBy('detalle')->get();

        return view('orden-delete')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'active',
            'fecha',
            'id_caja',
            'cerrada',
            'conceptos'
        ));    // Abrir form de modificacion
    }



    public function borrar($id)
    {
        $legajo = Fza030::find($id);
        if ($legajo == null) {
            return "{\"result\":\"cancel\",\"id\":\"$legajo->id\"}";
        }

        $legajo->delete();

        return redirect('/home/')->with('success', 'El comprobante fue creado con éxito');
    }


    public function apertura(Request $request)
    {
        // Validaciones
        $messages = [
            'fecha.required' => 'La fecha del comprobante es obligatoria',
            'fecha.unique' => 'La fecha ya existe',
            'apertura.required' => 'El importe es obligatorio'
        ];

        $rules = [
            'fecha' => 'required|unique:fza020s',
            'apertura' => 'required'
        ];

        $this->validate($request, $rules, $messages);

        $comprobante = new Fza020();
        //$request->all();
        //$comprobante = Cpa010::create($request->all()); // massives assignments : all() -> onLy() // only('name','description')

        $comprobante->id = $request->input('id');
        $fecha = str_replace('/', '-', $request->input('fecha'));
        $comprobante->fecha = Carbon::createFromFormat("d-m-Y", $fecha);
        $comprobante->apertura = $request->input('apertura');
        $comprobante->cierre = 0.00;
        $comprobante->comentarios = $request->input('comentarios');

        $comprobante->save();   // INSERT INTO - SQL

        return redirect('/home/')->with('success', 'Apertura éxitosa');
    }


    public function cerrar(Request $request, $id)
    {
        $fechaActual = null;
        $id_caja = 0;
        $importeEfectivo = 0;
        $rindeEfectivo = '';
        $saldoEfectivo = 0;
        $tarjetaCredito = 0;
        $tarjetaDebito = 0;
        $bancarios = 0;
        $cheques = 0;
        $fecha = null;
        $cerrada = false;

        // 1ro buscamos la apertura de la caja actual
        $apertura = Fza020::whereNull('cerrada')->first();

        // Si no hay aperturas redirijo a apertura
        if ($apertura == null) {
            $legajo = new Fza020;
            $legajo->fecha = Carbon::Now()->format('d/m/Y');
            $legajo->apertura = 0.00;
            $legajo->cierre = 0.00;

            return view('caja-apertura')->with(compact('novedad','fecha','cuenta',
                'legajoNew','legajo','agregar','edicion','active'));
        } else {

            $fechaActual = $apertura->fecha;
            $id_caja = $apertura->id;
            $importeEfectivo = $apertura->apertura;
            $saldoEfectivo = -$importeEfectivo;
            $apertura->fecha = Carbon::parse($apertura->fecha)->format('d/m/Y');
            
            $fecha = $apertura->fecha;
            $fechaActual = $fecha;
        }

        $legajo = Fza030::Where('id_caja', $id_caja)
            ->orderBy('id')
            ->get();
        
        foreach ($legajo as $comprobante) {
            if ($comprobante->cuenta == 0) {
                $importeEfectivo = $importeEfectivo - $comprobante->importe;
            }
            if ($comprobante->cuenta == 1) {
                $tarjetaCredito = $tarjetaCredito + $comprobante->importe;
            }
            if ($comprobante->cuenta == 2) {
                $tarjetaDebito = $tarjetaDebito + $comprobante->importe;
            }
            if ($comprobante->cuenta == 3) {
                $bancarios = $bancarios + $comprobante->importe;
            }
            if ($comprobante->cuenta == 4) {
                $saldoBancario = $bancarios + $comprobante->importe;
            }
            if ($comprobante->cuenta == 5) {
                $importeEfectivo = $importeEfectivo + $comprobante->importe;
            }
        }

        $edicion = True;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $agregar = True;
        $active = 1;

        return view('caja-cerrar')->with(compact(
            'legajo',
            'agregar',
            'edicion',
            'active',
            'cerrada',
            'id_caja',
            'fecha',
            'fechaActual',
            'importeEfectivo',
            'rindeEfectivo',
            'saldoEfectivo',
            'tarjetaCredito',
            'tarjetaDebito',
            'bancarios',
            'cheques'
        ));
    }



    public function close(Request $request, $id)
    {
        $fechaActual = null;
        $id_caja = 0;
        $importeEfectivo = 0;
        $rindeEfectivo = '';
        $tarjetaCredito = 0;
        $tarjetaDebito = 0;
        $bancarios = 0;
        $cheques = 0;
        $fecha = null;

        // 1ro buscamos la apertura de la caja actual
        $apertura = Fza020::where('id', $id)->first();

        // Si no hay aperturas redirijo a apertura
        if ($apertura == null) {
            return redirect('/home/')->with('success', 'El comprobante fue creado con éxito');

        } else {

            $fechaActual = $apertura->fecha;
            $id_caja = $apertura->id;
            $importeEfectivo = $apertura->apertura;
            $saldoEfectivo = -$importeEfectivo;
            //$apertura->fecha = Carbon::parse($apertura->fecha)->format('d/m/Y');
            $fecha = $apertura->fecha;
            $fechaActual = $fecha;
        }

        $apertura->cierre = $request->input('rinde');
        $apertura->tarjetadebito = $request->input('tarjetadebito2');
        $apertura->tarjetacredito = $request->input('tarjetacredito2');
        $apertura->bancarios = $request->input('bancarios2');
        $apertura->cheques = $request->input('cheques2');
        $apertura->cerrada = true;
        
        $apertura->update($request->only('cierre', 'tarjetaDebito', 'cerrada'));

        return redirect('/home/')->with('success', 'El comprobante fue creado con éxito');
    }




    public function search(Request $request)
    {
        $active = 1;
        $id_caja = 0;
        $cerrada = false;
        $fecha = null;

        //$legajos = Cpa010::paginate(5);
        $legajos = Fza020::name($request->get('name'))
            ->orderBy('id')
            ->paginate(10);

        //dd($legajos);

        $name = $request->get('name');

        return view('home.search')->with(compact('legajos', 'active', 'name', 'cerrada', 'id_caja', 'fecha'));
    }
}
