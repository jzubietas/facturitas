<?php

namespace App\Exports;

use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BasesFriasExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function base_fria($request) {
        $base_fria = Cliente::
        join('users as u', 'clientes.user_id', 'u.id')
        ->select('clientes.id', 
                'clientes.nombre', 
                'clientes.icelular', 
                'clientes.celular', 
                'clientes.estado', 
                'u.identificador as users')
        ->where('clientes.estado','1')
        ->where('clientes.tipo','0')
        ->whereBetween(DB::raw('DATE(clientes.created_at)'), [$request->desde, $request->hasta]) //rango de fechas
        ->get();
        $this->base_fria = $base_fria;
        return $this;
    }

    public function view(): View {
        return view('base_fria.excel.index', [
            'base_fria'=> $this->base_fria
        ]);
    }
}
