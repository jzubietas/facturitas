<?php

namespace App\Exports;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Porcentaje;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClientesPedidosExport implements FromView, ShouldAutoSize
{
    use Exportable;
    
    public function clientes($request) {

            $clientes = Cliente::
                    join('users as u', 'clientes.user_id', 'u.id')
                    ->join('pedidos as p', 'clientes.id', 'p.cliente_id')
                    ->select('clientes.id',
                            'u.identificador as asesor',
                            'clientes.nombre',
                            'clientes.dni',
                            'clientes.celular', 
                            'clientes.provincia',
                            'clientes.distrito',
                            'clientes.direccion',
                            'clientes.referencia',
                            'clientes.estado',
                            'clientes.deuda',
                            DB::raw('DATE_FORMAT(MAX(p.created_at), "%d/%m/%Y") as fecha'),
                            /* DB::raw('MAX(DATE_FORMAT(p.created_at, "%d/%m/%Y")) as fecha'), *///DB::raw('MAX(p.created_at) as fecha'),
                            /* DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'), */
                            DB::raw('DATE_FORMAT(MAX(p.created_at), "%m") as mes'),/* DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'), */
                            DB::raw('DATE_FORMAT(MAX(p.created_at), "%Y") as anio')/* DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio') */
                            )
                    ->where('clientes.estado','1')
                    ->where('clientes.tipo','1')
                    /* ->where('clientes.pidio','1') */
                    ->groupBy(
                        'clientes.id',
                        'u.identificador',
                        'clientes.nombre',
                        'clientes.dni',
                        'clientes.celular', 
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        'clientes.referencia',
                        'clientes.estado',
                        'clientes.deuda'
                    )
                    ->get();

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
                $dateY = Carbon::now()->format('Y');

                if( (($dateM*1)-($cliente->mes*1)) >= 0 && (($dateM*1)-($cliente->mes*1))<3 && (($dateY*1)-($cliente->anio*1)) == 0){
                    $estadopedido = 'RECURRENTE';
                }else{
                    $estadopedido = 'ABANDONO';
                }

                $cliente_list[$cont] = array(
                    'id' => $cliente->id,
                    'asesor' => $cliente->asesor,
                    'nombre' => $cliente->nombre,
                    'dni' => $cliente->dni,
                    'celular' => $cliente->celular,
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
                    'fecha' => $cliente->fecha,
                    'dia' => $cliente->dia,
                    'mes' => $cliente->mes,
                    'anio' => $cliente->anio,
                    /* 'dateM' => Carbon::now()->format('m'),
                    'dateY' => Carbon::now()->format('Y'), */
                    'estadopedido' => $estadopedido,
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
        return view('clientes.excel.clientepedido2', [
            'cliente_list'=> $this->cliente_list,
            'anioa'=> $this->anioa,
            'aniop'=> $this->aniop
        ]);
    }
}