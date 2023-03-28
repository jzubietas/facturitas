<?php

namespace App\Exports\Templates\Sheets\Envios;

use App\Abstracts\Export;
use App\Exports\Templates\Sheets\AfterSheet;
use App\Exports\Templates\Sheets\Fill;
use App\Models\Cliente;
use App\Models\DireccionGrupo;
use App\Models\ListadoResultado;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Http\Request;
class ListadoOlva extends Export implements WithColumnFormatting,WithColumnWidths
{
    public function collection()
    {
        $ultimos_pedidos = DireccionGrupo::join('clientes', 'clientes.id', 'direccion_grupos.cliente_id')
            ->join('users', 'users.id', 'direccion_grupos.user_id')
            ->activo()
            ->whereIn('direccion_grupos.condicion_envio_code', [
                Pedido::RECEPCIONADO_OLVA_INT,
                Pedido::EN_CAMINO_OLVA_INT,
                Pedido::EN_TIENDA_AGENTE_OLVA_INT,
                Pedido::NO_ENTREGADO_OLVA_INT,
            ])
            ->whereNotIn('direccion_grupos.courier_estado', ["ENTREGADO"])
            ->select([
                'direccion_grupos.correlativo as item',
                'direccion_grupos.codigos',
                'direccion_grupos.id',
                "clientes.nombre as cliente_nombre",
                "clientes.condicion_envio_at",
                DB::raw("(select empresa from rucs ru where ru.cliente_id=clientes.id limit 1) as razonsocial"),
                DB::raw("(select env_tracking from pedidos pe where pe.direccion_grupo=direccion_grupos.id limit 1) as pe_env_tracking"),
                'direccion_grupos.referencia',
                'direccion_grupos.courier_estado',
            ]);

        add_query_filtros_por_roles_pedidos($ultimos_pedidos, 'users.identificador');


        return $ultimos_pedidos->get();
    }
    public function fields(): array
    {
        return [
            "item"=>"Item"
            ,"codigos"=>"Codigo"
            ,"id"=>"Id"
            ,"cliente_nombre"=>"Cliente"
            ,"condicion_envio_at"=>"Fecha de envio"
            ,"razonsocial"=>"Razon Social"
            ,"pe_env_tracking"=>"Tracking"
            ,"referencia"=>"Numero de registro"
            ,"courier_estado"=>"Estado Envio"
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 8//item
            ,'B' => 8//codigos
            ,'C' => 8//id
            ,'D' => 8//cliente_nombre
            ,'E' => 8//condicion_envio_at
            ,'F' => 8//razonsocial
            ,'G' => 8//pe_env_tracking
            ,'H' => 8//referencia
            ,'I' => 8//courier_estado
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
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,

        ];
    }
    public function title(): string
    {
        return 'Listado de Olva';
    }
    public function map($model): array
    {
        //$model->Periodo=strval(str_pad($model->Periodo,2,"0"));
        return parent::map($model);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => [self::class, 'afterSheet']
        ];
    }

    public static function afterSheet(AfterSheet $event){

        $color_cabeceras='a9def9';
    }
}
