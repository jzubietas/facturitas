<?php

namespace App\Exports\Templates\Sheets;


use App\Abstracts\Export;
use App\Models\DireccionGrupo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});
class PagerutaenvioProvincia  extends Export  implements WithEvents,WithColumnWidths
{
    public $fecharuta;
    public function __construct($fecharuta)
    {
        parent::__construct();
        $this->fecharuta=$fecharuta;
    }
    public function collection()
    {


        $pedidos_lima = DireccionGrupo::join('gasto_envios as de','direccion_grupos.id','de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.estado','1')
            //->where('direccion_grupos.distribucion','SUR')
            /*->where(function($query){
                $query->where('direccion_grupos.distribucion','=','')->orWhereNull('direccion_grupos.distribucion');
            })*/
            ->where('direccion_grupos.destino','PROVINCIA')
            ->where(DB::raw('DATE(direccion_grupos.created_at)'), $this->fecharuta)
            ->select(
                'direccion_grupos.correlativo',
                'u.identificador as identificador',
                'direccion_grupos.destino',
                DB::raw(" (select '') as celular "),
                DB::raw(" (select '') as nombre "),
                'de.cantidad',
                'direccion_grupos.codigos',
                'direccion_grupos.producto',
                'de.tracking as direccion',
                'de.foto as referencia',
                DB::raw(" (select '') as observacion "),
                DB::raw(" (select '') as distrito "),
                'c.nombre as nombre_cli',
                'direccion_grupos.created_at as fecha',
                'direccion_grupos.distribucion',
                'direccion_grupos.condicion_sobre',
            );

        $pedidos = $pedidos_lima;
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
            ,"direccion"=>"Tracking"
            ,"referencia"=>"Adjunto"
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
        return 'PROVINCIA';
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
            ,'B' => 40
            ,'C' => 40
            ,'D' => 90
            ,'E' => 10
            ,'F' => 50
            ,'G' => 60
            ,'H' => 60
            ,'I' => 40
            ,'J' => 80
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
            AfterSheet::class => [self::class, 'afterSheet']
        ];
    }

    public static function afterSheet(AfterSheet $event){

        /*echo 'ROW: ', $cell->getRow(), PHP_EOL;
                   echo 'COLUMN: ', $cell->getColumn(), PHP_EOL;
                   echo 'COORDINATE: ', $cell->getCoordinate(), PHP_EOL;
                   echo 'RAW VALUE: ', $cell->getValue(), PHP_EOL;*/

        //Range Columns


        $event->sheet->styleCells('A1',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'ff0000']]]);
        $event->sheet->styleCells('B1:C1',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'ffeb00']]]);
        $event->sheet->styleCells('D1:H1',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'cde5f5']]]);
        $event->sheet->styleCells('I1',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'ffeb00']]]);
        $event->sheet->styleCells('J1',['fill' => ['fillType' => Fill::FILL_SOLID,'color' => ['rgb' => 'cde5f5']]]);


    }
}
