<?php

namespace App\Exports\Templates\Sheets\Envios;

use App\Abstracts\Export;
use App\Exports\Templates\Sheets\AfterSheet;
use App\Exports\Templates\Sheets\Fill;
use App\Models\Cliente;
use App\Models\ListadoResultado;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Http\Request;

class PageclienteCuatromeses extends Export implements WithColumnFormatting,WithColumnWidths
{
    public function collection()
    {
        $ultimos_pedidos=Cliente::activo()
            ->select([
                'clientes.id',
                'clientes.tipo',
                DB::raw("(select DATE_FORMAT(dp1.created_at,'%Y-%m-%d') from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id and dp1.codigo not like '%-C%' order by dp1.created_at desc limit 1) as fechaultimopedido"),
                DB::raw("(select DATE_FORMAT(dp1.created_at,'%m') from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id and dp1.codigo not like '%-C%' order by dp1.created_at desc limit 1) as fechaultimopedido_mes"),
                DB::raw("(select DATE_FORMAT(dp1.created_at,'%Y') from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id and dp1.codigo not like '%-C%' order by dp1.created_at desc limit 1) as fechaultimopedido_anio"),
                DB::raw("(select dp1.pago from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id and dp1.codigo not like '%-C%' order by dp1.created_at desc limit 1) as fechaultimopedido_pago"),
                DB::raw("(select dp1.pagado from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id and dp1.codigo not like '%-C%' order by dp1.created_at desc limit 1) as fechaultimopedido_pagado"),
            ])->get();

        //$ultimos=$ultimos_pedidos->whereNotNull('fechaultimopedido')->get();

        $dosmeses_ini=now()->startOfMonth()->subMonths(4)->format('Y-m');//01 11
        $dosmeses_fin=now()->endOfMonth()->subMonths(1)->format('Y-m');
        $lista=[];
        foreach ($ultimos_pedidos as $procesada){
            if($procesada->fechaultimopedido!=null)
            {
                $fecha_analizar=Carbon::parse($procesada->fechaultimopedido)->format('Y-m');
                if($fecha_analizar==$dosmeses_ini || $fecha_analizar==$dosmeses_fin)
                {
                    //if(in_array($procesada->fechaultimopedido_pago,["0","1"]))
                    {
                        //if(in_array($procesada->fechaultimopedido_pagado,["0","1"]))
                        {
                            $lista[]=$procesada->id;
                        }
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
                                        where dp1.estado=1 and dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as deuda"),
                DB::raw("(select dp2.saldo from pedidos a inner join detalle_pedidos dp2 on a.id=dp2.pedido_id
                                        where dp2.estado=1 and a.cliente_id=clientes.id order by dp2.created_at desc limit 1) as importeultimopedido"),
                DB::raw("(select DATE_FORMAT(dp3.created_at,'%m') from pedidos a inner join detalle_pedidos dp3 on a.id=dp3.pedido_id
                                        where dp3.estado=1 and a.cliente_id=clientes.id order by dp3.created_at desc limit 1) as mesultimopedido"),
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

        return $data->get();
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
            ,"mesultimopedido"=>"Mes ultimo pedido",
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

        ];
    }
    public function title(): string
    {
        return 'Dos meses sin pedir';
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


        /*$style_recurrente = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_cabeceras)
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


        }*/

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
