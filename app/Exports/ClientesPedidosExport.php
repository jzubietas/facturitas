<?php

namespace App\Exports;

use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class ClientesPedidosExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        /* $clientes = Cliente::
        join('pedidos as p', 'clientes.id', 'p.cliente_id')
        ->select('clientes.id',
                'clientes.nombre',
                'clientes.celular', 
                'clientes.estado',
                'clientes.provincia',
                'clientes.distrito',
                'clientes.direccion',
                'clientes.referencia',
                'clientes.dni',
                'clientes.deuda',
                DB::raw('count(p.created_at) as cantidad'),
                DB::raw('MONTH(p.created_at) as mes'),
                DB::raw('YEAR(p.created_at) as anio')
                )
        ->where('clientes.estado','1')
        ->where('clientes.tipo','1')
        ->where('clientes.pidio','1')
        ->where('p.estado','1')
        ->whereYear(DB::raw('Date(p.created_at)'), '2022')
        ->groupBy(
            'clientes.id',
            'clientes.nombre',
            'clientes.celular', 
            'clientes.estado', 
            'clientes.provincia',
            'clientes.distrito',
            'clientes.direccion',
            'clientes.referencia',
            'clientes.dni',
            'clientes.deuda',
            'p.created_at'
        )
        ->get(); */

        $clientes = Cliente::
        select('clientes.id',
                'clientes.nombre',
                'clientes.celular', 
                'clientes.estado', 
                'clientes.provincia',
                'clientes.distrito',
                'clientes.direccion',
                'clientes.referencia',
                'clientes.dni',
                'clientes.deuda'
                )
        ->where('clientes.estado','1')
        ->where('clientes.tipo','1')
        ->groupBy(
            'clientes.id',
            'clientes.nombre',
            'clientes.celular', 
            'clientes.estado',
            'clientes.provincia',
            'clientes.distrito',
            'clientes.direccion',
            'clientes.referencia',
            'clientes.dni',
            'clientes.deuda',
        )
        ->get();

        $pedidos = Pedido::
            select(DB::raw('count(*) as total'), DB::raw('MONTH(created_at) as mes'), 'cliente_id')
            ->where('estado', '1')
            ->whereYear(DB::raw('Date(created_at)'), '2022')
            ->groupBy(
                'cliente_id',
                DB::raw('MONTH(created_at)')
                )
            ->orderBy(DB::raw('MONTH(created_at)'), 'ASC')
            ->get();
            
        return view('clientes.excel.clientepedido', compact('clientes', 'pedidos'));
    }
}
