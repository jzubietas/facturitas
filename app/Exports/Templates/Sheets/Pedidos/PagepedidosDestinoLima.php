<?php

namespace App\Exports\Templates\Sheets\Pedidos;

use App\Abstracts\Export;
use App\Exports\Templates\Sheets\Envios\AfterSheet;
use App\Exports\Templates\Sheets\Envios\Fill;
use App\Models\Cliente;
use App\Models\DireccionGrupo;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});
class PagepedidosDestinoLima extends Export implements WithColumnFormatting,WithColumnWidths
{
    public function collection()
    {
        $pedidos = DireccionGrupo::join('gasto_envios as de', 'direccion_grupos.id', 'de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.estado', '1')
            ->where('direccion_grupos.destino', 'LIMA')
            ->where(DB::raw('DATE(direccion_grupos.created_at)'), self::$fecharuta)
            ->whereNotIn('direccion_grupos.condicion_envio_code', [Pedido::ENTREGADO_SIN_SOBRE_OPE_INT, Pedido::ENTREGADO_SIN_SOBRE_CLIENTE_INT])
            ->select([
                'c.celular as correlativo',
                'u.identificador as identificador',
                'direccion_grupos.destino',
                'de.cantidad',
                'direccion_grupos.codigos',
                'direccion_grupos.producto',
                'de.tracking as direccion',
                'de.foto as referencia',
                'c.nombre as nombre_cli',
                'direccion_grupos.created_at as fecha',
                'direccion_grupos.distribucion',
                'direccion_grupos.condicion_sobre',
            ]);

        $resultado=$pedidos->get();
        return $resultado;
    }
    public function fields(): array
    {
        return [
            "correlativo"=>"correlativo"
            ,"identificador"=>"identificador"
            ,"destino"=>"destino"
            ,"cantidad"=>"cantidad"
            ,"codigos"=>"codigos"
            ,"producto"=>"producto"
            ,"direccion"=>"direccion"
            ,"referencia"=>"referencia"
            ,"nombre_cli"=>"nombre_cli"
            ,"fecha"=>"fecha"
            ,"distribucion"=>"distribucion"
            ,"condicion_sobre"=>"condicion_sobre"
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
            ,'H' => 8//porcentaje
            ,'I' => 8//porcentaje
            ,'J' => 8//porcentaje
            ,'K' => 8//porcentaje
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
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_TEXT,
        ];
    }
    public function title(): string
    {
        return 'Destino Lima';
    }
    public function map($model): array
    {
        //$model->Periodo=strval(str_pad($model->Periodo,2,"0"));
        //$model->porcentajes = collect($model->porcentajes)->join("\n");
        return parent::map($model);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => [self::class, 'afterSheet']
        ];
    }

    public static function afterSheet(AfterSheet $event){

        $color_A1='e18b16';

        $event->sheet->getStyle('C')->getAlignment()->setWrapText(true);
        $event->sheet->getStyle('E')->getAlignment()->setWrapText(true);

        $event->sheet->styleCells(
            'A1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => $color_A1]
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
                    'color' => ['argb' => $color_A1]
                ]
            ]
        );

    }
}
