<?php

namespace App\Abstracts\Exports;

use App\Abstracts\Export;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class PageRutasEnvioLima extends Export implements WithEvents, WithColumnWidths, WithCustomStartCell, WithStyles
{
    public static $fecharuta = '';
    public $contador = 1;

    public function __construct($ids)
    {
        parent::__construct();
        self::$fecharuta = $ids;
    }

    public function fields(): array
    {
        return [
            "celular" => "NUMERO",
            "num_registros" => "Nº",
            "nombre_cli" => "NOMBRE CLIENTE",
            "codigos" => "CODIGO",
            "producto" => "PRODUCTO",
            "cantidad" => "QTY",
            "nombre" => "NOMBRE A QUIEN RECIBE",

            "direccion" => "DIRECCION DE ENTREGA",
            "referencia" => "REFERENCIA",
            "distrito" => "DISTRITO",
            "observacion" => "OBSERVACION",
            //,"celular"=>"Celular"
            //,"destino"=>"Destino"
            //,"fecha"=>"Fecha"
            //,"distribucion"=>"Distribucion"
            //,"condicion_sobre"=>"Condicion"
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10
            , 'B' => 4
            , 'C' => 15
            , 'D' => 12
            , 'E' => 40
            , 'F' => 7
            , 'G' => 17
            , 'H' => 28
            , 'I' => 25
            , 'J' => 15
            , 'K' => 20
        ];
    }


    public function startCell(): string
    {
        return 'A4';
    }


    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => [self::class, 'beforeSheet'],
            AfterSheet::class => [self::class, 'afterSheet']
        ];
    }


    public static function beforeSheet(BeforeSheet $event)
    {

        /*$sheet->prependRow(1, array(
            'prepended', 'prepended'
        ));*/


        //$workSheet = $event->sheet->getDelegate();
        //$workSheet->freezePane('A3');

        $event->sheet->appendRows(array(
            array('', '', 'ENVIOS', '', self::$fecharuta, 'FECHA: '),
            array('', '', '', '', '', ''),
            //....
        ), $event);

    }


    public static function afterSheet(AfterSheet $event)
    {


        /*echo 'ROW: ', $cell->getRow(), PHP_EOL;
                   echo 'COLUMN: ', $cell->getColumn(), PHP_EOL;
                   echo 'COORDINATE: ', $cell->getCoordinate(), PHP_EOL;
                   echo 'RAW VALUE: ', $cell->getValue(), PHP_EOL;*/

        //Range Columns

        $event->sheet->getStyle('A4:K4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);



        $letter_cell='N';
        foreach ($event->sheet->getRowIterator() as $row)
        {
            if($row->getRowIndex()<5)continue;
            $num=$row->getRowIndex();
            $event->sheet->getStyle("A$num:K$num")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }
        $event->sheet->getStyle('4')->getAlignment()->setWrapText(true);
        $event->sheet->getStyle('A')->getAlignment()->setWrapText(true);
        $event->sheet->getStyle('B')->getAlignment()->setWrapText(true);
        $event->sheet->getStyle('C')->getAlignment()->setWrapText(true);
        $event->sheet->getStyle('D')->getAlignment()->setWrapText(true);
        $event->sheet->getStyle('E')->getAlignment()->setWrapText(true);
        $event->sheet->getStyle('G')->getAlignment()->setWrapText(true);
        $event->sheet->getStyle('H')->getAlignment()->setWrapText(true);
        $event->sheet->getStyle('I')->getAlignment()->setWrapText(true);
        $event->sheet->getStyle('J')->getAlignment()->setWrapText(true);
        $event->sheet->getStyle('K')->getAlignment()->setWrapText(true);

        $event->sheet->styleCells(
            'C1:F1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'ffeb00']
                ]
            ]
        );


        $event->sheet->styleCells('A4', ['fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'ff0000']]]);
        $event->sheet->styleCells('B4', ['fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'EF7D31']]]);
        $event->sheet->styleCells('C4:D4', ['fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'ffeb00']]]);

        $event->sheet->styleCells('E4:I4', ['fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'cde5f5']]]);
        $event->sheet->styleCells('J4', ['fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'ffeb00']]]);
        $event->sheet->styleCells('K4', ['fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'cde5f5']]]);


    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Styling an entire column.
            '4' => ['font' => ['bold' => true, 'size' => 11]],
            'C' => ['font' => ['size' => 11]],
        ];
    }

    public function map($model): array
    {
        $model->nombre_cli = \Str::upper($model->nombre_cli ?: '');
        $model->producto = \Str::upper($model->producto ?: '');
        $model->referencia = \Str::upper($model->referencia ?: '');
        $model->distrito = \Str::upper($model->distrito ?: '');

        if (\Str::contains($model->producto ?: '', ',')) {
            $productos = explode(',', $model->producto ?: '');

            $ptxt = '';
            foreach ($productos as $index => $p) {
                $ptxt .= ($index + 1) . '. ' . $p;
                $ptxt .= "\n";
            }
            $model->producto = $ptxt;
        }
        if (\Str::contains($model->codigos ?: '', ',')) {
            $codigos = explode(',', $model->codigos ?: '');
            $ptxt = '';
            foreach ($codigos as $p) {
                $ptxt .= $p;
                $ptxt .= "\n";
            }
            $model->codigos = $ptxt;
        }
        return parent::map($model);
    }
}
