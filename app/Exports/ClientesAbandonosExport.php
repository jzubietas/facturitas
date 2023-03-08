<?php

namespace App\Exports;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\User;
use App\Models\Porcentaje;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClientesAbandonosExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function clientes($request) {

            $clientes = Cliente::
                    join('users as u', 'clientes.user_id', 'u.id')
                    ->rightJoin('pedidos as p', 'clientes.id', 'p.cliente_id')
                    ->select([
                            'clientes.id',
                            'clientes.tipo',
                            'u.identificador as asesor',
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
                            'clientes.pidio',
                            DB::raw("(select DATE_FORMAT(dp1.created_at,'%d-%m-%Y %h:%i:%s') from pedidos dp1 where dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fecha"),
                            DB::raw("(select DATE_FORMAT(dp2.created_at,'%m') from pedidos dp2 where dp2.cliente_id=clientes.id and dp2.estado=1 order by dp2.created_at desc limit 1) as mes"),
                            DB::raw("(select DATE_FORMAT(dp3.created_at,'%Y') from pedidos dp3 where dp3.cliente_id=clientes.id and dp3.estado=1 order by dp3.created_at desc limit 1) as anio"),
                            DB::raw(" (select (dp.codigo) from pedidos dp where dp.cliente_id=clientes.id and dp.estado=1 order by dp.created_at desc limit 1) as codigo "),
                            'clientes.situacion',

                            DB::raw("(select (r.porcentaje) from porcentajes r where r.cliente_id=clientes.id and r.nombre='FISICO - sin banca' limit 1) as porcentajes_1"),
                            DB::raw("(select (r.porcentaje) from porcentajes r where r.cliente_id=clientes.id and r.nombre='FISICO - banca' limit 1) as porcentajes_2"),
                            DB::raw("(select (r.porcentaje) from porcentajes r where r.cliente_id=clientes.id and r.nombre='ELECTRONICA - sin banca' limit 1) as porcentajes_3"),
                            DB::raw("(select (r.porcentaje) from porcentajes r where r.cliente_id=clientes.id and r.nombre='ELECTRONICA - banca' limit 1) as porcentajes_4"),
                            ])
                    ->where('clientes.estado','1')
                    ->where('clientes.tipo','1')
                    ->whereNotNull('clientes.situacion');


    }

    public function anioa($request) {

        $anioa = $request->anio;
        $this->anioa = $anioa;

        return $this;
    }

    public function aniop($request) {

        $aniop = $request->anio+1;
        $this->aniop = $aniop;

        return $this;
    }

    public function view(): View {
        return view('clientes.excel.clientesituacion', [
            'cliente_list'=> $this->cliente_list,
            'anioa'=> $this->anioa,
            'aniop'=> $this->aniop
        ]);
    }
}
