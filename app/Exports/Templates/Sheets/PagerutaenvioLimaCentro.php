<?php

namespace App\Exports\Templates\Sheets;


use App\Abstracts\Export;
use App\Abstracts\Exports\PageRutasEnvioLima;
use App\Models\Cliente;
use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class PagerutaenvioLimaCentro extends PageRutasEnvioLima
{



    public function collection()
    {
        $pedidos_lima = DireccionGrupo::join('direccion_envios as de', 'direccion_grupos.id', 'de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.estado', '1')
            ->where('direccion_grupos.distribucion', 'CENTRO')
            ->whereNotIn('direccion_grupos.condicion_envio_code', [Pedido::ENTREGADO_SIN_SOBRE_OPE_INT, Pedido::ENTREGADO_SIN_SOBRE_CLIENTE_INT])
            /*->where(function($query){
                $query->where('direccion_grupos.distribucion','=','')->orWhereNull('direccion_grupos.distribucion');
            })*/
            ->where('direccion_grupos.destino', 'LIMA')
            ->where(DB::raw('DATE(direccion_grupos.created_at)'), self::$fecharuta)
            ->select(
                [
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
                ]
            );

        $pedidos = $pedidos_lima;
        return $pedidos->get();
    }

    public function title(): string
    {
        return 'Lima CENTRO ' . self::$fecharuta;
    }

    public function map($model): array
    {
        $model->num_registros = $this->contador;
        $this->contador++;
        //$model->Periodo=strval(str_pad($model->Periodo,2,"0"));
        return parent::map($model);
    }

}
