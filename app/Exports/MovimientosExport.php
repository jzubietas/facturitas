<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Models\MovimientoBancario;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
//use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class MovimientosExport implements FromView, ShouldAutoSize//FromQuery, ShouldAutoSize,WithMapping ,WithColumnFormatting//WithHeadings
{
    use Exportable;

    public function movimientos($request) {
        $movimientos = MovimientoBancario::leftjoin("pagos as p","movimiento_bancarios.cabpago","p.id")            
            ->select(
                'movimiento_bancarios.id',
                'movimiento_bancarios.banco',
                'movimiento_bancarios.titular',
                'movimiento_bancarios.importe',
                'movimiento_bancarios.tipo',
                'movimiento_bancarios.descripcion_otros as otros',
                DB::raw('(select DATE_FORMAT(dpa.fecha, "%d-%m-%Y")  from movimiento_bancarios dpa where dpa.id=movimiento_bancarios.id) as fecha'),
                'movimiento_bancarios.pago',
                'p.id as pagoid',
                DB::raw(" (select (us.identificador) from users us where us.id=p.user_id) as users "),
                DB::raw(" (CASE WHEN (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=p.id and dpago.estado in (1) )>1 then 'V' else 'I' end) as cantidad_voucher "),

                DB::raw(" (CASE WHEN (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=p.id and ppedidos.estado in (1)  )>1 then 'V' else 'I' end) as cantidad_pedido "),
                DB::raw(" (select count(dp.id) from detalle_pagos dp where dp.pago_id=p.id) as cant "),
            )
            ->whereBetween(DB::raw('DATE(movimiento_bancarios.fecha)'), [$request->desde, $request->hasta]) 
            ->orderBy('movimiento_bancarios.fecha', 'DESC')
            ->get();

        $this->movimientos = $movimientos;
        return $this;

    }


    /*public function query() {
        $movimientos = MovimientoBancario::leftjoin("pagos as p","movimiento_bancarios.cabpago","p.id")            
            ->select(
                'movimiento_bancarios.id',
                'movimiento_bancarios.banco',
                'movimiento_bancarios.titular',
                'movimiento_bancarios.importe',
                'movimiento_bancarios.tipo',
                'movimiento_bancarios.descripcion_otros as otros',
                DB::raw('(select DATE_FORMAT(dpa.fecha, "%d-%m-%Y")  from movimiento_bancarios dpa where dpa.id=movimiento_bancarios.id) as fecha'),
                'movimiento_bancarios.pago',
                'p.id as pagoid',
                DB::raw(" (select (us.identificador) from users us where us.id=p.user_id) as users "),
                DB::raw(" (CASE WHEN (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=p.id and dpago.estado in (1) )>1 then 'V' else 'I' end) as cantidad_voucher "),

                DB::raw(" (CASE WHEN (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=p.id and ppedidos.estado in (1)  )>1 then 'V' else 'I' end) as cantidad_pedido "),
                DB::raw(" (select count(dp.id) from detalle_pagos dp where dp.pago_id=p.id) as cant "),
            )
            //->whereBetween(DB::raw('DATE(movimiento_bancarios.fecha)'), [$request->desde, $request->hasta]) 
            ->get();

        $this->movimientos = $movimientos;
        return $this;
    }*/

    /*public function collection()
    {
        return MovimientoBancario::all();

    }*/

    /*public function headings(): array
    {
        return [
          'ITEM',
          'ID',
          'BANCO',
          'TITULAR',
          'IMPORTE',
          'TIPO',
          'FECHA',
          'ESTADO',
          'CODIGO DE VOUCHER',
          'CANT.DE VOUCHER'
        ];
    }*/

    /*public function map($row): array{
        return [
            $row->id,
            $row->banco,
            $row->titular,
            $row->importe,
            $row->tipo,
            $row->otros,
            Date::dateTimeToExcel(($row->fecha)),
            $row->pago,
            $row->pagoid,
            $row->users,
            $row->cantidad_voucher,
            $row->cantidad_pedido,
            $row->cant,            
        ];
    }*/

    /*public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }*/

    /*public function view(): View {
        return view('movimientos.excel.index', [
            'movimientos1'=> $this->movimientos1,
        ]);
    }*/

    /*public function actions(Request $request)
{
    return [
        new DownloadExcel(),
    ];
}*/

        public function view(): View {
            return view('movimientos.excel.index', [
                'movimientos1'=> $this->movimientos
            ]);
        } 
}