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
                    ->select('clientes.id',
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
                            //DB::raw("DATE_FORMAT(MAX(p.created_at), '%d-%m-%Y %h:%i:%s') as fecha"),
                            //DB::raw("DATE_FORMAT(MAX(p.created_at), '%d-%m-%Y %h:%i:%s') as fecha"),
                            //DB::raw('DATE_FORMAT(MAX(p.created_at), "%m") as mes'),
                            //DB::raw('DATE_FORMAT(MAX(p.created_at), "%Y") as anio'),
                            DB::raw("(select DATE_FORMAT(dp1.created_at,'%d-%m-%Y %h:%i:%s') from pedidos dp1 where dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fecha"),
                            DB::raw("(select DATE_FORMAT(dp2.created_at,'%m') from pedidos dp2 where dp2.cliente_id=clientes.id and dp2.estado=1 order by dp2.created_at desc limit 1) as mes"),
                            DB::raw("(select DATE_FORMAT(dp3.created_at,'%Y') from pedidos dp3 where dp3.cliente_id=clientes.id and dp3.estado=1 order by dp3.created_at desc limit 1) as anio"),

                            DB::raw(" (select (dp.codigo) from pedidos dp where dp.cliente_id=clientes.id and dp.estado=1 order by dp.created_at desc limit 1) as codigo "),
                            'clientes.situacion',
                            )
                    ->where('clientes.estado','1')
                    ->where('clientes.tipo','1')
                    ->whereNotNull('clientes.situacion')
                    ->whereBetween('created_at', '2023-02-01 0:00:00', '2023-02-28 23:59:00');

                    /*->groupBy(
                        'clientes.id',
                        'u.identificador',
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
                        'clientes.situacion',
                    );*/
                    //->get();
                if($request->situacion=='ABANDONO')
                    $clientes=$clientes->whereIn('clientes.situacion',['ABANDONO','ABANDONO RECIENTE']);
                else if($request->situacion=='RECURENTE')
                    $clientes=$clientes->whereIn('clientes.situacion',['RECURRENTE']);
                else if($request->situacion=='NUEVO')
                    $clientes=$clientes->whereIn('clientes.situacion',['NUEVO']);
                else if($request->situacion=='RECUPERADO')
                    $clientes=$clientes->whereIn('clientes.situacion',['RECUPERADO']);
                else if($request->situacion=='RECUPERADO ABANDONO')
                    $clientes=$clientes->whereIn('clientes.situacion',['RECUPERADO ABANDONO']);
                else if($request->situacion=='RECUPERADO RECIENTE')
                    $clientes=$clientes->whereIn('clientes.situacion',['RECUPERADO RECIENTE']);
                else if($request->situacion=='ABANDONO RECIENTE')
                    $clientes=$clientes->whereIn('clientes.situacion',['RECUPERADO ABANDONO']);

            if (Auth::user()->rol == "Llamadas") {

                $usersasesores = User::where('users.rol', 'Asesor')
                    ->where('users.estado', '1')
                    ->where('users.llamada', Auth::user()->id)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');
                $clientes = $clientes->WhereIn("u.identificador", $usersasesores);
            }elseif (Auth::user()->rol == "Asesor") {
                $usersasesores = User::where('users.rol', 'Asesor')
                    ->where('users.estado', '1')
                    ->where('users.identificador', Auth::user()->identificador)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');
                $clientes = $clientes->WhereIn("u.identificador", $usersasesores);

            }else if (Auth::user()->rol == "Encargado") {
                $usersasesores = User::where('users.rol', 'Asesor')
                    ->where('users.estado', '1')
                    ->where('users.supervisor', Auth::user()->id)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');

                $clientes = $clientes->WhereIn("u.identificador", $usersasesores);
            }

            $clientes=$clientes->get();

            $cliente_list = [];
            $pedido_list = [];
            $cont = 0;

            foreach($clientes as $cliente){
                $porcentajefsb = Porcentaje::select('porcentaje')
                                            ->where('cliente_id', $cliente->id)
                                            ->where('nombre', 'FISICO - sin banca')
                                            ->first();
                $porcentajefb = Porcentaje::select('porcentaje')
                                            ->where('cliente_id', $cliente->id)
                                            ->where('nombre', 'FISICO - banca')
                                            ->first();
                $porcentajeesb = Porcentaje::select('porcentaje')
                                            ->where('cliente_id', $cliente->id)
                                            ->where('nombre', 'ELECTRONICA - sin banca')
                                            ->first();
                $porcentajeeb = Porcentaje::select('porcentaje')
                                            ->where('cliente_id', $cliente->id)
                                            ->where('nombre', 'ELECTRONICA - banca')
                                            ->first();

                $eneroa = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                            ->where(DB::raw('MONTH(created_at)'), '1')
                            ->count();
                if($eneroa<0){$eneroa = 0;}else{$eneroa = $eneroa;}
                $enerop = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio+1)
                            ->where(DB::raw('MONTH(created_at)'), '1')
                            ->count();
                $febreroa = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                            ->where(DB::raw('MONTH(created_at)'), '2')
                            ->count();
                $febrerop = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio+1)
                            ->where(DB::raw('MONTH(created_at)'), '2')
                            ->count();
                $marzoa = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                            ->where(DB::raw('MONTH(created_at)'), '3')
                            ->count();
                $marzop = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio+1)
                            ->where(DB::raw('MONTH(created_at)'), '3')
                            ->count();
                $abrila = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                            ->where(DB::raw('MONTH(created_at)'), '4')
                            ->count();
                $abrilp = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio+1)
                            ->where(DB::raw('MONTH(created_at)'), '4')
                            ->count();
                $mayoa = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                            ->where(DB::raw('MONTH(created_at)'), '5')
                            ->count();
                $mayop = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio+1)
                            ->where(DB::raw('MONTH(created_at)'), '5')
                            ->count();
                $junioa = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                            ->where(DB::raw('MONTH(created_at)'), '6')
                            ->count();
                $juniop = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio+1)
                            ->where(DB::raw('MONTH(created_at)'), '6')
                            ->count();
                $julioa = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                            ->where(DB::raw('MONTH(created_at)'), '7')
                            ->count();
                $juliop = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio+1)
                            ->where(DB::raw('MONTH(created_at)'), '7')
                            ->count();
                $agostoa = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                            ->where(DB::raw('MONTH(created_at)'), '8')
                            ->count();
                $agostop = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio+1)
                            ->where(DB::raw('MONTH(created_at)'), '8')
                            ->count();
                $setiembrea = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                            ->where(DB::raw('MONTH(created_at)'), '9')
                            ->count();
                $setiembrep = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio+1)
                            ->where(DB::raw('MONTH(created_at)'), '9')
                            ->count();
                $octubrea = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                            ->where(DB::raw('MONTH(created_at)'), '10')
                            ->count();
                $octubrep = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio+1)
                            ->where(DB::raw('MONTH(created_at)'), '10')
                            ->count();
                $noviembrea = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                            ->where(DB::raw('MONTH(created_at)'), '11')
                            ->count();
                $noviembrep = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio+1)
                            ->where(DB::raw('MONTH(created_at)'), '11')
                            ->count();
                $diciembrea = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                            ->where(DB::raw('MONTH(created_at)'), '12')
                            ->count();
                $diciembrep = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */where('estado', '1')
                            ->where('cliente_id', $cliente->id)
                            ->whereYear(DB::raw('Date(created_at)'), $request->anio+1)
                            ->where(DB::raw('MONTH(created_at)'), '12')
                            ->count();

                if($cliente->deuda == '1'){
                    $deposito = 'DEBE';
                }else{
                    $deposito = 'CANCELADO';
                }

                $dateM = Carbon::now()->format('m');
                $dateY = Carbon::now()->format('Y');//2022

                $estadopedido=$cliente->situacion;

                $cliente_list[$cont] = array(
                    'id' => $cliente->id,
                    'asesor' => $cliente->asesor,
                    'nombre' => $cliente->nombre,
                    'dni' => $cliente->dni,
                    'celular' => $cliente->celular,
                    'icelular' => $cliente->icelular,
                    'provincia' => $cliente->provincia,
                    'distrito' => $cliente->distrito,
                    'direccion' => $cliente->direccion,
                    'referencia' => $cliente->referencia,
                    'porcentajefsb' => $porcentajefsb,
                    'porcentajefb' => $porcentajefb,
                    'porcentajeesb' => $porcentajeesb,
                    'porcentajeeb' => $porcentajeeb,
                    'deuda' => $cliente->deuda,
                    'deposito' => $deposito,
                    //'fecha' => date('d-m-Y H:i:s', strtotime($cliente->fecha)),
                    'fecha' => ($cliente->fecha),
                    'dia' => $cliente->dia,
                    'mes' => $cliente->mes,
                    'anio' => $cliente->anio,
                    'codigo' => $cliente->codigo,
                    'situacion' => $cliente->situacion,
                    /* 'dateM' => Carbon::now()->format('m'),
                    'dateY' => Carbon::now()->format('Y'), */
                    'estadopedido' => $estadopedido,
                    'pidio' => $cliente->pidio,
                    //
                    //'dateY' => $dateY,
                    //'dateM' => $dateM,
                    //
                    'estado' => $cliente->estado,
                    'eneroa' => $eneroa,
                    'enerop' => $enerop,
                    'febreroa' => $febreroa,
                    'febrerop' => $febrerop,
                    'marzoa' => $marzoa,
                    'marzop' => $marzop,
                    'abrila' => $abrila,
                    'abrilp' => $abrilp,
                    'mayoa' => $mayoa,
                    'mayop' => $mayop,
                    'junioa' => $junioa,
                    'juniop' => $juniop,
                    'julioa' => $julioa,
                    'juliop' => $juliop,
                    'agostoa' => $agostoa,
                    'agostop' => $agostop,
                    'setiembrea' => $setiembrea,
                    'setiembrep' => $setiembrep,
                    'octubrea' => $octubrea,
                    'octubrep' => $octubrep,
                    'noviembrea' => $noviembrea,
                    'noviembrep' => $noviembrep,
                    'diciembrea' => $diciembrea,
                    'diciembrep' => $diciembrep
                );
                $cont++;
            }

            $this->cliente_list = $cliente_list;

        return $this;
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
