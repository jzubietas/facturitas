<?php

namespace App\Exports\Templates\Sheets\Envios;

use App\Abstracts\Export;
use App\Exports\Templates\Sheets\AfterSheet;
use App\Exports\Templates\Sheets\Fill;
use App\Models\Cliente;
use App\Models\DetallePedido;
use App\Models\ListadoResultado;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Http\Request;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class PageclienteCuatromesesNodeben extends Export implements WithColumnFormatting,WithColumnWidths
{
    public function collection()
    {
        $ultimos_pedidos=Cliente::activo()
            ->select([
                'clientes.id',
                'clientes.tipo',
                DB::raw("(select dp1.pago from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id and dp1.codigo not like '%-C%' order by dp1.created_at desc limit 1) as pagoultimopedido"),
                DB::raw("(select dp1.pagado from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id and dp1.codigo not like '%-C%' order by dp1.created_at desc limit 1) as pagadoultimopedido"),
                DB::raw("(select dp1.codigo from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id and dp1.codigo not like '%-C%' order by dp1.created_at desc limit 1) as codigoultimopedido"),
                DB::raw("(select DATE_FORMAT(dp1.created_at,'%Y-%m-%d') from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id and dp1.codigo not like '%-C%' order by dp1.created_at desc limit 1) as fechaultimopedido"),
                DB::raw("(select DATE_FORMAT(dp1.created_at,'%m') from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id and dp1.codigo not like '%-C%' order by dp1.created_at desc limit 1) as fechaultimopedido_mes"),
                DB::raw("(select DATE_FORMAT(dp1.created_at,'%Y') from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id and dp1.codigo not like '%-C%' order by dp1.created_at desc limit 1) as fechaultimopedido_anio"),
                DB::raw("(select dp1.pago from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id and dp1.codigo not like '%-C%' order by dp1.created_at desc limit 1) as fechaultimopedido_pago"),
                DB::raw("(select dp1.pagado from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id and dp1.codigo not like '%-C%' order by dp1.created_at desc limit 1) as fechaultimopedido_pagado"),
            ])->get();

        $dosmeses_ini=[];
        for($i=4;$i>0;$i--)
        {
            $dosmeses_ini[]=  now()->startOfMonth()->subMonths($i)->format('Y-m');
        }

        $lista=[];
        foreach ($ultimos_pedidos as $procesada){
            if($procesada->fechaultimopedido)
            {
                $fecha_analizar=Carbon::parse($procesada->fechaultimopedido)->format('Y-m');//->tostring();
                if(in_array($fecha_analizar,$dosmeses_ini))
                {
                    if( in_array($procesada->fechaultimopedido_pagado,["2"]) )
                    {
                        $lista[]=$procesada->id;
                    }
                }
            }
        }

        $data=Cliente::
        join('users as u','u.id','clientes.user_id')
            ->whereIn("clientes.id",$lista)
            ->select([
                'clientes.id as item',
                DB::raw("concat(u.identificador,' ',ifnull(u.letra,'') ) as asesor_identificador"),
                DB::raw("concat(clientes.celular,'-',clientes.icelular)  as celular"),
                DB::raw("(select group_concat(r.num_ruc) from rucs r where r.cliente_id=clientes.id) as rucs"),
                DB::raw("(select case when dp1.pagado=0 then 'DEUDA'
                                        when dp1.pagado=1 then 'DEUDA'
                                        else 'NO DEUDA' end from pedidos dp1
                                        where dp1.estado=1 and dp1.cliente_id=clientes.id and dp1.codigo not like '%-C%' order by dp1.created_at desc limit 1) as deuda"),
                DB::raw("(select dp2.saldo from pedidos a inner join detalle_pedidos dp2 on a.id=dp2.pedido_id
                                        where dp2.estado=1 and a.cliente_id=clientes.id and a.codigo not like '%-C%' order by dp2.created_at desc limit 1) as importeultimopedido"),
                DB::raw("(select DATE_FORMAT(dp3.created_at,'%m') from pedidos a inner join detalle_pedidos dp3 on a.id=dp3.pedido_id
                                        where dp3.estado=1 and a.cliente_id=clientes.id and a.codigo not like '%-C%' order by dp3.created_at desc limit 1) as mesultimopedido"),
                DB::raw("(select dp2.porcentaje from pedidos a inner join detalle_pedidos dp2 on a.id=dp2.pedido_id
                                        where dp2.estado=1 and a.cliente_id=clientes.id and a.codigo not like '%-C%' order by dp2.created_at desc limit 1) as porcentajeultimopedido"),
                DB::raw("(select (r.porcentaje) from porcentajes r where r.cliente_id=clientes.id and r.nombre='FISICO - sin banca' limit 1) as porcentajes_1"),
                DB::raw("(select (r.porcentaje) from porcentajes r where r.cliente_id=clientes.id and r.nombre='FISICO - banca' limit 1) as porcentajes_2"),
                DB::raw("(select (r.porcentaje) from porcentajes r where r.cliente_id=clientes.id and r.nombre='ELECTRONICA - sin banca' limit 1) as porcentajes_3"),
                DB::raw("(select (r.porcentaje) from porcentajes r where r.cliente_id=clientes.id and r.nombre='ELECTRONICA - banca' limit 1) as porcentajes_4"),
            ]);


        if (Auth::user()->rol == User::ROL_LLAMADAS) {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $data = $data->WhereIn("u.identificador", $usersasesores);

        }elseif (Auth::user()->rol == User::ROL_ASESOR) {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $data = $data->WhereIn("u.identificador", $usersasesores);
        }else if (Auth::user()->rol == User::ROL_ENCARGADO) {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn("u.identificador", $usersasesores);
        }elseif (Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {
            $data = $data->Where("u.identificador", '=', 'B');
        }elseif (Auth::user()->rol == "Operario") {
            $asesores = User::whereIN('users.rol', ['Asesor', 'Administrador', 'ASESOR ADMINISTRATIVO'])
                ->where('users.estado', '1')
                ->Where('users.operario', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $pedidos = $data->WhereIn('u.identificador', $asesores);

        }

        $resultado=$data->get();
        $final=[];
        foreach($resultado as $filas)
        {
            if($filas->deuda=='NO DEUDA')
            {
                $final[]=($filas);
            }
        }
        $final_r=collect($final);

        return $final_r;
    }
    public function fields(): array
    {
        return [
            "item"=>"Item"
            ,"asesor_identificador"=>"Asesor"
            ,"celular"=>"Celular"
            ,"rucs"=>"Rucs"
            ,"deuda"=>"Deuda"
            ,"importeultimopedido"=>"Importe ultimo pedido"
            ,"mesultimopedido"=>"Mes ultimo pedido"
            ,"porcentajes_1"=>"Porcentaje FISICO - sin banca"
            ,"porcentajes_2"=>"Porcentaje FISICO - banca"
            ,"porcentajes_3"=>"Porcentaje ELECTRONICA - sin banca"
            ,"porcentajes_4"=>"Porcentaje ELECTRONICA - banca"
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
        return 'Cuatro meses sin pedir- No deben';
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

        $event->sheet->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        $event->sheet->styleCells(
            'B1:G1',
            [
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => 'FFFF0000'],
                    ],
                ]
            ]
        );


    }
}
