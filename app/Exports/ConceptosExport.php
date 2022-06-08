<?php

namespace App\Exports;

use App\Models\Fza030;
use DB;
//use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ConceptosExport implements FromQuery, WithHeadings, WithEvents    //FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Fza030::all();
    // }



    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Fza030::all();
    // }
    
    use Exportable;

    public function __construct($desde, $hasta, $concepto1, $concepto2)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->concepto1 = $concepto1;
        $this->concepto2 = $concepto2;
    }


    public function query()
    {
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
            ->select(DB::raw('fza030s.tipo,fza030s.numero,DATE_FORMAT(fecha, "%d/%m/%Y") AS fecha,fza030s.cuenta,fza030s.concepto,cpa010s.detalle as concepto_detalle,fza030s.comentarios,fza030s.importe'))
            ->whereBetween('fecha', [$this->desde, $this->hasta])
            ->whereBetween('concepto', [$this->concepto1, $this->concepto2])
            ->join('cpa010s', function ($join) {
                $join->on('fza030s.concepto', '=', 'cpa010s.codigo');
                })
            ->orderBy('fecha','asc')
            ->orderBy('numero','asc');

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
            'Detalle Concepto',
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
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(35);  // Detalle concept
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(55);  // Comentarios
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(20);  // Importe
                $event->sheet->getDelegate()->setTitle('Conceptos-fecha');
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
