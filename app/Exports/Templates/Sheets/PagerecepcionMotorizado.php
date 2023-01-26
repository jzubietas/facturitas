<?php

namespace App\Exports\Templates\Sheets;

use App\Abstracts\Export;
use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;
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

class PagerecepcionMotorizado extends Export implements WithStyles, WithColumnFormatting, FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithColumnWidths
{
    //use RemembersRowNumber;

    public int $motorizado_id = 0;
    public string $fecha_envio_h = '';
    public int $condicion_envio_h = 0;

    public function __construct($user_motorizado_p, $fecha_envio_p, $condicion_envio_p)
    {
        parent::__construct();
        $this->motorizado_id = $user_motorizado_p;
        $this->fecha_envio_h = $fecha_envio_p;
        $this->condicion_envio_h = $condicion_envio_p;
    }

    public function collection()
    {
        DB::statement(DB::raw('set @rownum=0'));

        $direccion = DireccionGrupo::where('direccion_grupos.estado', '1')
            ->join('users as u', 'direccion_grupos.user_id', 'u.id')
            ->join('clientes as c', 'direccion_grupos.cliente_id', 'c.id')
            ->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                DB::raw('direccion_grupos.celular as celular_recibe'),
                DB::raw('direccion_grupos.nombre as nombre_recibe'),
                'direccion_grupos.correlativo',
                'direccion_grupos.codigos',
                'direccion_grupos.producto',
                'direccion_grupos.cantidad as qty',
                'direccion_grupos.nombre_cliente',

                'direccion_grupos.destino',
                'direccion_grupos.distribucion',
                'direccion_grupos.nombre',
                'direccion_grupos.direccion',
                'direccion_grupos.referencia',
                'direccion_grupos.distrito',
            ])
            ->where('direccion_grupos.estado', '=', '1');
        //->orderBy('concat(direccion_grupos.celular)','desc');
        if ($this->motorizado_id != 0) {
            $direccion = $direccion->where('direccion_grupos.motorizado_id', $this->motorizado_id);
        }
        if ($this->fecha_envio_h != '') {
            $direccion = $direccion->whereDate('direccion_grupos.fecha_salida', $this->fecha_envio_h);
        }
        if ($this->condicion_envio_h != '') {
            $direccion = $direccion->where('direccion_grupos.condicion_envio_code', $this->condicion_envio_h);
        }

        /*->when($fecha_consulta != null, function ($query) use ($fecha_consulta) {
            $query->whereDate('direccion_grupos.fecha_salida', $fecha_consulta);
        })*/
        $datos = $direccion->get();

        $items = [];

        foreach ($datos as $item) {
            if ($item->distribucion == 'OLVA') {
                $key = $item->destino . '_' . $item->distribucion . '_' . $item->direccion;
            } else {
                $key = $item->destino . '_' . $item->distribucion . '_' . $item->direccion . '_' . $item->distrito . '_' . $item->nombre_recibe;
            }
            if (!isset($items[$key])) {
                $items[$key] = $item;
                $items[$key]->producto = explode(',', $item->producto);
                $items[$key]->codigos = explode(',', $item->codigos);
            } else {
                $p = $items[$key]->producto;
                $c = $items[$key]->codigos;
                $productos = explode(',', $item->producto);
                $codigos = explode(',', $item->codigos);
                foreach ($productos as $producto) {
                    $p[] = $producto;
                }
                foreach ($codigos as $codigo) {
                    $c[] = $codigo;
                }
                $items[$key]->producto = $p;
                $items[$key]->codigos = $c;
            }

        }

        return collect(array_values($items));
    }

    public function title(): string
    {
        //return parent::title();//Por defecto se toma del nombre de la clase de php, en este caso seria "Pagina One" de titulo
        return 'Motorizado ' . $this->motorizado_id . ' ' . $this->fecha_envio_h;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 13.57 //CELULAR QUIEN RECIBE
            , 'B' => 3.57 //ME RO
            , 'C' => 11.29 //CODIGO
            , 'D' => 13.57 //NOMBRE DE qUIEN RECIBE
            , 'E' => 33.57 //RAZON SOCIAL
            , 'F' => 3.57 //QTY
            , 'G' => 13.57 //CLIENTE
            , 'H' => 33.57 //DIRECCION
            , 'I' => 37.29 //REFERENCIA
            , 'J' => 13.57 //DISTRITO
        ];

    }

    public function map($model): array
    {
        if ($model->distribucion == 'OLVA') {
            $model->contacto_recibe_tracking = $model->direccion;
        } else {
            $model->contacto_recibe_tracking = $model->nombre_recibe;
        }

        $model->codigos = collect($model->codigos)->join("\n");

        $model->producto = collect($model->producto)->map(fn($producto, $index) => ($index + 1) . ') ' . $producto)->join("\n");

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            "celular_recibe" => "QUIEN RECIBE"
            , "rownum" => "NUM"
            , "codigos" => "CODIGO"
            , "contacto_recibe_tracking" => "NOMBRE DE QUIEN RECIBE"
            , "producto" => "PRODUCTO/RAZON SOCIAL"
            , "QTY" => "QTY"
            , "nombre_cliente" => "CLIENTE"
            , "direccion" => "DIRECCION"
            , "referencia" => "REFERENCIA"
            , "distrito" => "DISTRITO"
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => [self::class, 'afterSheet']
        ];
    }

    public static function afterSheet(AfterSheet $event)
    {
        $color_R = 'ff5733';
        $color__ = 'fcf8f2';
        $color_A = 'faf01c';
        $color_C = '1cfaf3';
        $color_N = 'e18b16';
        $color_V = '6acf0c';

        $style_R = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_R)
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
        $style_C = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_C)
            )
        );
        $style_V = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_V)
            )
        );
        /*$styledefault = array(
    'fill' => array(
        'fillType' => Fill::FILL_SOLID,
        'startColor' => array('argb' => $color_default)
    )
);*/

        $row_cell_ = 14;
        $letter_cell = 'J';

        $event->sheet->getStyle('C')->getAlignment()->setWrapText(true);
        $event->sheet->getStyle('E')->getAlignment()->setWrapText(true);

        $event->sheet->styleCells(
            'A1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => $color_R]
                ]
            ]
        );
        $event->sheet->styleCells(
            'B1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => $color__]
                ]
            ]
        );
        $event->sheet->styleCells(
            'C1:E1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => $color_A]
                ]
            ]
        );
        $event->sheet->styleCells(
            'F1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => $color_N]
                ]
            ]
        );
        $event->sheet->styleCells(
            'G1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => $color_A]
                ]
            ]
        );
        $event->sheet->styleCells(
            'H1:I1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => $color_C]
                ]
            ]
        );
        $event->sheet->styleCells(
            'J1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => $color_A]
                ]
            ]
        );

        foreach ($event->sheet->getRowIterator() as $row) {
            if ($row->getRowIndex() == 1) continue;
            /*if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='RECURRENTE')
            {*/
            $event->sheet->getStyle($letter_cell . $row->getRowIndex())->applyFromArray($style_V);
            //}
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

