<?php

namespace App\Exports\Templates\Sheets;

use App\Abstracts\Export;
use App\Models\Cliente;
use App\Models\ListadoResultado;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Http\Request;

class PagereporteLlamada extends Export implements WithColumnFormatting,WithColumnWidths
{

    public function collection()
    {
        $cliente_list = [];

        /*"cliente"=>"Ejercicio"
            ,"asesor"=>"Periodo"
            ,"llamada"=>"Periodo2"
            ,"pedido"=>"grupo"
            ,"fecha_pedido"=>"total*/

        $informacion=Cliente::join('clientes as c','c.id','listado_resultados.id')
        ->select(
            DB::raw(" (select '2022') as Ejercicio "),
            DB::raw(" (select '07') as Periodo "),
            DB::raw(" (select 'Julio') as Periodo2 "),
            'listado_resultados.s_2022_07 as grupo',
            DB::raw('count(listado_resultados.s_2022_07) as total')
            //'cantidad'
        )
        ->groupBy(
            's_2022_07'
        );

       
        return $informacion->get();
    }

    public function title(): string
    {
        return 'Reporte Llamada';
    }

    public function map($model): array
    {
        //$model->Periodo=strval(str_pad($model->Periodo,2,"0"));
        return parent::map($model);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8
            ,'B' => 8
            ,'C' => 8
            ,'D' => 8
            ,'E' => 8
        ];
    }

    public function fields(): array
    {
        return [
            "cliente"=>"Ejercicio"
            ,"asesor"=>"Periodo"
            ,"llamada"=>"Periodo2"
            ,"pedido"=>"grupo"
            ,"fecha_pedido"=>"total"
        ];
    }

    public function columnFormats(): array
    {
        return [
            //Formato de las columnas segun la letra
            /*
             'D' => NumberFormat::FORMAT_DATE_YYYYMMDD,
             'E' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            */
            'B' => NumberFormat::FORMAT_TEXT

        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => [self::class, 'afterSheet']
        ];
    }

    public static function afterSheet(AfterSheet $event){

        $color_cabeceras='a9def9';
       

        $style_cabeceras = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_cabeceras)
            )
        );

        $row_cell_=14;
        $letter_cell='N';
        foreach ($event->sheet->getRowIterator() as $row)
        {
            if($row->getRowIndex()==1)continue;
            if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='RECURRENTE')
            {
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style_recurrente);
            }
            

        }

        /*echo 'ROW: ', $cell->getRow(), PHP_EOL;
                   echo 'COLUMN: ', $cell->getColumn(), PHP_EOL;
                   echo 'COORDINATE: ', $cell->getCoordinate(), PHP_EOL;
                   echo 'RAW VALUE: ', $cell->getValue(), PHP_EOL;*/

        //Range Columns
                /*
                $event->sheet->styleCells(
                    'Q',
                    [
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => '336655']
                        ]
                    ]
                ); */
            }
}
