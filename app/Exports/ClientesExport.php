<?php

namespace App\Exports;

use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClientesExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function clientes1($request) {

        // $dateM = Carbon::now()->format('m');
        // $dateY = Carbon::now()->format('Y');

        $clientes1 = Cliente::join('users as u', 'clientes.user_id', 'u.id')//CLIENTES CON PEDIDOS
        ->join('pedidos as p', 'clientes.id', 'p.cliente_id')
        ->select('clientes.id',
                'clientes.nombre',
                'clientes.icelular', 
                'clientes.celular', 
                'clientes.estado', 
                'u.identificador as id_asesor',
                'u.name as nombre_asesor',
                'clientes.provincia',
                'clientes.distrito',
                'clientes.direccion',
                'clientes.referencia',
                'clientes.dni',
                'clientes.deuda',
                DB::raw('DATE_FORMAT(MAX(p.created_at), "%d/%m/%Y") as fecha'),
                DB::raw('DATE_FORMAT(MAX(p.created_at), "%d") as dia'),
                DB::raw('DATE_FORMAT(MAX(p.created_at), "%m") as mes'),
                DB::raw('DATE_FORMAT(MAX(p.created_at), "%Y") as anio')
                )
        ->where('clientes.estado','1')
        ->where('clientes.tipo','1')
        ->where('clientes.pidio','1')
        ->whereBetween(DB::raw('DATE(clientes.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
        ->groupBy(
            'clientes.id',
            'clientes.nombre',
            'clientes.icelular', 
            'clientes.celular', 
            'clientes.estado', 
            'u.identificador',
            'u.name',
            'clientes.provincia',
            'clientes.distrito',
            'clientes.direccion',
            'clientes.referencia',
            'clientes.dni',
            'clientes.deuda',
        )
        ->get();
        $this->clientes1 = $clientes1;
        return $this;
    }

    public function clientes2($request) {
        $clientes2 = Cliente::join('users as u', 'clientes.user_id', 'u.id')//CLIENTES SIN PEDIDOS
        ->select('clientes.id',
                'clientes.nombre',
                'clientes.celular', 
                'clientes.estado', 
                'u.identificador as id_asesor',
                'u.name as nombre_asesor',
                'clientes.provincia',
                'clientes.distrito',
                'clientes.direccion',
                'clientes.referencia',
                'clientes.dni',
                'clientes.deuda'
                )
        ->where('clientes.estado','1')
        ->where('clientes.tipo','1')
        ->where('clientes.pidio','0')
        ->whereBetween(DB::raw('DATE(clientes.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
        ->groupBy(
            'clientes.id',
            'clientes.nombre',
            'clientes.celular', 
            'clientes.estado', 
            'u.identificador',
            'u.name',
            'clientes.provincia',
            'clientes.distrito',
            'clientes.direccion',
            'clientes.referencia',
            'clientes.dni',
            'clientes.deuda',
        )
        ->get();

        $this->clientes2 = $clientes2;
        return $this;        
    }
    public function view(): View {
        return view('clientes.excel.index', [
            'clientes1'=> $this->clientes1,
            'clientes2' => $this->clientes2
        ]);
    }

}