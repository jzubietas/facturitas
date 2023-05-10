<?php

namespace App\Exports\Templates\Sheets;


use App\Abstracts\Export;
use App\Models\DireccionGrupo;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use \Maatwebsite\Excel\Sheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use function PHPUnit\Framework\returnSelf;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class PagerutaenvioLimaSinasignar extends Export implements WithEvents,WithColumnWidths,WithCustomStartCell
{
    public static $fecharuta='';
    public $contador=1;
    public function __construct($ids)
    {
        parent::__construct();
        self::$fecharuta=$ids;
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function collection()
    {

        $pedidos_lima = DireccionGrupo::join('direccion_envios as de','direccion_grupos.id','de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')

            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.estado','1')
            ->where(function($query){
                $query->where('direccion_grupos.distribucion','=','')->orWhereNull('direccion_grupos.distribucion');
            })
            ->where('direccion_grupos.destino','LIMA')
            ->where(DB::raw('DATE(direccion_grupos.created_at)'), self::$fecharuta)
            ->whereNotIn('direccion_grupos.condicion_envio_code',[Pedido::ENTREGADO_SIN_SOBRE_OPE_INT,Pedido::ENTREGADO_SIN_SOBRE_CLIENTE_INT])
            ->select(
                'c.celular as correlativo',
                'u.identificador as identificador',
                'direccion_grupos.destino',
                'de.celular',
                'de.nombre',
                'de.cantidad',
                'direccion_grupos.codigos',
                'direccion_grupos.producto',
                'de.direccion',
                'de.referencia',
                'de.observacion',
                'de.distrito',
                'c.nombre as nombre_cli',
                'direccion_grupos.created_at as fecha',
                'direccion_grupos.distribucion',
                'direccion_grupos.condicion_sobre',
            );

        $pedidos =($pedidos_lima);
        return $pedidos->get();
    }

    public function fields(): array
    {
        return [
            "celular"=>"NUMERO"
            ,"num_registros"=>"Nº"
            ,"nombre"=>"NOMBRE A QUIEN RECIBE"
            // ,"nombre_cli" => "NOMBRE CLIENTE"
            ,"codigos"=>"CODIGO"
            ,"producto"=>"PRODUCTO"
            ,"cantidad"=>"CANTIDAD"

            ,"direccion"=>"DIRECCION DE ENTREGA"
            ,"referencia"=>"REFERENCIA"
            ,"distrito"=>"DISTRITO"
            ,"observacion"=>"OBSERVACION"
            //,"celular"=>"Celular"
            //,"destino"=>"Destino"
            //,"fecha"=>"Fecha"
            //,"distribucion"=>"Distribucion"
            //,"condicion_sobre"=>"Condicion"
        ];
    }

    public function title(): string
    {
        return 'Lima Sin Asignar '.self::$fecharuta;
    }
    public function map($model): array
    {
        $model->num_registros=$this->contador;
        $this->contador++;
        return parent::map($model);
    }
    public function columnWidths(): array
    {
        return [
            'A' => 15
            ,'B' => 5
            ,'C' => 30
            ,'D' => 30
            ,'E' => 30
            ,'F' => 30
            ,'G' => 50
            ,'H' => 60
            ,'I' => 120
            ,'J' => 60
            ,'K' => 40
            ,'M' => 8
            ,'N' => 8
            ,'O' => 8
            ,'P' => 8
        ];
    }

    public function columnFormats(): array
    {
        return [
            'N' => NumberFormat::FORMAT_TEXT
        ];
    }
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => [self::class, 'beforeSheet'],
            AfterSheet::class => [self::class, 'afterSheet']
        ];
    }
    private static function getfecha(): string{
        return self::$fecharuta;
    }
    public static function beforeSheet(BeforeSheet $event){

        /*$sheet->prependRow(1, array(
            'prepended', 'prepended'
        ));*/



        //$workSheet = $event->sheet->getDelegate();
        //$workSheet->freezePane('A3');

        $event->sheet->appendRows(array(
            array('','','ENVIOS' ,'',self::$fecharuta  ,'FECHA: '),
            array('','','', '','',''),
            //....
        ), $event);

    }

    public static function afterSheet(AfterSheet $event){



        /*echo 'ROW: ', $cell->getRow(), PHP_EOL;
                   echo 'COLUMN: ', $cell->getColumn(), PHP_EOL;
                   echo 'COORDINATE: ', $cell->getCoordinate(), PHP_EOL;
                   echo 'RAW VALUE: ', $cell->getValue(), PHP_EOL;*/

        //Range Columns

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



        $event->sheet->styleCells('A4',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'ff0000']]]);
        $event->sheet->styleCells('B4',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'EF7D31']]]);
        $event->sheet->styleCells('C4:D4',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'ffeb00']]]);

        $event->sheet->styleCells('E4:I4',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'cde5f5']]]);
        $event->sheet->styleCells('J4',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'ffeb00']]]);
        $event->sheet->styleCells('K4',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'cde5f5']]]);


    }
}
