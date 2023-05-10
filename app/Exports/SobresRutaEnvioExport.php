<?php

namespace App\Exports;

use App\Models\Pedido;

use App\Models\DireccionEnvio;
use App\Models\DireccionGrupo;
use App\Models\DireccionPedido;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SobresRutaEnvioExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function pedidos($request) {

        //return $request->all();       
        //$request->de
        $min=$request->de;
        //$min = Carbon::createFromFormat('d/m/Y', $request->de)->format('Y-m-d');

        $pedidosLima = DireccionGrupo::join('direccion_envios as de','direccion_grupos.id','de.direcciongrupo')
                                    ->join('clientes as c', 'c.id', 'de.cliente_id')
                                    ->join('users as u', 'u.id', 'c.user_id')
                                    ->where("direccion_grupos.estado","1")
                                    ->whereNull('direccion_grupos.condicion_sobre') 
                                    ->where(DB::raw('DATE(direccion_grupos.created_at)'), $min)
                                    ->select(
                                        'direccion_grupos.id',
                                        'u.identificador',
                                        DB::raw(" (select 'LIMA') as destino "),
                                        'de.celular',
                                        'de.nombre',
                                        'de.cantidad',
                                        DB::raw(" (select group_concat(dp.codigo_pedido) from direccion_pedidos dp where dp.direcciongrupo=direccion_grupos.id) as codigos "),
                                        DB::raw(" (select group_concat(ab.empresa) from direccion_pedidos ab where ab.direcciongrupo=direccion_grupos.id) as producto "),
                                        'de.direccion',
                                        'de.referencia',
                                        'de.observacion',                                        
                                        'de.distrito',
                                        'direccion_grupos.created_at as fecha',
                                        'direccion_grupos.destino as destino2',
                                        'direccion_grupos.distribucion as distribucion'
                                    );//->get();

        $pedidosProvincia = DireccionGrupo::join('gasto_envios as de','direccion_grupos.id','de.direcciongrupo')
                                    ->join('clientes as c', 'c.id', 'de.cliente_id')
                                    ->join('users as u', 'u.id', 'c.user_id')
                                    ->where("direccion_grupos.estado","1")
                                    ->whereNull('direccion_grupos.condicion_sobre') 
                                    ->where(DB::raw('DATE(direccion_grupos.created_at)'), $min)
                                    ->select(
                                        'direccion_grupos.id',
                                        'u.identificador',
                                        DB::raw(" (select 'PROVINCIA') as destino "),
                                        DB::raw(" (select '') as celular "),
                                        DB::raw(" (select '') as nombre "),
                                        'de.cantidad',
                                        DB::raw(" (select group_concat(dp.codigo_pedido) from gasto_pedidos dp where dp.direcciongrupo=direccion_grupos.id) as codigos "),
                                        DB::raw(" (select group_concat(ab.empresa) from gasto_pedidos ab where ab.direcciongrupo=direccion_grupos.id) as producto "),
                                        'de.tracking as direccion',
                                        'de.foto as referencia',
                                        DB::raw(" (select '') as observacion "),
                                        DB::raw(" (select '') as distrito "),
                                        'direccion_grupos.created_at as fecha',
                                        'direccion_grupos.destino as destino2',
                                        'direccion_grupos.distribucion as distribucion'
                                    );//->get();                                    
        $pedidos = $pedidosLima->union($pedidosProvincia);
        $pedidos=$pedidos->get();

        $this->pedidos = $pedidos;
        return $this;
    }

    /*public function fecha() 
    {
        $fecha= Carbon::now()->addDate(1).toString();
        $this->fecha = $fecha;
        return $this;
    }*/



    public function view(): View {
        return view('sobres.excel.sobresRutaEnvio', [
            'pedidos'=> $this->pedidos
            //'fecha' => $this->fecha
        ]);
    }

}