<?php

namespace App\Exports\Templates\Sheets\Movimientos;

use App\Abstracts\Export;
use App\Models\Cliente;
use App\Models\MovimientoBancario;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;

use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});
class PagemovimientoReporteMultiple extends Export implements WithStyles, WithColumnFormatting, FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithColumnWidths
{
    private $desde='';
    private  $hasta='';
    public function __construct($desde,$hasta)
    {
        parent::__construct();
        $this->desde=$desde;
        $this->hasta=$hasta;
    }
    public function collection()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $movimientos = MovimientoBancario::leftjoin("pagos as p","movimiento_bancarios.cabpago","p.id")
            ->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'movimiento_bancarios.id as iden',
                'movimiento_bancarios.banco',
                'movimiento_bancarios.titular',
                'movimiento_bancarios.importe',
                'movimiento_bancarios.tipo',
                'movimiento_bancarios.descripcion_otros as otros',
                DB::raw('(select DATE_FORMAT(dpa.fecha, "%d-%m-%Y")  from movimiento_bancarios dpa where dpa.id=movimiento_bancarios.id) as fecha'),
                'movimiento_bancarios.pago',
                'p.id as pagoid',
                DB::raw(" (select (us.identificador) from users us where us.id=p.user_id) as users "),
                DB::raw(" (CASE WHEN (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=p.id and dpago.estado in (1) )>1 then 'V' else 'I' end) as cantidad_voucher "),
                DB::raw(" (CASE WHEN (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=p.id and ppedidos.estado in (1)  )>1 then 'V' else 'I' end) as cantidad_pedido "),
                DB::raw(" (select count(dp.id) from detalle_pagos dp where dp.pago_id=p.id) as cant "),
                'movimiento_bancarios.estado'
            ])
            ->whereBetween(DB::raw('DATE(movimiento_bancarios.fecha)'), [$this->desde, $this->hasta])
            ->orderBy('movimiento_bancarios.fecha', 'DESC');
            //->get();

        return $movimientos->get();
    }
    public function fields(): array
    {
        return [
            "rownum"=>"Item"
            ,"iden"=>"Id"
            ,"banco"=>"Banco"
            ,"titular"=>"Titular"
            ,"fecha"=>"Fecha"
            ,"tipo"=>"Tipo-Otros"
            ,"importe"=>"Importe"
            ,"pago"=>"Estado conciliado"
            ,"cantidad_voucher"=>"PAGO"
            ,"estado"=>"Estado Activo"
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 8//item
            ,'B' => 13//identificador
            ,'C' => 13//celular
            ,'D' => 26//Titular
            ,'E' => 18//Fecha
            ,'F' => 25//importe
            ,'G' => 10//mes
            ,'H' => 15//mes
            ,'I' => 15//mes
            ,'J' => 6//mes
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
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,

        ];
    }
    public function title(): string
    {
        return 'MOVIMIENTOS BANCARIOS '.($this->desde).' - '.($this->hasta);
    }
    public function map($model): array
    {
        $model->cantidad_voucher= 'PAG-'.($model->cantidad_voucher) .($model->cantidad_pedido).'-'.($model->iden);
        $model->iden='MOV'.($model->iden);
        $model->pago=( ($model->pago==1)? 'CONCILIADO':'SIN CONCILIAR' );

        $model->estado=( ($model->estado==1)? 'ACTIVO':'INACTIVO' );
        return parent::map($model);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => [self::class, 'afterSheet']
        ];
    }

    public static function afterSheet(AfterSheet $event){

        $color_R = 'ff5733';
        $color__ = 'fcf8f2';
        $color_A = 'faf01c';
        $color_C = '1cfaf3';
        $color_AR = '1cfaf3';
        $color_N = 'e18b16';
        $color_V = '6acf0c';

        $style_R = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_R)
            )
        );
        $style_AR = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_AR)
            )
        );
        $style__ = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color__)
            )
        );
        $style_A = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_A)
            )
        );

        $event->sheet->styleCells(
            'A1:K1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'faf01c']
                ]
            ]
        );

        $row_cell_=10;
        $letter_cell='J';
        foreach ($event->sheet->getRowIterator() as $row)
        {
            if($row->getRowIndex()==1)continue;
            if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='INACTIVO')
            {
                $event->sheet->styleCells(
                    'A'.$row->getRowIndex().':K'.$row->getRowIndex(),
                    [
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => 'f20000']
                        ]
                    ]
                );
                //$event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style_R);
            }else{
                //$event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style_AR);
            }
        }

    }
    public function styles(Worksheet $sheet)
    {
        // TODO: Implement styles() method.
        return [
            'A' => [
                'alignment' => [
                    'wrapText' => true,
                ],
            ],
            'B' => [
                'alignment' => [
                    'wrapText' => true,
                ],
            ],
            'C' => [
                'alignment' => [
                    'wrapText' => true,
                ],
            ],
        ];
    }

}
