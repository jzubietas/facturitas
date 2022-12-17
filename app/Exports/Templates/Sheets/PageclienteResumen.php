<?php

namespace App\Exports\Templates\Sheets;

use App\Abstracts\Export;
use App\Models\ListadoResultado;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PageclienteResumen extends Export implements WithColumnFormatting,WithColumnWidths
{
    public function collection()
    {
        $cliente_list = [];

        //now()->startOfMonth()->format("Y-m-d H:i:s")
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

        $data=$_2022_10;

        //$pedidos = $pedidosLima->union($pedidosProvincia);

        //$data=$data->get();
        //return Cliente::with('user')->get();
        return $data->get();
    }

    public function title(): string
    {
        //return parent::title();//Por defecto se toma del nombre de la clase de php, en este caso seria "Pagina One" de titulo
        return 'Octubre';
    }

    public function map($model): array
    {
        //mapear datos del model que no esten la tabla
     /*
        $model->nuevo_campo = //nuevo campo
     */
        //$model->fehca_formato=$model->created_at->format('');
        $model->Periodo=strval(str_pad($model->Periodo,2,"0"));//->setDataType(DataType::TYPE_STRING);
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
        // columna de la base de datos => nombre de la columna en excel
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

        /*d62828  ABANDONO
        fca311  ABANDONO RECIENTE
        blanco  base fria
        b5e48c	nuevo
        00b4d8		RECUPERADO RECIENTE
        3a86ff		RECUPERADO ABANDONO
        a9def9		RECURRENTE*/

        /*$stylerecuperadoreciente = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            ),
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'bfd200',
                ]
            ],
        );*/
        $color_recurente='a9def9';
        $color_recuperadoabandono='3a86ff';
        $color_recuperadoreciente='00b4d8';
        $color_nuevo='b5e48c';
        $color_basefria='ffffff';
        $color_abandono='d62828';
        $color_abandonoreciente='fca311';
        $color_default='eff7f6';

        $style_recurrente = array(
                'fill' => array(
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => array('argb' => $color_recurente)
                )
        );
        $style_recuperadoabandono = array(
                'fill' => array(
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => array('argb' => $color_recuperadoabandono)
                )
        );
        $style_recuperadoreciente = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_recuperadoreciente)
            )
        );
        $style_nuevo = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_nuevo)
            )
        );
        $style_basefria = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_basefria)
            )
        );
        $style_abandono = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_abandono)
            )
        );
        $style_abandonoreciente = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_abandonoreciente)
            )
        );
        $styledefault = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_default)
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
            else if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='RECUPERADO ABANDONO')
            {
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style_recuperadoabandono);
            }
            else if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='RECUPERADO RECIENTE')
            {
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style_recuperadoreciente);
            }
            else if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='NUEVO')
            {
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style_nuevo);
            }
            else if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='BASE FRIA')
            {
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style_basefria);
            }
            else if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='ABANDONO')
            {
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style_abandono);
            }
            else if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='ABANDONO RECIENTE')
            {
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style_abandonoreciente);
            }else{
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($styledefault);
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
