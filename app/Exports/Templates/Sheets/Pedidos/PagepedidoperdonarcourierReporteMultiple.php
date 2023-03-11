<?php

namespace App\Exports\Templates\Sheets\Pedidos;

use App\Abstracts\Export;
use App\Models\Cliente;
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
class PagepedidoperdonarcourierReporteMultiple extends Export implements WithStyles, WithColumnFormatting, FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithColumnWidths
{
    private $situacion='';
    private  $anio='';
    public function __construct($situacion,$anio)
    {
        parent::__construct();
        $this->situacion=$situacion;
        $this->anio=$anio;
    }
    public function collection()
    {
        $perdonar = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select([
                'pedidos.id',
                'c.nombre as nombres',
                'c.icelular as icelulares',
                'c.celular as celulares',
                'u.identificador as users',
                'pedidos.codigo as codigos',
                'dp.nombre_empresa as empresas',
                'dp.total as total',
                'pedidos.condicion_envio',
                'pedidos.condicion as condiciones',
                'pedidos.pagado as condicion_pa',
                DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                'pedidos.motivo',
                'pedidos.responsable',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha2'),
                DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                'dp.saldo as diferencia',
                'pedidos.estado',
                'pedidos.pago',
                'pedidos.pagado',
                'pedidos.envio'
            ])
            /*->whereNotIn('pedidos.condicion_code', [Pedido::ANULADO_INT])*/
            ->whereIn('pedidos.pagado', ['1'])
            ->whereIn('pedidos.pago', ['1'])
            //->whereNotIn("pedidos.envio", ['3'])
            ->where('dp.saldo', '>=', 11)->where('dp.saldo', '<=', 13);

        return $perdonar->get();
    }
    public function fields(): array
    {
        return [
            "nombres"=>"Nombres"
            ,"icelulares"=>"Letra celular"
            ,"celulares"=>"Celular"
            ,"users"=>"Asesor"
            ,"codigos"=>"Pedido"
            ,"empresas"=>"Empresa"
            ,"total"=>"Total"
            ,"condicion_envio"=>"Condicion"
            ,"condicion_pa"=>"Estado Pago"
            ,"condiciones_aprobado"=>"Estado Administracion"
            ,"motivo"=>"Motivo"
            ,"responsable"=>"Responsable"
            ,"diferencia"=>"Saldo"
            ,"estado"=>"Estado del pedido"
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 8//item
            ,'B' => 8//identificador
            ,'C' => 8//celular
            ,'D' => 8//rucs
            ,'E' => 8//deuda
            ,'F' => 8//importe
            ,'G' => 8//mes
            ,'H' => 8//mes
            ,'I' => 8//mes
            ,'J' => 8//mes
            ,'K' => 8//mes
            ,'L' => 8//mes
            ,'M' => 8//mes
            ,'N' => 8//mes
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
            'S' => NumberFormat::FORMAT_TEXT,
            'T' => NumberFormat::FORMAT_TEXT,
            'U' => NumberFormat::FORMAT_TEXT,

        ];
    }
    public function title(): string
    {
        return 'PERDONAR DEUDA';
    }
    public function map($model): array
    {
        switch ($model->condicion_pa)
        {
            case '0':
                $model->condicion_pa='POR PAGAR';
                break;
            case '1':
                $model->condicion_pa='ADELANTO';
                break;
            case '2':
                $model->condicion_pa='PAGADO';
            break;
        }
        $model->estado=( ($model->estado==1)? 'Activo':'Anulado' );
        return parent::map($model);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => [self::class, 'afterSheet']
        ];
    }

    public static function afterSheet(AfterSheet $event){

        /*$color_R = 'ff5733';
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
        $style_V = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_V)
            )
        );*/

        /*$event->sheet->styleCells(
            'A1:AX1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => '6acf0c ']
                ]
            ]
        );*/

        /*$event->sheet->styleCells(
            'L1:O1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'cedb40']
                ]
            ]
        );*/

        /*$row_cell_=23;
        $letter_cell='W';
        foreach ($event->sheet->getRowIterator() as $row)
        {
            if($row->getRowIndex()==1)continue;
            //$event->sheet->getStyle($letter_cell . $row->getRowIndex())->applyFromArray($style_V);
            if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='RECURRENTE')
            {
                //$event->sheet->getStyle($letter_cell . $row->getRowIndex())->applyFromArray($style_V);
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style_R);
            }
            else if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='ABANDONO')
            {
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style_A);
            }else if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='NULO')
            {
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style__);
            }else if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='ABANDONO RECIENTE')
            {
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style_AR);
            }


        }*/

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
