<?php

namespace App\Exports\Templates\Sheets\Clientes;

use App\Abstracts\Export;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;

use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});
class PageclienteReporteMultiple extends Export implements WithStyles, WithColumnFormatting, FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithColumnWidths
{
    private $situacion='';
    private  $anio='';
    public function __construct($situacion,$anio)
    {
        parent::__construct();
        $this->situacion=$situacion;
        $this->anio=$anio;
    }
    public function collection()
    {
        $clientes=Cliente::activo()
            //->join('users as u', 'clientes.user_id', 'u.id')
            ->select([
                'clientes.id',
                'clientes.tipo',
                'clientes.user_clavepedido as asesor',
                //'u.identificador as asesor',
                'clientes.nombre',
                'clientes.dni',
                'clientes.icelular',
                'clientes.celular',
                'clientes.provincia',
                'clientes.distrito',
                'clientes.direccion',
                'clientes.referencia',
                'clientes.estado',
                'clientes.deuda',
                DB::raw(" (CASE WHEN clientes.deuda=1 then 'DEBE' else 'CANCELADO' end) as deposito "),
                'clientes.pidio',
                DB::raw("(select DATE_FORMAT(dp1.created_at,'%d-%m-%Y %h:%i:%s') from pedidos dp1 where dp1.cliente_id=clientes.id and dp1.estado=1 order by dp1.created_at desc limit 1) as fecha"),
                DB::raw("(select DATE_FORMAT(dp0.created_at,'%m') from pedidos dp0 where dp0.cliente_id=clientes.id and dp0.estado=1 order by dp0.created_at desc limit 1) as fechaultimopedido_dia"),
                DB::raw("(select DATE_FORMAT(dp2.created_at,'%m') from pedidos dp2 where dp2.cliente_id=clientes.id and dp2.estado=1 order by dp2.created_at desc limit 1) as fechaultimopedido_mes"),
                DB::raw("(select DATE_FORMAT(dp3.created_at,'%Y') from pedidos dp3 where dp3.cliente_id=clientes.id and dp3.estado=1 order by dp3.created_at desc limit 1) as fechaultimopedido_anio"),
                DB::raw(" (select (dp.codigo) from pedidos dp where dp.cliente_id=clientes.id and dp.estado=1 order by dp.created_at desc limit 1) as codigo "),
                'clientes.situacion',
                DB::raw("(select dp1.pago from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fechaultimopedido_pago"),
                DB::raw("(select dp1.pagado from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fechaultimopedido_pagado"),
                DB::raw("(select (r.porcentaje) from porcentajes r where r.cliente_id=clientes.id and r.nombre='FISICO - sin banca' limit 1) as porcentajefsb"),
                DB::raw("(select (r.porcentaje) from porcentajes r where r.cliente_id=clientes.id and r.nombre='FISICO - banca' limit 1) as porcentajefb"),
                DB::raw("(select (r.porcentaje) from porcentajes r where r.cliente_id=clientes.id and r.nombre='ELECTRONICA - sin banca' limit 1) as porcentajeesb"),
                DB::raw("(select (r.porcentaje) from porcentajes r where r.cliente_id=clientes.id and r.nombre='ELECTRONICA - banca' limit 1) as porcentajeeb"),
            ])
            ->where('clientes.estado','1')
            ->whereNotIn('clientes.user_clavepedido',['B'])
            ->where('clientes.tipo','1');
            //->whereNotNull('clientes.situacion');
        $cal_sit=$this->situacion;
        //$clientes=$clientes->limit(10);
        switch($cal_sit)
            {
                case 'ABANDONO':
                    $clientes=$clientes->whereIn('clientes.situacion',['ABANDONO','ABANDONO RECIENTE']);
                    break;
                case 'RECURENTE':
                    $clientes=$clientes->whereIn('clientes.situacion',['RECURRENTE']);
                    break;
                case 'NUEVO':
                    $clientes=$clientes->whereIn('clientes.situacion',['NUEVO']);
                    break;
                case 'RECUPERADO':
                    $clientes=$clientes->whereIn('clientes.situacion',['RECUPERADO']);
                    break;
                case 'RECUPERADO ABANDONO':
                    $clientes=$clientes->whereIn('clientes.situacion',['RECUPERADO ABANDONO']);
                    break;
                case 'RECUPERADO RECIENTE':
                    $clientes=$clientes->whereIn('clientes.situacion',['RECUPERADO RECIENTE']);
                    break;
                case 'ABANDONO RECIENTE':
                    $clientes=$clientes->whereIn('clientes.situacion',['ABANDONO RECIENTE']);
                    break;
            case 'ACTIVO':
                $clientes=$clientes->whereIn('clientes.situacion',['ACTIVO']);
                break;
                default:break;
            }


        if (Auth::user()->rol == "Llamadas")
        {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $clientes = $clientes->WhereIn("u.identificador", $usersasesores);
        }
        elseif (Auth::user()->rol == "Asesor")
        {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $clientes = $clientes->WhereIn("u.identificador", $usersasesores);
        }
        else if (Auth::user()->rol == "Encargado")
        {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $clientes = $clientes->WhereIn("u.identificador", $usersasesores);
        }

        return $clientes->get();
    }
    public function fields(): array
    {
        return [
            "item"=>"Item"
            ,"id"=>"Id"
            ,"asesor"=>"Asesor"
            ,"nombre"=>"Nombre"
            ,"dni"=>"DNI"
            ,"celular"=>"Celular"
            ,"icelular"=>"Letra Celular"
            ,"provincia"=>"Provincia"
            ,"distrito"=>"Distrito"
            ,"direccion"=>"Direccion"
            ,"referencia"=>"Referencia"
            ,"porcentajefsb"=>"Fisico sin banca"
            ,"porcentajefb"=>"Fisico con banca"
            ,"porcentajeesb"=>"Electronica sin banca"
            ,"porcentajeeb"=>"Electronica con banca"
            ,"deuda"=>"Deuda"
            ,"deposito"=>"Deposito"
            ,"fecha"=>"Fecha"
            ,"fechaultimopedido_dia"=>"Dia"
            ,"fechaultimopedido_mes"=>"Mes"
            ,"fechaultimopedido_anio"=>"Año"
            ,"codigo"=>"Codigo"
            ,"situacion"=>"Situacion"
            ,"estadopedido"=>"Estado pedido"
            ,"pidio"=>"Pidio"
            ,"estado"=>"Estado"
            , "eneroa"=> sprintf("Enero %s", ($this->anio))
            ,"enerob"=>"Enero ".(intval($this->anio)+1)
            ,"febreroa"=>"Febrero ".($this->anio),"febrerob"=>"Febrero ".(intval($this->anio)+1)
            ,"marzoa"=>"Marzo ".($this->anio),"marzob"=>"Marzo ".(intval($this->anio)+1)
            ,"abrila"=>"Abril ".($this->anio),"abrilb"=>"Abril ".(intval($this->anio)+1)
            ,"mayoa"=>"Mayo ".($this->anio),"mayob"=>"Mayo ".(intval($this->anio)+1)
            ,"junioa"=>"Junio ".($this->anio),"juniob"=>"Junio ".(intval($this->anio)+1)
            ,"julioa"=>"Julio ".($this->anio),"juliob"=>"Julio ".(intval($this->anio)+1)
            ,"agostoa"=>"Agosto ".($this->anio),"agostob"=>"Agosto ".(intval($this->anio)+1)
            ,"setiembrea"=>"Setiembre ".($this->anio),"setiembreb"=>"Setiembre ".(intval($this->anio)+1)
            ,"octubrea"=>"Octubre ".($this->anio),"octubreb"=>"Octubre ".(intval($this->anio)+1)
            ,"noviembrea"=>"Noviembre ".($this->anio),"noviembreb"=>"Noviembre ".(intval($this->anio)+1)
            ,"diciembrea"=>"Diciembre ".($this->anio),"diciembreb"=>"Diciembre ".(intval($this->anio)+1)
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
            ,'H' => 8//mes
            ,'I' => 8//mes
            ,'J' => 8//mes
            ,'K' => 8//mes
            ,'L' => 8//mes
            ,'M' => 8//mes
            ,'N' => 8//mes
            ,'O' => 8//mes
            ,'P' => 8//mes
            ,'Q' => 8//mes
            ,'R' => 8//mes
            ,'S' => 8//mes
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
            'S' => NumberFormat::FORMAT_TEXT,
            'T' => NumberFormat::FORMAT_TEXT,
            'U' => NumberFormat::FORMAT_TEXT,

        ];
    }
    public function title(): string
    {
        return 'CLIENTES SITUACION '.($this->anio).' '.(intval($this->anio)+1). ' :: '.($this->situacion);
    }
    public function map($model): array
    {
        $model->deuda=( ($model->deuda==1)? 'SI':'NO' );
        $model->anioa=$this->anio;
        $model->aniob=( intval($this->anio) +1);
        $model->eneroa=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->anioa)
            ->where(DB::raw('MONTH(created_at)'), '1')
            ->count();
        $model->enerob=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->aniob)
            ->where(DB::raw('MONTH(created_at)'), '1')
            ->count();
        $model->febreroa=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->anioa)
            ->where(DB::raw('MONTH(created_at)'), '2')
            ->count();
        $model->febrerob=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->aniob)
            ->where(DB::raw('MONTH(created_at)'), '2')
            ->count();
        $model->marzoa=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->anioa)
            ->where(DB::raw('MONTH(created_at)'), '3')
            ->count();
        $model->marzob=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->aniob)
            ->where(DB::raw('MONTH(created_at)'), '3')
            ->count();
        $model->abrila=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->anioa)
            ->where(DB::raw('MONTH(created_at)'), '4')
            ->count();
        $model->abrilb=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->aniob)
            ->where(DB::raw('MONTH(created_at)'), '4')
            ->count();
        $model->mayoa=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->anioa)
            ->where(DB::raw('MONTH(created_at)'), '5')
            ->count();
        $model->mayob=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->aniob)
            ->where(DB::raw('MONTH(created_at)'), '5')
            ->count();
        $model->junioa=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->anioa)
            ->where(DB::raw('MONTH(created_at)'), '6')
            ->count();
        $model->juniob=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->aniob)
            ->where(DB::raw('MONTH(created_at)'), '6')
            ->count();
        $model->julioa=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->anioa)
            ->where(DB::raw('MONTH(created_at)'), '7')
            ->count();
        $model->juliob=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->aniob)
            ->where(DB::raw('MONTH(created_at)'), '7')
            ->count();
        $model->agostoa=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->anioa)
            ->where(DB::raw('MONTH(created_at)'), '8')
            ->count();
        $model->agostob=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->aniob)
            ->where(DB::raw('MONTH(created_at)'), '8')
            ->count();
        $model->setiembrea=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->anioa)
            ->where(DB::raw('MONTH(created_at)'), '9')
            ->count();
        $model->setiembreb=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->aniob)
            ->where(DB::raw('MONTH(created_at)'), '9')
            ->count();
        $model->octubrea=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->anioa)
            ->where(DB::raw('MONTH(created_at)'), '10')
            ->count();
        $model->octubreb=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->aniob)
            ->where(DB::raw('MONTH(created_at)'), '10')
            ->count();
        $model->noviembrea=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->anioa)
            ->where(DB::raw('MONTH(created_at)'), '11')
            ->count();
        $model->noviembreb=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->aniob)
            ->where(DB::raw('MONTH(created_at)'), '11')
            ->count();
        $model->diciembrea=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->anioa)
            ->where(DB::raw('MONTH(created_at)'), '12')
            ->count();
        $model->diciembreb=Pedido::where('estado', '1')->where('cliente_id', $model->id)
            ->whereYear(DB::raw('Date(created_at)'), $model->aniob)
            ->where(DB::raw('MONTH(created_at)'), '12')
            ->count();

        return parent::map($model);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => [self::class, 'afterSheet']
        ];
    }

    public static function afterSheet(AfterSheet $event){

        $color_R = 'ff5733';
        $color__ = 'fcf8f2';
        $color_A = 'faf01c';
        $color_C = '1cfaf3';
        $color_AR = '1cfaf3';
        $color_N = 'e18b16';
        $color_V = '6acf0c';

        $style_R = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_R)
            )
        );
        $style_AR = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_AR)
            )
        );
        $style__ = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color__)
            )
        );
        $style_A = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_A)
            )
        );
        $style_V = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_V)
            )
        );

        /*$event->sheet->styleCells(
            'A1:AX1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => '6acf0c ']
                ]
            ]
        );*/

        $event->sheet->styleCells(
            'L1:O1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'cedb40']
                ]
            ]
        );

        $row_cell_=23;
        $letter_cell='W';
        foreach ($event->sheet->getRowIterator() as $row)
        {
            if($row->getRowIndex()==1)continue;
            //$event->sheet->getStyle($letter_cell . $row->getRowIndex())->applyFromArray($style_V);
            if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='RECURRENTE')
            {
                //$event->sheet->getStyle($letter_cell . $row->getRowIndex())->applyFromArray($style_V);
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style_R);
            }
            else if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='ABANDONO')
            {
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style_A);
            }else if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='NULO')
            {
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style__);
            }else if($event->sheet->getCellByColumnAndRow($row_cell_,$row->getRowIndex())->getValue()=='ABANDONO RECIENTE')
            {
                $event->sheet->getStyle($letter_cell.$row->getRowIndex())->applyFromArray($style_AR);
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
    public function styles(Worksheet $sheet)
    {
        // TODO: Implement styles() method.
        return [
            'A' => [
                'alignment' => [
                    'wrapText' => true,
                ],
            ],
            'B' => [
                'alignment' => [
                    'wrapText' => true,
                ],
            ],
            'C' => [
                'alignment' => [
                    'wrapText' => true,
                ],
            ],
        ];
    }

}
