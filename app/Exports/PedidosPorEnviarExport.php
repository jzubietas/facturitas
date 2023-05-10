<?php

namespace App\Exports;

use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PedidosPorEnviarExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function pedidosLima($request) {

        $pedidosLima = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select([
                'pedidos.id',
                'u.identificador as identificador_asesor',
                'u.name as nombre_asesor',
                'pedidos.codigo as codigo',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha_registro'),
                'c.nombre as nombre_cliente',
                'c.icelular as icelular_cliente',
                'c.celular as celular_cliente',
                'dp.nombre_empresa as empresa',
                'dp.cantidad as cantidad',
                'dp.fecha_envio_doc as fecha_elaboracion',
                'pedidos.env_distrito as distrito',
                'pedidos.env_direccion as direccion',
                'pedidos.env_referencia as referencia',
                'pedidos.env_nombre_cliente_recibe as nombre_recibe',
                'pedidos.env_celular_cliente_recibe as celular_contacto',
                'pedidos.env_zona as zona',
                'pedidos.condicion_envio as estado_pedido',
                'pedidos.condicion_envio as estado_envio'
            ])
          ->where('pedidos.estado', '1')
          ->where('pedidos.pendiente_anulacion', '0')
            //->where('dp.estado', '1')
            //->where('pedidos.envio', '<>', '0')
            //->where('pedidos.direccion', '1')
            //->where('pedidos.destino', 'LIMA')
            //->where('di.provincia', 'LIMA')
            ->whereIn('pedidos.condicion_envio_code', [
              Pedido::ENVIO_COURIER_JEFE_OPE_INT,
              Pedido::RECIBIDO_JEFE_OPE_INT,
              Pedido::RECEPCION_COURIER_INT,
            ])
            ->whereBetween(DB::raw('DATE(pedidos.created_at)'), [$request->desde, $request->hasta])
            ->sinDireccionEnvio()
            ->orderBy('pedidos.created_at', 'DESC');

      if (Auth::user()->rol == "Operario") {
        $asesores = User::where('users.rol', 'Asesor')
          ->where('users.estado', '1')
          ->Where('users.operario', Auth::user()->id)
          ->select(
            DB::raw("users.identificador as identificador")
          )
          ->pluck('users.identificador');

        $pedidosLima = $pedidosLima->WhereIn('u.identificador', $asesores);

      } else if (Auth::user()->rol == "Jefe de operaciones") {
        $operarios = User::where('users.rol', 'Operario')
          ->where('users.estado', '1')
          ->where('users.jefe', Auth::user()->id)
          ->select(
            DB::raw("users.id as id")
          )
          ->pluck('users.id');

        $asesores = User::where('users.rol', 'Asesor')
          ->where('users.estado', '1')
          ->WhereIn('users.operario', $operarios)
          ->select(
            DB::raw("users.identificador as identificador")
          )
          ->pluck('users.identificador');

        $pedidosLima = $pedidosLima->WhereIn('u.identificador', $asesores);

      } else if (Auth::user()->rol == "Asesor") {
        $pedidosLima = $pedidosLima->Where('u.identificador', Auth::user()->identificador);

      } else if (Auth::user()->rol == "Super asesor") {
        $pedidosLima = $pedidosLima->Where('u.identificador', Auth::user()->identificador);

      } else if (Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {
        $pedidosLima = $pedidosLima->Where('u.identificador', Auth::user()->identificador);
      } else if (Auth::user()->rol == "Encargado") {

        $usersasesores = User::where('users.rol', 'Asesor')
          ->where('users.estado', '1')
          ->where('users.supervisor', Auth::user()->id)
          ->select(
            DB::raw("users.identificador as identificador")
          )
          ->pluck('users.identificador');

        $pedidosLima = $pedidosLima->whereIn('u.identificador', $usersasesores);
      }


        $pedidosLima=$pedidosLima->get();
        $this->pedidosLima = $pedidosLima;
        return $this;
    }
    public function view(): View {
        return view('pedidos.excel.pedidosporenviar', [
            'pedidosLima'=> $this->pedidosLima
        ]);
    }

}
