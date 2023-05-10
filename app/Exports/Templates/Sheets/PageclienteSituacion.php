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

class PageclienteSituacion extends Export implements WithColumnFormatting,WithColumnWidths
{


    public function collection()
    {
        $cliente_list = [];

        $_2022_07=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
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

        $_2022_08=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
        ->select(
            DB::raw(" (select '2022') as Ejercicio "),
            DB::raw(" (select '08') as Periodo "),
            DB::raw(" (select 'Agosto') as Periodo2 "),
            'listado_resultados.s_2022_08 as grupo',
            DB::raw('count(listado_resultados.s_2022_08) as total')
            //'cantidad'
        )
        ->groupBy(
            's_2022_08'
        );

        $_2022_09=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
        ->select(
            DB::raw(" (select '2022') as Ejercicio "),
            DB::raw(" (select '09') as Periodo "),
            DB::raw(" (select 'Setiembre') as Periodo2 "),
            'listado_resultados.s_2022_09 as grupo',
            DB::raw('count(listado_resultados.s_2022_09) as total')
            //'cantidad'
        )
        ->groupBy(
            's_2022_09'
        );

        $_2022_10=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
        ->select(
            DB::raw(" (select '2022') as Ejercicio "),
            DB::raw(" (select '10') as Periodo "),
            DB::raw(" (select 'Octubre') as Periodo2 "),
            'listado_resultados.s_2022_10 as grupo',
            DB::raw('count(listado_resultados.s_2022_10) as total')
            //'cantidad'
        )
        ->groupBy(
            's_2022_10'
        );

        $_2022_11=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
        ->select(
            DB::raw(" (select '2022') as Ejercicio "),
            DB::raw(" (select '11') as Periodo "),
            DB::raw(" (select 'Noviembre') as Periodo2 "),
            'listado_resultados.s_2022_11 as grupo',
            DB::raw('count(listado_resultados.s_2022_11) as total')
            //'cantidad'
        )
        ->groupBy(
            's_2022_11'
        );

        $_2022_12=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
        ->select(
            DB::raw(" (select '2022') as Ejercicio "),
            DB::raw(" (select '12') as Periodo "),
            DB::raw(" (select 'Diciembre') as Periodo2 "),
            'listado_resultados.s_2022_12 as grupo',
            DB::raw('count(listado_resultados.s_2022_12) as total')
            //'cantidad'
        )
        ->groupBy(
            's_2022_12'
        );

        $data=$_2022_07
                ->union($_2022_08)
                ->union($_2022_09)
                ->union($_2022_10)
                ->union($_2022_11)
                ->union($_2022_12);

        return $data->get();
    }

    public function title(): string
    {
        return 'Info Situacion';
    }

    public function map($model): array
    {
        $model->Periodo=strval(str_pad($model->Periodo,2,"0"));
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
            "Ejercicio"=>"Ejercicio"
            ,"Periodo"=>"Periodo"
            ,"Periodo2"=>"Periodo2"
            ,"grupo"=>"grupo"
            ,"total"=>"total"
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
