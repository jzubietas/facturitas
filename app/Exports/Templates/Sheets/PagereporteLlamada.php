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

        $informacion=Cliente::
        select(
            //'clientes.id'
            'clientes.user_id'
            ,DB::raw(" (select u.identificador from users u where u.id=clientes.user_id and u.Rol='Asesor' ) as asesor ")
            .DB::raw("   
                select ua.name from users ua where u.id in 
                    (select u.id from users u where u.id=clientes.user_id and u.rol='Asesor' ) and ua.rol='Llamada'
                as llamada "),
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
        ];
    }

    public function fields(): array
    {
        return [
            "cliente"=>"cliente"
            ,"asesor"=>"asesor"
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
