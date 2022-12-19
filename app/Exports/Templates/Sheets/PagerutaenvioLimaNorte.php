<?php

namespace App\Exports\Templates\Sheets;


use App\Abstracts\Export;
use App\Models\DireccionGrupo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PagerutaenvioLimaNorte  extends Export
{
    public $fecharuta;
    public function __construct($fecharuta)
    {
        parent::__construct();
        $this->fecharuta=$fecharuta;
    }
    public function collection()
    {
        $pedidos_lima = DireccionGrupo::join('direccion_envios as de','direccion_grupos.id','de.direcciongrupo')
            ->join('clientes as c', 'c.id', 'de.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.estado','1')
            ->where('direccion_grupos.distribucion','NORTE')
            /*->where(function($query){
                $query->where('direccion_grupos.distribucion','=','')->orWhereNull('direccion_grupos.distribucion');
            })*/
            ->where('direccion_grupos.destino','LIMA')
            ->where(DB::raw('DATE(direccion_grupos.created_at)'), $this->fecharuta)
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
            ,"identificador"=>"Asersor"
            ,"destino"=>"Destino"
            ,"celular"=>"Celular"
            ,"nombre"=>"Nombre"
            ,"cantidad"=>"Cantidad"
            ,"codigos"=>"Codigos"
            ,"producto"=>"Producto"
            ,"direccion"=>"Direccion"
            ,"referencia"=>"Referencia"
            ,"observacion"=>"Observacion"
            ,"distrito"=>"Distrito"
            ,"fecha"=>"Fecha"
            ,"distribucion"=>"Distribucion"
            ,"condicion_sobre"=>"Condicion"
        ];
    }

    public function title(): string
    {
        return 'Lima NORTE';
    }
    public function map($model): array
    {
        //$model->Periodo=strval(str_pad($model->Periodo,2,"0"));
        return map($model);
    }
    public function columnWidths(): array
    {
        return [
            'A' => 8
            ,'B' => 8
            ,'C' => 8
            ,'D' => 8
            ,'E' => 8
            ,'F' => 8
            ,'G' => 8
            ,'H' => 8
            ,'I' => 8
            ,'J' => 8
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
}
