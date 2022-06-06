<?php

namespace App\Exports;

use App\Models\Fza020;
use App\Models\Fza030;
use DB;
//use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class CajaExport implements FromQuery, WithHeadings, WithEvents         //FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Fza030::all();
    // }
    
    use Exportable;

    public function __construct($id, $fecha, $estado)
    {
        $this->id = $id;
        $this->id_caja = $id;
        $this->fecha = $fecha;
        $this->estado = $estado;
    }


    public function query()
    {
        $id = $this->id;
        $id_caja = $this->id;

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
        
        $cajaAbierta = [];

        if ($id != null) {
            $id_caja = $id;

            $cajaAbierta = Fza020::Where('id', $id)->first();

            if ($cajaAbierta != null) {
                $id_caja = $cajaAbierta->id;
                $fecha = $cajaAbierta->fecha;
            } else {
                $cajaAbierta = Fza020::orderBy('id','asc')->first();

                if ($cajaAbierta != null) {
                    $id_caja = $cajaAbierta->id;
                    $fecha = $cajaAbierta->fecha;
                }
            }
        } else {
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
        }


        $agregar = False;
        $edicion = False;    // True: Muestra botones Grabar - Cancelar   //  False: Muestra botones: Agregar, Editar, Borrar
        $active = 26;
        $novedad = null;
        $order = null;
        $fecha_orig = null;
        $fecha5 = null;
        $consulta = null;
        
        // $consulta = DB::table('sue001s')
        //         ->select(DB::raw('codigo,CONCAT(detalle, " " , nombres) as ape_nom,cuil,alta,baja,domici,nro,piso,locali,cod_centro,codsector,cod_jerarq,cod_categ'))
        //         ->whereNotNull('codigo')
        //         ->whereNull('baja')
        //         ->whereBetween('codigo', [$this->desde, $this->fecha])
        //         ->orderBy('codigo');

        $consulta = DB::table('fza030s')
            ->select(DB::raw('tipo,numero,fecha,cuenta,concepto,comentarios,importe'))
            ->where('id_caja', $this->id_caja)
            ->orderBy('fecha');

        $creditos = Fza030::Where('id_caja', $id_caja)
            ->where('cuenta', 1)
            ->orderBy('fecha','asc')
            ->orderBy('id','asc')
            ->get();

        $debitos = Fza030::Where('id_caja', $id_caja)
            ->where('cuenta', 2)
            ->orderBy('fecha','asc')
            ->orderBy('id','asc')
            ->get();
        
        $bancos = Fza030::Where('id_caja', $id_caja)
            ->where('cuenta', 3)
            ->orWhere('cuenta', 4)
            ->orderBy('fecha','asc')
            ->orderBy('id','asc')
            ->get();

        
        $cheques = Fza030::Where('id_caja', $id_caja)
            ->where('cuenta', 6)
            ->orderBy('fecha','asc')
            ->orderBy('id','asc')
            ->get();

        return $consulta;
    }

    
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tipo',
            'Número',
            'Fecha',
            'Cuenta',
            'Concepto',
            'Comentarios',
            'importe'
        ];

        //'Médico',
    }



    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:M1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)
                      ->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)
                      ->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1:M1')
                      ->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A1:M1')
                      ->getBorders()->getTop()->setBorderStyle("medium");
                $event->sheet->getDelegate()->getStyle('A1:M1')
                      ->getBorders()->getBottom()->setBorderStyle("thin");
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(30);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(18);  // Numero
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(14);  // CUIL
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(12);  // Cuenta
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(12);  // Concepto
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(55);  // Comentarios
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(18);  // Importe
                $event->sheet->getDelegate()->setTitle('Caja diaria');
                //$event->sheet->getDelegate()->getCell('A1');
                //$event->sheet->getDelegate()->appendRow();
                //$event->sheet->getDelegate()->getcellCollection()->appendRow(2,array('appended', 'appended'));
                //dd($event->sheet->getDelegate()->getcellCollection());

                //dd($event->sheet->getDelegate());
                //$event->sheet->getDelegate()->appendRow(1,['aaa','b']);

                // PHPExcel_Style_Border::BORDER_THIN
                //dd($event->sheet->getDelegate()->getStyle('A1:K1')->getBorders()->getTop()->setBorderStyle("thin"));
            },
        ];
    }
}
