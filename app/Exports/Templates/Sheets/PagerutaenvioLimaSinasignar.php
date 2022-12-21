<?php

namespace App\Exports\Templates\Sheets;


use App\Abstracts\Export;
use App\Models\DireccionGrupo;
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
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use function PHPUnit\Framework\returnSelf;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class PagerutaenvioLimaSinasignar extends Export implements WithEvents,WithColumnWidths
{
    public static $fecharuta='';
    public function __construct($ids)
    {
        parent::__construct();
        self::$fecharuta=$ids;
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
            ->select(
                'direccion_grupos.correlativo',
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
            "correlativo"=>"Correlativo"
            //,"identificador"=>"Asersor"
             ,"nombre_cli" => "Nombre cliente"
            ,"codigos"=>"Codigos"
            ,"producto"=>"Producto"
            ,"cantidad"=>"Cantidad"
            ,"nombre"=>"Nombre"
            ,"direccion"=>"Direccion"
            ,"referencia"=>"Referencia"
            ,"distrito"=>"Distrito"
            ,"observacion"=>"Observacion"
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
        return parent::map($model);
    }
    public function columnWidths(): array
    {
        return [
            'A' => 8
            ,'B' => 30
            ,'C' => 30
            ,'D' => 30
            ,'E' => 10
            ,'F' => 30
            ,'G' => 30
            ,'H' => 30
            ,'I' => 30
            ,'J' => 30
            ,'K' => 8
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
        $event->sheet->appendRows(array(
            array('', self::$fecharuta),
            array('', ''),
            array('', ''),
            //....
        ), $event);

    }

    public static function afterSheet(AfterSheet $event){

        /*echo 'ROW: ', $cell->getRow(), PHP_EOL;
                   echo 'COLUMN: ', $cell->getColumn(), PHP_EOL;
                   echo 'COORDINATE: ', $cell->getCoordinate(), PHP_EOL;
                   echo 'RAW VALUE: ', $cell->getValue(), PHP_EOL;*/

        //Range Columns


        $event->sheet->styleCells('A3',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'ff0000']]]);
        $event->sheet->styleCells('B3:C3',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'ffeb00']]]);
        $event->sheet->styleCells('D3:H3',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'cde5f5']]]);
        $event->sheet->styleCells('I3',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'ffeb00']]]);
        $event->sheet->styleCells('J3',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'cde5f5']]]);

        return [
            AfterSheet::class => function(AfterSheet $event) {
                $workSheet = $event->sheet->getDelegate();
                $workSheet->freezePane('A2');
            },
        ];s

    }
}
