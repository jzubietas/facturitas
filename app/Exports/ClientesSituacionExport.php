<?php

namespace App\Exports;

use App\Models\Cliente;
use App\Models\ListadoResultado;
use App\Models\Pedido;
use App\Models\Porcentaje;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClientesSituacionExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function resumenes($request)
    {

        $_2021_11 = ListadoResultado::join('clientes as c', 'c.id', 'listado_resultados.id')
            ->select(
                DB::raw(" (select '2021') as Ejercicio "),
                DB::raw(" (select '11') as Periodo "),
                DB::raw(" (select 'Noviembre') as Periodo2 "),
                'listado_resultados.s_2021_11 as grupo',
                DB::raw('count(listado_resultados.s_2021_11) as total')
            //'cantidad'
            )
            ->groupBy(
                's_2021_11'
            );

        $_2021_12 = ListadoResultado::join('clientes as c', 'c.id', 'listado_resultados.id')
            ->select(
                DB::raw(" (select '2021') as Ejercicio "),
                DB::raw(" (select '12') as Periodo "),
                DB::raw(" (select 'Diciembre') as Periodo2 "),
                'listado_resultados.s_2021_12 as grupo',
                DB::raw('count(listado_resultados.s_2021_12) as total')
            //'cantidad'
            )
            ->groupBy(
                's_2021_12'
            );

        $_2022_01 = ListadoResultado::join('clientes as c', 'c.id', 'listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '01') as Periodo "),
                DB::raw(" (select 'Enero') as Periodo2 "),
                'listado_resultados.s_2022_01 as grupo',
                DB::raw('count(listado_resultados.s_2022_01) as total')
            //'cantidad'
            )
            ->groupBy(
                's_2022_01'
            );

        $_2022_02 = ListadoResultado::join('clientes as c', 'c.id', 'listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '02') as Periodo "),
                DB::raw(" (select 'Febrero') as Periodo2 "),
                'listado_resultados.s_2022_02 as grupo',
                DB::raw('count(listado_resultados.s_2022_02) as total')
            //'cantidad'
            )
            ->groupBy(
                's_2022_02'
            );

        $_2022_03 = ListadoResultado::join('clientes as c', 'c.id', 'listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '03') as Periodo "),
                DB::raw(" (select 'Marzo') as Periodo2 "),
                'listado_resultados.s_2022_03 as grupo',
                DB::raw('count(listado_resultados.s_2022_03) as total')
            //'cantidad'
            )
            ->groupBy(
                's_2022_03'
            );

        $_2022_04 = ListadoResultado::join('clientes as c', 'c.id', 'listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '04') as Periodo "),
                DB::raw(" (select 'Abril') as Periodo2 "),
                'listado_resultados.s_2022_04 as grupo',
                DB::raw('count(listado_resultados.s_2022_04) as total')
            //'cantidad'
            )
            ->groupBy(
                's_2022_04'
            );

        $_2022_05 = ListadoResultado::join('clientes as c', 'c.id', 'listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '05') as Periodo "),
                DB::raw(" (select 'Mayo') as Periodo2 "),
                'listado_resultados.s_2022_05 as grupo',
                DB::raw('count(listado_resultados.s_2022_05) as total')
            //'cantidad'
            )
            ->groupBy(
                's_2022_05'
            );

        $_2022_06 = ListadoResultado::join('clientes as c', 'c.id', 'listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '06') as Periodo "),
                DB::raw(" (select 'Junio') as Periodo2 "),
                'listado_resultados.s_2022_06 as grupo',
                DB::raw('count(listado_resultados.s_2022_06) as total')
            //'cantidad'
            )
            ->groupBy(
                's_2022_06'
            );

        $_2022_07 = ListadoResultado::join('clientes as c', 'c.id', 'listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '07') as Periodo "),
                DB::raw(" (select 'Julio') as Periodo2 "),
                'listado_resultados.s_2022_07 as grupo',
                DB::raw('count(listado_resultados.s_2022_07) as total')
            //'cantidad'
            )
            ->groupBy(
                's_2022_07'
            );

        $_2022_08 = ListadoResultado::join('clientes as c', 'c.id', 'listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '08') as Periodo "),
                DB::raw(" (select 'Agosto') as Periodo2 "),
                'listado_resultados.s_2022_08 as grupo',
                DB::raw('count(listado_resultados.s_2022_08) as total')
            //'cantidad'
            )
            ->groupBy(
                's_2022_08'
            );
    }

    public function clientes($request)
    {

        $clientes = Cliente::
        join('users as u', 'clientes.user_id', 'u.id')
            //->rightJoin('pedidos as p', 'clientes.id', 'p.cliente_id')
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
                DB::raw("(select DATE_FORMAT(dp1.created_at,'%d-%m-%Y %h:%i:%s') from pedidos dp1 where dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fecha"),
                DB::raw("(select DATE_FORMAT(dp2.created_at,'%m') from pedidos dp2 where dp2.cliente_id=clientes.id order by dp2.created_at desc limit 1) as mes"),
                DB::raw("(select DATE_FORMAT(dp3.created_at,'%Y') from pedidos dp3 where dp3.cliente_id=clientes.id order by dp3.created_at desc limit 1) as anio"),
                DB::raw(" (select (dp.codigo) from pedidos dp where dp.cliente_id=clientes.id order by dp.created_at desc limit 1) as codigo "),
                'clientes.situacion',
            )
            ->where('clientes.estado', '1')
            ->where('clientes.tipo', '1')
            //->where(DB::raw('CAST(clientes.created_at as date)'), '=', '2022-09-09')
            ->groupBy(
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
            );
        //->get();
        if ($request->situacion == 'ABANDONO')
            $clientes = $clientes->whereIn('clientes.situacion', [Cliente::ABANDONO_RECIENTE]);
        else if ($request->situacion == 'ABANDONO_PERMANENTE')
            $clientes = $clientes->whereIn('clientes.situacion', [Cliente::ABANDONO_PERMANENTE]);    
        else if ($request->situacion == 'RECURRENTE')
            $clientes = $clientes->whereIn('clientes.situacion', [Cliente::RECURRENTE]);
        else if ($request->situacion == 'NUEVO')
            $clientes = $clientes->whereIn('clientes.situacion', [Cliente::NUEVO]);
        else if ($request->situacion == 'RECUPERADO')
            $clientes = $clientes->whereIn('clientes.situacion', [Cliente::RECUPERADO_RECIENTE]);
        else if ($request->situacion == 'RECUPERADO_ABANDONO')
            $clientes = $clientes->whereIn('clientes.situacion', [Cliente::RECUPERADO_ABANDONO]);   
        else if ($request->situacion == 'CASI_ABANDONO')
            $clientes = $clientes->whereIn('clientes.situacion', [Cliente::CASI_ABANDONO]);    


        $clientes = $clientes->get();


        $cliente_list = [];
        $pedido_list = [];
        $cont = 0;

        foreach ($clientes as $cliente) {
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
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                ->where(DB::raw('MONTH(created_at)'), '1')
                ->count();
            if ($eneroa < 0) {
                $eneroa = 0;
            } else {
                $eneroa = $eneroa;
            }
            $enerop = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio + 1)
                ->where(DB::raw('MONTH(created_at)'), '1')
                ->count();
            $febreroa = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                ->where(DB::raw('MONTH(created_at)'), '2')
                ->count();
            $febrerop = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio + 1)
                ->where(DB::raw('MONTH(created_at)'), '2')
                ->count();
            $marzoa = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                ->where(DB::raw('MONTH(created_at)'), '3')
                ->count();
            $marzop = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio + 1)
                ->where(DB::raw('MONTH(created_at)'), '3')
                ->count();
            $abrila = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                ->where(DB::raw('MONTH(created_at)'), '4')
                ->count();
            $abrilp = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio + 1)
                ->where(DB::raw('MONTH(created_at)'), '4')
                ->count();
            $mayoa = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                ->where(DB::raw('MONTH(created_at)'), '5')
                ->count();
            $mayop = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio + 1)
                ->where(DB::raw('MONTH(created_at)'), '5')
                ->count();
            $junioa = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                ->where(DB::raw('MONTH(created_at)'), '6')
                ->count();
            $juniop = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio + 1)
                ->where(DB::raw('MONTH(created_at)'), '6')
                ->count();
            $julioa = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                ->where(DB::raw('MONTH(created_at)'), '7')
                ->count();
            $juliop = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio + 1)
                ->where(DB::raw('MONTH(created_at)'), '7')
                ->count();
            $agostoa = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                ->where(DB::raw('MONTH(created_at)'), '8')
                ->count();
            $agostop = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio + 1)
                ->where(DB::raw('MONTH(created_at)'), '8')
                ->count();
            $setiembrea = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                ->where(DB::raw('MONTH(created_at)'), '9')
                ->count();
            $setiembrep = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio + 1)
                ->where(DB::raw('MONTH(created_at)'), '9')
                ->count();
            $octubrea = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                ->where(DB::raw('MONTH(created_at)'), '10')
                ->count();
            $octubrep = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio + 1)
                ->where(DB::raw('MONTH(created_at)'), '10')
                ->count();
            $noviembrea = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                ->where(DB::raw('MONTH(created_at)'), '11')
                ->count();
            $noviembrep = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio + 1)
                ->where(DB::raw('MONTH(created_at)'), '11')
                ->count();
            $diciembrea = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio)
                ->where(DB::raw('MONTH(created_at)'), '12')
                ->count();
            $diciembrep = Pedido::/* select(DB::raw('count(*) as total'))
                            -> */ where('estado', '1')
                ->where('cliente_id', $cliente->id)
                ->whereYear(DB::raw('Date(created_at)'), $request->anio + 1)
                ->where(DB::raw('MONTH(created_at)'), '12')
                ->count();

            if ($cliente->deuda == '1') {
                $deposito = 'DEBE';
            } else {
                $deposito = 'CANCELADO';
            }

            $dateM = Carbon::now()->format('m');
            $dateY = Carbon::now()->format('Y');//2022
            
            $estadopedido=$cliente->situacion;

            /*if ($cliente->pidio == 0 || $cliente->pidio == '0' || $cliente->pidio == null || $cliente->pidio == 'null') {
                $estadopedido = 'SIN PEDIDO';
            } else {
                if ((($dateY * 1) - ($cliente->anio * 1)) == 0) {
                   
                    if ((($dateM * 1) - ($cliente->mes * 1)) >= 0 && (($dateM * 1) - ($cliente->mes * 1)) < 2) {
                        $estadopedido = 'RECURRENTE';
                    } else {
                        $estadopedido = 'ABANDONO';
                    }

                } else {
                    $estadopedido = 'ABANDONO';
                }
            }*/


            /*if( (($dateM*1)-($cliente->mes*1)) >= 0 && (($dateM*1)-($cliente->mes*1))<3 && (($dateY*1)-($cliente->anio*1)) == 0){
                $estadopedido = 'RECURRENTE';
            }else{
                $estadopedido = 'ABANDONO';
            }*/

            $cliente_list[$cont] = array(
                'id' => $cliente->id,
                'asesor' => $cliente->asesor,
                'nombre' => $cliente->nombre,
                'dni' => $cliente->dni,
                'celular' => $cliente->celular,
                'icelular' => $cliente->icelular,
                'codigo' => $cliente->codigo,
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
                'situacion' => $cliente->situacion,

                /* 'dateM' => Carbon::now()->format('m'),
                'dateY' => Carbon::now()->format('Y'), */
                'estadopedido' => $cliente->situacion,
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

    public function anioa($request)
    {

        $anioa = $request->anio;
        $this->anioa = $anioa;

        return $this;
    }

    public function aniop($request)
    {

        $aniop = $request->anio + 1;
        $this->aniop = $aniop;

        return $this;
    }

    public function view(): View
    {
        return view('clientes.excel.clientesituacion', [
            'cliente_list' => $this->cliente_list,
            'anioa' => $this->anioa,
            'aniop' => $this->aniop
        ]);
    }
}
