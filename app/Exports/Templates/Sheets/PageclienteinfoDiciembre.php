<?php

namespace App\Exports\Templates\Sheets;

use App\Abstracts\Export;
use App\Models\ListadoResultado;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PageclienteinfoDiciembre extends Export implements WithColumnFormatting,WithColumnWidths
{
    public function collection()
    {
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

        $data=$_2022_12;

        return $data->get();
    }
    public function title(): string
    {
        return 'Detalle Diciembre';
    }
    public function map($model): array
    {
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

    public static function afterSheet(AfterSheet $event){

        $style_recurrente = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            ),
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '90e0ef',
                ]
            ],
        );
        $stylerecuperadoabandono = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            ),
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'b5e48c',
                ]
            ],
        );
        $stylerecuperadoreciente = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            ),
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'bfd200',
                ]
            ],
        );
        $stylenuevo = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            ),
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'ffcfd2',
                ]
            ],
        );
        $stylebasefria = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            ),
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'eff7f6',
                ]
            ],
        );
        $styleabandono = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            ),
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'ff4d6d',
                ]
            ],
        );
        $styleabandonoreciente = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            ),
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'e85d04',
                ]
            ],
        );

        /*
         * RECURRENTE-----90e0ef
            RECUPERADO ABANDONO----b5e48c
            RECUPERADO RECIENTE---bfd200
            NUEVO------ffcfd2
            BASE FRIA----eff7f6
            ABANDONO----ff4d6d
            ABANDONO RECIENTE----e85d04
         * */

        foreach ($event->sheet->getRowIterator() as $row)
        {
            if($event->sheet->getCellByColumnAndRow(20,$row->getRowIndex())->getValue()=='RECURRENTE')
            {
                $event->sheet->getStyle("T".$row->getRowIndex())->applyFromArray($style_recurrente);
            }
            else if($event->sheet->getCellByColumnAndRow(20,$row->getRowIndex())->getValue()=='RECUPERADO ABANDONO')
            {
                $event->sheet->getStyle("T".$row->getRowIndex())->applyFromArray($stylerecuperadoabandono);
            }
            else if($event->sheet->getCellByColumnAndRow(20,$row->getRowIndex())->getValue()=='RECUPERADO RECIENTE')
            {
                $event->sheet->getStyle("T".$row->getRowIndex())->applyFromArray($stylerecuperadoreciente);
            }
            else if($event->sheet->getCellByColumnAndRow(20,$row->getRowIndex())->getValue()=='NUEVO')
            {
                $event->sheet->getStyle("T".$row->getRowIndex())->applyFromArray($stylenuevo);
            }
            else if($event->sheet->getCellByColumnAndRow(20,$row->getRowIndex())->getValue()=='BASE FRIA')
            {
                $event->sheet->getStyle("T".$row->getRowIndex())->applyFromArray($stylebasefria);
            }
            else if($event->sheet->getCellByColumnAndRow(20,$row->getRowIndex())->getValue()=='ABANDONO')
            {
                $event->sheet->getStyle("T".$row->getRowIndex())->applyFromArray($styleabandono);
            }
            else if($event->sheet->getCellByColumnAndRow(20,$row->getRowIndex())->getValue()=='ABANDONO RECIENTE')
            {
                $event->sheet->getStyle("T".$row->getRowIndex())->applyFromArray($styleabandonoreciente);
            }
            //$row->getRowIndex();
        }
    }
}
