<?php

namespace App\Exports;

use App\Models\Cpps01;
use App\Models\Cpps14;
use App\Models\Cpps30;
use Carbon\Carbon;
use DB;
//use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterExport;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
//use Maatwebsite\Excel\Concerns\WithStyles;

class OrdenesExport implements FromQuery, WithHeadings, WithEvents, WithPreCalculateFormulas      //, WithStyles   //FromCollection
{
    use Exportable;

    public function __construct($periodo, $obra, $profesional1, $profesional2)
    {
        $this->periodo = $periodo;
        $this->obra = $obra;
        $this->profesional = $profesional1;
        $this->profesional2 = $profesional2;
    }

    public function query()
    {
        $periodo = $this->periodo;
        $profesional1 = $this->profesional;
        $profesional2 = $this->profesional2;
        $obra = $this->obra;

        $novedades = DB::table('cpps30s')
            ->select(DB::raw('cpps30s.mat_prov_cole as matricula,CONCAT(cpps01s.nom_ape) as ape_nom,cpps30s.ordennro,cpps30s.fecha,cpps30s.dni_afiliado,cpps30s.nom_afiliado,cpps30s.plan,cpps30s.cod_nomen,cpps09s.nom_prest,cpps30s.cantidad,cpps30s.precio,cpps30s.importe'))
            ->whereBetween('cpps01s.nom_ape', [$profesional1, $profesional2])
            ->whereBetween('periodo', [$periodo, $periodo])
            ->whereBetween('cod_os', [$obra, $obra])
            ->join('cpps01s', function ($join) {
            $join->on('cpps01s.mat_prov_cole', '=', 'cpps30s.mat_prov_cole');
                })
            ->join('cpps09s', function ($join) {
                    $join->on('cpps09s.cod_nemotecnico', '=', 'cpps30s.cod_nemotecnico');
                })
            ->join('cpps14s', function ($join) {
                $join->on('cpps14s.cod_nemotecnico', '=', 'cpps30s.cod_nemotecnico')->on('cpps14s.cod_convenio', '=', 'cpps30s.plan');
                })
            ->orderBy('cpps01s.nom_ape','asc')
            ->orderBy('cpps30s.ordennro','asc');
        
        // $novedades = Cpps30::where('periodo', $periodo)
        //     ->join('cpps01s', function ($join) {
        //             $join->on('cpps01s.mat_prov_cole', '=', 'cpps30s.mat_prov_cole');
        //         })
        //     ->join('cpps09s', function ($join) {
        //             $join->on('cpps09s.cod_nemotecnico', '=', 'cpps30s.cod_nemotecnico');
        //         })
        //     ->join('cpps14s', function ($join) {
        //         $join->on('cpps14s.cod_nemotecnico', '=', 'cpps30s.cod_nemotecnico')->on('cpps14s.cod_convenio', '=', 'cpps30s.plan');
        //         })
        //     ->select('cpps30s.*', 'cpps01s.mat_prov_cole', 'cpps01s.nom_ape', 'cpps09s.nom_prest')
        //     ->whereBetween('cpps01s.nom_ape', [$profesional1, $profesional2])
        //     ->orderBy('cpps01s.nom_ape','asc')
        //     ->orderBy('cpps30s.ordennro','asc')
        //     ->get();
        
        // dd($novedades);

        // temporalmente se saco la relacion con los Prestadores
        /*

        mdl003s.apellido as medico

        ->join('mdl003s', function ($join) {
            $join->on('mdl060s.prestador', '=', 'mdl003s.id');
            })

        */

        //return Mdl060::query()->whereYear('fecha', $this->year);
        return $novedades;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $nomEmpresa = 'CPPS';
        $subTitulo = '';

        $subTitulo = '- Detalle por profesional -';

        return [
                [$nomEmpresa],
                [''],
                ['Informe de Ordenes Emitidas'],
                [$subTitulo],
                ['Periodo : '. $this->periodo],
                [''],
                ['Matricula',
                'Apellido y Nombre',
                'N° Orden',
                'Fecha',
                'DNI Afiliado',
                'Afiliado',
                'Plan',
                'Cód.',
                'Practica',
                'Cantidad',
                'Precio',
                'Importe'],
            ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setName('Arial');
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setSize('8');
                
                $cellRange = 'A1';
                $event->sheet->getDelegate()->getStyle($cellRange)
                      ->getFont()->setBold(true);

                $cellRange = 'A3:L3';
                $event->sheet->getDelegate()->getStyle($cellRange)
                    ->getFont()->setBold(true);
                $event->sheet->getDelegate()->mergeCells($cellRange);
                $event->sheet->getDelegate()->getStyle($cellRange)
                      ->getAlignment()->setHorizontal('center');

                $cellRange = 'A4:L4';
                $event->sheet->getDelegate()->getStyle($cellRange)
                    ->getFont()->setBold(true);
                $event->sheet->getDelegate()->mergeCells($cellRange);
                $event->sheet->getDelegate()->getStyle($cellRange)
                    ->getAlignment()->setHorizontal('center');

                $cellRange = 'A5:L5';
                $event->sheet->getDelegate()->mergeCells($cellRange);
                $event->sheet->getDelegate()->getStyle($cellRange)
                    ->getAlignment()->setHorizontal('center');

                $cellRange = 'A7:L7'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)
                      ->getAlignment()->setVertical('center');
                $event->sheet->getDelegate()->getStyle($cellRange)
                        ->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle($cellRange)
                      ->getFont()->setSize(12);

                $event->sheet->getDelegate()->getStyle($cellRange)
                      ->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A7:L7')
                      ->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A7:L7')
                      ->getBorders()->getTop()->setBorderStyle("medium");
                $event->sheet->getDelegate()->getStyle('A7:L7')
                      ->getBorders()->getBottom()->setBorderStyle("thin");
                $event->sheet->getDelegate()->getRowDimension('7')->setRowHeight(30);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(8);
                // $cellRange = 'A8:A1000';
                // $event->sheet->getDelegate()->getStyle($cellRange)
                //     ->getAlignment()->setHorizontal('center');

                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(35);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(8);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(11);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(11); 
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(11); 
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(11); 
                $event->sheet->getDelegate()->setTitle('Ordenes');

                // $cellRange = 'A1:L1000'; // All sheet
                // $event->sheet->getDelegate()->getStyle($cellRange)
                //     ->getFont()->setName('Arial');
                
                $cellRange = 'A1:L5'; // All sheet
                $event->sheet->getDelegate()->getStyle($cellRange)
                    ->getFont()->setSize(11);

                $cellRange = 'A7:L7'; // All sheet
                $event->sheet->getDelegate()->getStyle($cellRange)
                    ->getFont()->setSize(9);

                // $cellRange = 'A6:L1000'; // All sheet
                // $event->sheet->getDelegate()->getStyle($cellRange)
                //         ->getFont()->setSize(8);

                        
                
                //$event->sheet->getDelegate()->getCell('A1');
                //$event->sheet->getDelegate()->appendRow();
                //$event->sheet->getDelegate()->getcellCollection()->appendRow(2,array('appended', 'appended'));
                $numOfRows = $event->sheet->getDelegate()->getHighestRow();
                $totalRow = $numOfRows + 2;

                $cellRange = 'K' . $totalRow;
                $cellRange2 = 'L' . $totalRow;
                
                $event->sheet->getDelegate()->getStyle($cellRange)
                      ->getFont()->setBold(true);
                $event->getDelegate()->setCellValue("K{$totalRow}", "TOTAL");

                $event->sheet->getDelegate()->getStyle($cellRange2)
                      ->getFont()->setBold(true);
                $event->getDelegate()->setCellValue("L{$totalRow}", "=SUM(L1:L{$numOfRows})");

                //$cellRange = 'A1'; // All headers
                //$event->sheet->getDelegate()->add

                // PHPExcel_Style_Border::BORDER_THIN
                //dd($event->sheet->getDelegate()->getStyle('A7:K7')->getBorders()->getTop()->setBorderStyle("thin"));

                
            }
        ];
    }

    // public function styles()
    // {
    //     // Add cell with SUM formula to last row
    //     $sheet->setCellValue("B{$totalRow}", "=SUM(B1:B{$numOfRows})");
    // }
}
