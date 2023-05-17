<?php

namespace App\Exports;

use App\Models\Cliente;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BaseFriaPorAsesorExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function clientes($request) {
        $base_fria = Cliente::
        select([
            'clientes.id',
            'clientes.nombre',
            'clientes.icelular',
            'clientes.celular',
            'clientes.estado',
            'clientes.user_clavepedido as users',
            Db::raw("date_format(clientes.created_at,'%d-%m-%Y') as creacion")
        ])
        ->where('clientes.estado','1')
        ->where('clientes.tipo','0');

        if(!$request->user_id)
        {
        }else{
            $base_fria=$base_fria->where('clientes.user_id', $request->user_id);
        }

        $base_fria=$base_fria->get();

        $this->base_fria = $base_fria;

        return $this;
    }

    public function view(): View {
        return view('base_fria.excel.porasesor', [
            'base_fria'=> $this->base_fria
        ]);
    }
}
