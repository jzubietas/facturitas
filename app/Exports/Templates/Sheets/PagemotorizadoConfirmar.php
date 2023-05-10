<?php

namespace App\Exports\Templates\Sheets;

use App\Abstracts\Export;
use App\Models\DireccionGrupo;
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

use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class PagemotorizadoConfirmar extends Export implements WithColumnFormatting, FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    public int $user_motorizado_h=0;
    public string $fecha_envio_h='';
    public function __construct($user_motorizado_p,$fecha_envio_p)
    {
        parent::__construct();
        $this->user_motorizado_h=$user_motorizado_p;
        $this->fecha_envio_h=$fecha_envio_p;
    }
    public function collection()
    {
        $direccion=DireccionGrupo::where('direccion_grupos.estado','1')
            ->join('users as u', 'direccion_grupos.user_id', 'u.id')
            ->join('clientes as c', 'direccion_grupos.cliente_id', 'c.id')
            ->select([
                'direccion_grupos.correlativo',
                'direccion_grupos.codigos',
                'u.identificador',
                'c.nombre',
                'direccion_grupos.fecha_recepcion',
                'direccion_grupos.producto',
                'direccion_grupos.destino',
                'direccion_grupos.direccion',
                'direccion_grupos.referencia',
                'direccion_grupos.condicion_envio',
            ]);
        if(!$this->user_motorizado_h){
            $direccion=$direccion->where('direccion_grupos.motorizado_id',$this->user_motorizado_h);
        }
        if(!$this->fecha_envio_h){
            $direccion=$direccion->where('cast(direccion_grupos.fecha_recepcion as date)',$this->fecha_envio_h);
        }

        return  $direccion->get();
    }

    public function title(): string
    {
        //return parent::title();//Por defecto se toma del nombre de la clase de php, en este caso seria "Pagina One" de titulo
        return 'Motorizado Confirmar'.$this->user_motorizado_h.' '.$this->fecha_envio_h;;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8 //ITEM
            ,'B' => 8 //CODIGO
            ,'C' => 8 //ASESOR
            ,'D' => 8 //Cliente
            ,'E' => 8 //FECHA ENVIO
            ,'F' => 8 //RAZON SOCIAL
            ,'G' => 8 //DESTINO
            ,'H' => 8 //DIRECCION ENVIO
            ,'I' => 8 //REFERENCIA
            ,'J' => 8 //ESTADO DE ENVIO
        ];
    }

    public function map($model): array
    {
        return parent::map($model);
    }
    public function fields(): array
    {
        return [
            "correlativo"=>"ITEM"
            ,"codigos"=>"Codigo"
            ,"identificador"=>"Asesor"
            ,"nombre"=>"Cliente"
            ,"fecha_recepcion"=>"Fecha de Envio"
            ,"producto"=>"Razon Social"
            ,"destino"=>"Destino"
            ,"direccion"=>"Direccion de Envio"
            ,"referencia"=>"Referencia"
            ,"condicion_envio"=>"Estado de Envio"
        ];
    }
    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_YYYYMMDD
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => [self::class, 'afterSheet']
        ];
    }

    public static function afterSheet(AfterSheet $event){


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

