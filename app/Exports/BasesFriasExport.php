<?php

namespace App\Exports;

use App\Models\Cliente;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BasesFriasExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $base_fria = Cliente::
        join('users as u', 'clientes.user_id', 'u.id')
        ->select('clientes.id', 
                'clientes.nombre', 
                'clientes.celular', 
                'clientes.estado', 
                'u.identificador as users')
        ->where('clientes.estado','1')
        ->where('clientes.tipo','0')
        ->get();
        return view('base_fria.excel.index', compact('base_fria'));
    }
}
