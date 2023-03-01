<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DetallePedido;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Ruc;
use App\Models\User;
use App\View\Components\dashboard\graficos\borras\PedidosPorDia;
use App\View\Components\dashboard\graficos\PedidosMesCountProgressBar;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use \Yajra\Datatables\Datatables;

class DashboardController extends Controller
{
  public function index()
  {
    if (auth()->user()->rol == 'MOTORIZADO') {
      return redirect()->route('envios.motorizados.index'); //->with('info', 'registrado');
    }

    return view(
      'dashboard.dashboard',
    );
  }

  public function widgets(Request $request)
  {
    $widget1 = new PedidosMesCountProgressBar();
    $widget2 = new PedidosPorDia(\auth()->user()->rol, 'Cantidad de pedidos de los asesores por dia', 'Asesores', 'Cant. Pedidos', '370', true);
    $widget3 = new PedidosPorDia(\auth()->user()->rol, 'Cantidad de pedidos de los asesores por mes', 'Asesores', 'Cant. Pedidos');
    $widget2->renderData();
    $widget3->renderData();
    return response()->json([
      "widgets" =>
        [
          [
            "data" => [],
            "html" => \Blade::renderComponent($widget1)
          ],
          [
            "data" => $widget2->getData(),
            "html" => \Blade::renderComponent($widget2)
          ],
          [
            "chart" => true,
            "data" => $widget3->getData(),
            "html" => \Blade::renderComponent($widget3)
          ],
        ]
    ]);
  }

  public function searchCliente(Request $request)
  {
    $q = $request->get("q");//915722331
    $clientes = Cliente::query()
      ->with(['user', 'rucs', 'porcentajes'])
      ->where('celular', 'like', '%' . $q . '%')
      ->orwhere(DB::raw("concat(clientes.celular,'-',clientes.icelular)"), 'like', '%' . $q . '%')
      ->orWhere('nombre', 'like', '%' . join("%", explode(" ", trim($q))) . '%')
      ->orWhere('dni', 'like', '%' . $q . '%')
      ->limit(10)
      ->get()
      ->map(function (Cliente $cliente) {
        $cliente->deuda_total = DetallePedido::query()->whereIn('pedido_id', $cliente->pedidos()->where('estado', '1')->pluck("id"))->sum("saldo");
        return $cliente;
      });

    return view('dashboard.searchs.search_cliente', compact('clientes'));
  }

  public function searchRuc(Request $request)
  {
    $q = $request->get("q");
    $rucs = Ruc::query()
      ->with(['cliente', 'user'])
      ->where('num_ruc', 'like', '%' . $q . '%')
      ->limit(10)
      ->get()
      ->map(function (Ruc $ruc) {
        $ruc->cliente->deuda_total = DetallePedido::query()->activo()->whereIn('pedido_id', $ruc->cliente->pedidos()->activo()->pluck("id"))->sum("saldo");
        $ruc->cliente->deuda_total_ruc = DetallePedido::query()->activo()->where('ruc', $ruc->num_ruc)->sum("saldo");
        return $ruc;
      });
    return view('dashboard.searchs.search_rucs', compact('rucs'));
  }


  /**
   * @throws \Exception
   */



  public function viewMetaTable(Request $request)
  {


    $metas = [];
    $total_asesor= User::query()->activo()->rolAsesor()->count();
    if (auth()->user()->rol == User::ROL_ASESOR){
      $asesores = User::query()->activo()->rolAsesor()->where('identificador',auth()->user()->identificador)->where('excluir_meta','<>','1')->get();
      $total_asesor = User::query()->activo()->rolAsesor()->where('identificador',auth()->user()->identificador)->where('excluir_meta','<>','1')->count();
    }else if (auth()->user()->rol == User::ROL_JEFE_LLAMADAS) {
      $asesores = User::query()->activo()->rolAsesor()->where('excluir_meta','<>','1')->get();
      $total_asesor = User::query()->activo()->rolAsesor()->where('excluir_meta','<>','1')->count();
    }else if (auth()->user()->rol == User::ROL_LLAMADAS) {
      $asesores = User::query()->activo()->rolAsesor()->where('excluir_meta','<>','1')->get();
      $total_asesor = User::query()->activo()->rolAsesor()->where('excluir_meta','<>','1')->count();
    } else if (auth()->user()->rol == User::ROL_FORMACION) {
      $asesores = User::query()->activo()->rolAsesor()->where('excluir_meta','<>','1')->get();
      $total_asesor = User::query()->activo()->rolAsesor()->where('excluir_meta','<>','1')->count();
    } else if (auth()->user()->rol == User::ROL_PRESENTACION) {
      $asesores = User::query()->activo()->rolAsesor()->where('excluir_meta','<>','1')->get();
      $total_asesor = User::query()->activo()->rolAsesor()->where('excluir_meta','<>','1')->count();
    }else {
      $encargado = null;
      if (auth()->user()->rol == User::ROL_ENCARGADO) {
        $encargado = auth()->user()->id;
      }
      $asesores = User::query()->activo()->rolAsesor()->where('excluir_meta','<>','1')->when($encargado != null, function ($query) use ($encargado) {
        return $query->where('supervisor', '=', $encargado);
      })->get();
      $total_asesor = User::query()->activo()->rolAsesor()->where('excluir_meta','<>','1')->when($encargado != null, function ($query) use ($encargado) {
        return $query->where('supervisor', '=', $encargado);
      })->count();
    }

    $supervisores_array = User::query()->activo()->rolSupervisor()->get();
    $count_asesor = [];
    foreach ($supervisores_array as $supervisor){
      $count_asesor[$supervisor->id] =
        ['pedidos_totales' => 0,
          'total_pedido_mespasado' => 0,
          'meta' => 0,
          'total_pagado' => 0,
          'progress_pagos' => 0,
          'progress_pedidos' => 0,
          'total_pedido' => 0,
          'pedidos_dia' => 0,
        ]
      ;
    }

    foreach ($asesores as $asesor) {
      if (in_array(auth()->user()->rol, [User::ROL_FORMACION, User::ROL_ADMIN, User::ROL_PRESENTACION, User::ROL_ASESOR, User::ROL_LLAMADAS, User::ROL_JEFE_LLAMADAS])) {
      } else {
        if (auth()->user()->rol != User::ROL_ADMIN ) {
          if (auth()->user()->rol != User::ROL_ENCARGADO) {
            if (auth()->user()->id != $asesor->id) {
              continue;
            }
          } else {
            if (auth()->user()->id != $asesor->supervisor) {
              continue;
            }
          }
        }
      }

      $date_pagos = Carbon::parse(now())->subMonth();
      $asesor_pedido_dia = Pedido::query()->join('users as u', 'u.id', 'pedidos.user_id')->where('u.identificador', $asesor->identificador)
        ->where('pedidos.codigo', 'not like', "%-C%")->activo()->whereDate('pedidos.created_at', now())->where('pendiente_anulacion', '<>','1' )->count();
      $metatotal = (float)$asesor->meta_pedido;
      $metatotal_2 = (float)$asesor->meta_pedido_2;
      $metatotal_cobro = (float)$asesor->meta_cobro;
      $total_pedido = $this->applyFilterCustom(Pedido::query()->where('user_id', $asesor->id)
        ->where('codigo', 'not like', "%-C%")->activo()->where('pendiente_anulacion', '<>','1' ), now(), 'created_at')
        ->count();
      $total_pedido_mespasado = $this->applyFilterCustom(Pedido::query()->where('user_id', $asesor->id)
        ->where('codigo', 'not like', "%-C%")->activo()->where('pendiente_anulacion', '<>','1' ), $date_pagos, 'created_at')
        ->count();
      $total_pagado = $this->applyFilterCustom(Pedido::query()->where('user_id', $asesor->id)
        ->where('codigo', 'not like', "%-C%")->activo()->where('pendiente_anulacion', '<>','1' )->pagados(), $date_pagos, 'created_at')
        ->count();
      $supervisor = User::where('rol', User::ROL_ASESOR)->where('identificador', $asesor->identificador)->activo()->first()->supervisor;
      $pedidos_totales = Pedido::query()->join('users as u', 'u.id', 'pedidos.user_id')
        ->where('pedidos.codigo', 'not like', "%-C%")->where('pendiente_anulacion', '<>','1' )->whereDate('pedidos.created_at', now())->count();
      $encargado_asesor = $asesor->supervisor;



      $item = [
        "identificador" => $asesor->identificador,
        "code" => "{$asesor->name}",
        "pedidos_dia" => $asesor_pedido_dia,
        "name" => $asesor->name,
        "total_pedido" => $total_pedido,
        "total_pedido_mespasado" => $total_pedido_mespasado,
        "total_pagado" => $total_pagado,
        "meta" => $metatotal,
        "meta_2" => $metatotal_2,
        "meta_cobro" => $metatotal_cobro,
        "pedidos_totales" => $pedidos_totales,
        "supervisor" =>  $supervisor,
      ];

      if (array_key_exists($encargado_asesor, $count_asesor)){
        if ($encargado_asesor == 46){
          $count_asesor[$encargado_asesor]['pedidos_totales'] = $pedidos_totales+$count_asesor[$encargado_asesor]['pedidos_totales'];
          $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado+$count_asesor[$encargado_asesor]['total_pagado'];
          $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado+$count_asesor[$encargado_asesor]['total_pedido_mespasado'];
          $count_asesor[$encargado_asesor]['meta'] = $metatotal+$count_asesor[$encargado_asesor]['meta'];
          $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido+$count_asesor[$encargado_asesor]['total_pedido'];
          $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia+$count_asesor[$encargado_asesor]['pedidos_dia'];
        }else if ($encargado_asesor == 24){
          $count_asesor[$encargado_asesor]['pedidos_totales'] = $pedidos_totales+$count_asesor[$encargado_asesor]['pedidos_totales'];
          $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado+$count_asesor[$encargado_asesor]['total_pagado'];
          $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado+$count_asesor[$encargado_asesor]['total_pedido_mespasado'];
          $count_asesor[$encargado_asesor]['meta'] = $metatotal+$count_asesor[$encargado_asesor]['meta'];
          $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido+$count_asesor[$encargado_asesor]['total_pedido'];
          $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia+$count_asesor[$encargado_asesor]['pedidos_dia'];
        }else{
          $count_asesor[$encargado_asesor]['pedidos_totales'] = 0;
          $count_asesor[$encargado_asesor]['total_pagado'] = $total_pagado+$count_asesor[$encargado_asesor]['total_pagado'];
          $count_asesor[$encargado_asesor]['total_pedido_mespasado'] = $total_pedido_mespasado+$count_asesor[$encargado_asesor]['total_pedido_mespasado'];
          $count_asesor[$encargado_asesor]['meta'] = $metatotal+$count_asesor[$encargado_asesor]['meta'];
          $count_asesor[$encargado_asesor]['total_pedido'] = $total_pedido+$count_asesor[$encargado_asesor]['total_pedido'];
          $count_asesor[$encargado_asesor]['pedidos_dia'] = $asesor_pedido_dia+$count_asesor[$encargado_asesor]['pedidos_dia'];

        }
      }

      if ($asesor->excluir_meta) {
        if ($metatotal_cobro > 0) {
          $p_pagos = round(($total_pedido_mespasado / $total_pagado) * 100, 2);
        } else {
          $p_pagos = 0;
        }
        if ($metatotal > 0) {
          $p_pedidos = round(($total_pedido / $metatotal) * 100, 2);
        } else {
          $p_pedidos = 0;
        }
        $item['progress_pagos'] = $p_pagos;
        $item['progress_pedidos'] = $p_pedidos;
      } else {
        $progressData[] = $item;
      }



    }

    $newData = [];
    $union = collect($progressData)->groupBy('identificador');
    foreach ($union as $identificador => $items) {
      foreach ($items as $item) {
        if (!isset($newData[$identificador])) {
          $newData[$identificador] = $item;
        } else {
          $newData[$identificador]['total_pedido'] += data_get($item, 'total_pedido');
          $newData[$identificador]['total_pedido_pasado'] += data_get($item, 'total_pedido_mespasado');
          $newData[$identificador]['total_pagado'] += data_get($item, 'total_pagado');
          $newData[$identificador]['meta'] += data_get($item, 'meta');
          $newData[$identificador]['meta_2'] += data_get($item, 'meta_2');
          $newData[$identificador]['meta_cobro'] += data_get($item, 'meta_cobro');
          $newData[$identificador]['pedidos_dia'] += data_get($item, 'pedidos_dia');
          $newData[$identificador]['supervisor'] += data_get($item, 'supervisor');

          $newData[$identificador]['pedidos_totales'] += data_get($item, 'pedidos_totales');
        }
      }
      $newData[$identificador]['name'] = collect($items)->map(function ($item) {
        return explode(" ", data_get($item, 'name'))[0];
      })->first();
    }
    $progressData = collect($newData)->values()->map(function ($item) {
      $all = data_get($item, 'total_pedido');
      $all_mespasado = data_get($item, 'total_pedido_mespasado');
      $pay = data_get($item, 'total_pagado');
      $allmeta = data_get($item, 'meta');
      $allmeta_2 = data_get($item, 'meta_2');
      $allmeta_cobro = data_get($item, 'meta_cobro');
      $pedidos_dia = data_get($item, 'pedidos_dia');
      $pedidos_totales = data_get($item, 'pedidos_totales');
      $supervisor = data_get($item, 'supervisor');

      if ($pay > 0) {
        $p_pagos = round(($pay / $all_mespasado) * 100, 2);
      } else {
        $p_pagos = 0;
      }

      if ($allmeta > 0) {
        $p_pedidos = round(($all / $allmeta) * 100, 2);
      } else {
        $p_pedidos = 0;
      }

      if ($allmeta_2 > 0) {
        $p_pedidos_new = round(($all / $allmeta_2) * 100, 2);
      } else {
        $p_pedidos_new = 0;
      }

      if ($p_pedidos >= 100) {
        $item['progress_pedidos'] = $p_pedidos_new;
        $item['meta_new'] = 0;

      } else {
        $item['progress_pedidos'] = $p_pedidos;
        $item['meta_new'] = 1;
      }

      $item['progress_pagos'] = $p_pagos;
      $item['total_pedido'] = $all;
      $item['total_pedido_pasado'] = $all_mespasado;
      $item['pedidos_dia'] = $pedidos_dia;


      $item['pedidos_totales'] = $pedidos_totales;
      return $item;

    })->sortBy('progress_pedidos', SORT_NUMERIC, true);//->all();

    if($request->ii==1)
    {
      if($total_asesor%2==0)
      {
        $skip=0;
        $take=intval($total_asesor/2);
      }else{
        $skip=0;
        $take=intval($total_asesor/2)+1;
      }
      //return json_encode(array('skip'=>$skip,'take'=>$take)); 0  8
      $progressData->splice($skip,$take)->all();
      //$progressData=array_slice($progressData, 2);
      //return $progressData;
    }
    else if($request->ii==2)
    {
      if($total_asesor%2==0)
      {
        $skip=intval($total_asesor/2);
        $take=intval($total_asesor/2);
      }else{
        $skip=intval($total_asesor/2)+1;
        $take=intval($total_asesor/2);
      }
      //return json_encode(array('skip'=>$skip,'take'=>$take));  8   7
      $progressData->splice($skip,$take)->all();
      //$progressData=array_slice($progressData, 3);
      //return $progressData;
    }
    else if($request->ii==3){
      $progressData->all();
    }

    //aqui la division de  1  o 2

    $all = collect($progressData)->pluck('total_pedido')->sum();
    $all_mespasado = collect($progressData)->pluck('total_pedido_mespasado')->sum();
    $pay = collect($progressData)->pluck('total_pagado')->sum();
    $meta = collect($progressData)->pluck('meta')->sum();
    $meta_2 = collect($progressData)->pluck('meta_2')->sum();
    $meta_cobro = collect($progressData)->pluck('meta_cobro')->sum();
    $pedidos_dia = collect($progressData)->pluck('pedidos_dia')->sum();
    $supervisor = collect($progressData)->pluck('supervisor')->sum();

    if ($meta > 0) {
      $p_pedidos = round(($all / $meta) * 100, 2);
    }
    else {
      $p_pedidos = 0;
    }
    if ($pay > 0) {
      $p_pagos = round(($pay / $all_mespasado) * 100, 2);
    }
    else {
      $p_pagos = 0;
    }

    $object_totales = [
      "progress_pedidos" => $p_pedidos,
      "progress_pagos" => $p_pagos,
      "total_pedido" => $all,
      "total_pedido_mespasado" => $all_mespasado,
      "total_pagado" => $pay,
      "meta" => $meta,
      "meta_2" => $meta_2,
      "meta_cobro" => $meta_cobro,
      "pedidos_dia" => $pedidos_dia,
      "supervisor" => $supervisor,
    ];
    $html='';

    /*TOTAL*/
    if($request->ii==3)
    {
      $html .= '<table class="table tabla-metas_pagos_pedidos" style="background: #ade0db; color: #0a0302">';
      $html .= '<tbody>
              <tr class="responsive-table">
                  <th class="col-lg-4 col-md-12 col-sm-12">';
      if($object_totales['pedidos_dia']==0){
        $html .= '<span class="px-4 pt-1 pb-1 bg-red text-center justify-content-center w-100 rounded font-weight-bold" style="display:flex; align-items: center;height: 40px !important; color: black !important;">  TOTAL DE PEDIDOS DEL DIA: '.$object_totales['pedidos_dia'].' </span>';
      }else {
        $html .= '<span class="px-4 pt-1 pb-1 bg-white text-center justify-content-center w-100 rounded font-weight-bold" style="display:flex; align-items: center;height: 40px !important; color: black !important;">  TOTAL DE PEDIDOS DEL DIA: '.$object_totales['pedidos_dia'].' </span>';
      }
      $html .= '
                  </th>
                  <th class="col-lg-4 col-md-12 col-sm-12">';
      $html.='<div class="position-relative rounded">
                <div class="progress rounded h-40 h-60-res">';
      if($object_totales['progress_pagos']>=80)
        $html.='<div class="progress-bar bg-success rounded h-60-res" role="progressbar"
                 style="width: '.$object_totales['progress_pagos'].'%;background: #03af03;"
                 aria-valuenow="'.$object_totales['progress_pagos'].'"
                 aria-valuemin="0" aria-valuemax="100"></div>';
      else if($object_totales['progress_pagos']>70)
        $html.='<div class="progress-bar bg-warning rounded  h-60-res" role="progressbar"
                 style="height:40px; width: 70%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
            <div class="progress-bar rounded h-60-res" role="progressbar"
                 style="width: '.($object_totales['progress_pagos']-70).'%;
             background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                 aria-valuenow="'.($object_totales['progress_pagos']-70).'"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
      else if($object_totales['progress_pagos']>50)
        $html.='<div class="progress-bar bg-warning" role="progressbar"
                 style="width: 70%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
      else if($object_totales['progress_pagos']>40)
        $html.='<div class="progress-bar bg-danger h-60-res" role="progressbar"
                 style="width: 40%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
            <div class="progress-bar h-60-res" role="progressbar"
                 style="width: '.($object_totales['progress_pagos']-40).'%;
             background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                 aria-valuenow="'.($object_totales['progress_pagos']-40).'"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
      else
        $html.='<div class="progress-bar bg-danger h-60-res" role="progressbar"
                 style="width: '.($object_totales['progress_pagos']).'%"
                 aria-valuenow="'.($object_totales['progress_pagos']).'"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
      $html.='</div>
    <div class="position-absolute w-100 text-center rounded" style="top: 10px;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;"> TOTAL COBRANZA - '.Carbon::now()->subMonths(1)->monthName .' :  '.$object_totales['progress_pagos'].'%</b> - '.$object_totales['total_pagado'].'/'.$object_totales['total_pedido_mespasado'].'</span>
    </div>
</div>';

      $html.=' </th>
                  <th class="col-lg-4 col-md-12 col-sm-12">';
      $html.='<div class="position-relative rounded">
                <div class="progress rounded" style="height: 40px !important;">';

      if($object_totales['progress_pedidos']>=80)
        $html.='<div class="progress-bar bg-success rounded" role="progressbar"
                 style="height:40px; width: '.$object_totales['progress_pagos'].'%;background: #03af03;"
                 aria-valuenow="'.$object_totales['progress_pagos'].'"
                 aria-valuemin="0" aria-valuemax="100"></div>';
      else if($object_totales['progress_pedidos']>70)
        $html.='<div class="progress-bar bg-warning rounded" role="progressbar"
                 style="height:40px; width: 70%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
            <div class="progress-bar rounded" role="progressbar"
                 style="height:40px !important; width: '.($object_totales['progress_pedidos']-70).'%;
             background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                 aria-valuenow="'.($object_totales['progress_pedidos']-70).'"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
      else if($object_totales['progress_pedidos']>50)
        $html.='<div class="progress-bar bg-warning" role="progressbar"
                 style="height:40px;width: 70%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
      else if($object_totales['progress_pedidos']>40)
        $html.='<div class="progress-bar bg-danger" role="progressbar"
                 style="width: 40%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
            <div class="progress-bar" role="progressbar"
                 style="width: '.($object_totales['progress_pedidos']-40).'%;
             background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                 aria-valuenow="'.($object_totales['progress_pedidos']-40).'"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
      else
        $html.='<div class="progress-bar bg-danger" role="progressbar"
                 style="width: '.($object_totales['progress_pedidos']).'%"
                 aria-valuenow="'.($object_totales['progress_pedidos']).'"
                 aria-valuemin="0"
                 aria-valuemax="100">0000</div>';
      $html.='</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res" style="top: 10px;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;"> TOTAL PEDIDOS -  '.Carbon::now()->monthName .' : '.$object_totales['progress_pedidos'].'%</b> - '.$object_totales['total_pedido'].'/'.$object_totales['meta'].'</span>
    </div>';
      $html.='</th>
              </tr>
              </tbody>';
      $html .= '</table>';
    }

    /*LUISSSSSSSSSSSSSSSSSSSSSSSSSSSSS ----- 46   */
    else if($request->ii==4)
    {
      $html .= '<table class="table tabla-metas_pagos_pedidos" style="background: #e4dbc6; color: #0a0302">';
      $html .= '<tbody>
                    <tr class="responsive-table">
                        <th class="col-lg-4 col-md-12 col-sm-12">';
      if( ($count_asesor[46]['pedidos_dia'])==0){
        $html .= '<span class="px-4 pt-1 pb-1 bg-red text-center justify-content-center w-100 rounded font-weight-bold" style="display:flex; align-items: center;height: 40px !important; color: black !important;">  PEDIDOS DE ENCARGADO LUIS: '.$count_asesor[46]['pedidos_dia'].' </span>';
      }
      else {
        $html .= '<span class="px-4 pt-1 pb-1 bg-white text-center justify-content-center w-100 rounded font-weight-bold" style="display:flex; align-items: center;height: 40px !important; color: black !important;">  PEDIDOS DE ENCARGADO LUIS: '.$count_asesor[46]['pedidos_dia'].' </span>';
      }
      $html .= '        </th>
                        <th class="col-lg-4 col-md-12 col-sm-12">';
      $html.='<div class="position-relative rounded">
                         <div class="progress rounded h-40 h-60-res">';
      if(($count_asesor[46]['progress_pagos'])>=80)
        $html.='<div class="progress-bar bg-success rounded h-60-res" role="progressbar"
                         style="width: '.round(( $count_asesor[46]['total_pagado']/$count_asesor[46]['total_pedido_mespasado'])*100,2).'%;background: #03af03;"
                         aria-valuenow="'.round(( $count_asesor[46]['total_pagado']/$count_asesor[46]['total_pedido_mespasado'])*100,2).'"
                         aria-valuemin="0" aria-valuemax="100"></div>';

      else if( round(($count_asesor[46]['total_pagado']/$count_asesor[46]['total_pedido_mespasado']) *100, 0)>70)
        $html.='<div class="progress-bar bg-warning rounded  h-60-res" role="progressbar"
                         style="height:40px; width: 70%"
                         aria-valuenow="70"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>
                    <div class="progress-bar rounded h-60-res" role="progressbar"
                         style="width: '.(round(( $count_asesor[46]['total_pagado']/$count_asesor[46]['total_pedido_mespasado']),2)-70).'%;
                     background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                         aria-valuenow="'.(  round($count_asesor[46]['total_pedido_mespasado']/$count_asesor[46]['total_pagado'],2)-70).'"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
      else if( round(($count_asesor[46]['total_pagado']/$count_asesor[46]['total_pedido_mespasado']) *100, 0)>50)
        $html.='<div class="progress-bar bg-warning" role="progressbar"
                       style="width: 70%"
                       aria-valuenow="70"
                       aria-valuemin="0"
                       aria-valuemax="100"></div>';
      else if( round(($count_asesor[46]['total_pagado']/$count_asesor[46]['total_pedido_mespasado']) *100, 0)>40)
        $html.='<div class="progress-bar bg-danger h-60-res" role="progressbar"
                       style="width: 40%"
                       aria-valuenow="70"
                       aria-valuemin="0"
                       aria-valuemax="100"></div>
                      <div class="progress-bar h-60-res" role="progressbar"
                           style="width: '.( round($count_asesor[46]['total_pedido_mespasado']/$count_asesor[46]['total_pagado'],2)-40).'%;
                       background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                           aria-valuenow="'.(  round($count_asesor[46]['total_pedido_mespasado']/$count_asesor[46]['total_pagado'],2)-40).'"
                           aria-valuemin="0"
                           aria-valuemax="100"></div>';
      else
        $html.='<div class="progress-bar bg-danger h-60-res" role="progressbar"
                       style="width: '.(round(($count_asesor[46]['total_pagado']/$count_asesor[46]['total_pedido_mespasado']) , 2)).'%"
                       aria-valuenow="'.(round(($count_asesor[46]['total_pagado']/$count_asesor[46]['total_pedido_mespasado']) , 2)).'"
                       aria-valuemin="0"
                       aria-valuemax="100"></div>';
      $html.='</div>
                      <div class="position-absolute w-100 text-center rounded" style="top: 10px;font-size: 12px;">
                            <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;"> TOTAL COBRANZA - '.Carbon::now()->subMonths(1)->monthName .' :  '.  (round(($count_asesor[46]['total_pagado']/$count_asesor[46]['total_pedido_mespasado']) *100, 2))
        .'% </b> - '. $count_asesor[46]['total_pagado'].'/'.$count_asesor[46]['total_pedido_mespasado'].'</span>
                      </div>
                    </div>';

      $html.=' </th>
                  <th class="col-lg-4 col-md-12 col-sm-12">';
      $html.='<div class="position-relative rounded">
                <div class="progress rounded" style="height: 40px !important;">';
      if(round(($count_asesor[46]['total_pedido']/$count_asesor[46]['meta'])*100,0)>=80)
        $html.='<div class="progress-bar bg-success rounded" role="progressbar"
                 style="height:40px; width: '. round(($count_asesor[46]['total_pedido']/$count_asesor[46]['meta'])*100,2).'%;background: #03af03;"
                 aria-valuenow="'. round(($count_asesor[46]['total_pedido']/$count_asesor[46]['meta'])*100,2).'"
                 aria-valuemin="0" aria-valuemax="100"></div>';
            else if( round(( $count_asesor[46]['total_pedido']/$count_asesor[46]['meta'])*100,0)>70)
        $html.='<div class="progress-bar bg-warning rounded" role="progressbar"
                 style="height:40px; width: 70%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
            <div class="progress-bar rounded" role="progressbar"
                 style="height:40px !important; width: '.(round(($count_asesor[46]['total_pedido']/$count_asesor[46]['meta'])*100,2)-70).'%;
             background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                 aria-valuenow="'.( round(( $count_asesor[46]['total_pedido']/$count_asesor[46]['meta'])*100,2)-70).'"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
      else if( round(( $count_asesor[46]['total_pedido']/$count_asesor[46]['meta'])*100,0)>50)
        $html.='<div class="progress-bar bg-warning" role="progressbar"
                 style="height:40px;width: 70%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
      else if( round(( $count_asesor[46]['total_pedido']/$count_asesor[46]['meta'])*100,0)>40)
        $html.='<div class="progress-bar bg-danger" role="progressbar"
                 style="width: 40%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
            <div class="progress-bar" role="progressbar"
                 style="width: '.( round(( $count_asesor[46]['total_pedido']/$count_asesor[46]['meta']),2)-40).'%;
             background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                 aria-valuenow="'.( round(( $count_asesor[46]['total_pedido']/$count_asesor[46]['meta']),2)-40).'"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
      else
        $html.='<div class="progress-bar bg-danger" role="progressbar"
                 style="width: '.( round(( $count_asesor[46]['total_pedido']/$count_asesor[46]['meta'])*100,2)).'%"
                 aria-valuenow="'.( round(( $count_asesor[46]['total_pedido']/$count_asesor[46]['meta']),2)).'"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
      $html.='</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res" style="top: 10px;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;"> TOTAL PEDIDOSSSSS -  '.Carbon::now()->monthName .' : '. round(( $count_asesor[46]['total_pedido']/$count_asesor[46]['meta'])*100,2).'%</b> - '.$count_asesor[46]['total_pedido'].'/'.$count_asesor[46]['meta'].'</span>
    </div>';
      $html.='</th>
              </tr>
              </tbody>';
      $html .= '</table>';
    }


    /*PAOLAAAAAAAAAAAAAAAAAAAAAAAAAAAAA ----- 24*/
    else if($request->ii==5)
    {
      $html .= '<table class="table tabla-metas_pagos_pedidos" style="background: #e4dbc6; color: #0a0302">';
      $html .= '<tbody>
                    <tr class="responsive-table">
                        <th class="col-lg-4 col-md-12 col-sm-12">';
      if(($count_asesor[24]['pedidos_dia']) ==0){
                $html .= '<span class="px-4 pt-1 pb-1 bg-red text-center justify-content-center w-100 rounded font-weight-bold" style="display:flex; align-items: center;height: 40px !important; color: black !important;">  PEDIDOS DE ENCARGADO PAOLA: '.$count_asesor[24]['pedidos_dia'].' </span>';
      }
      else {
                $html .= '<span class="px-4 pt-1 pb-1 bg-white text-center justify-content-center w-100 rounded font-weight-bold" style="display:flex; align-items: center;height: 40px !important; color: black !important;">  PEDIDOS DE ENCARGADO PAOLA: '.$count_asesor[24]['pedidos_dia'].' </span>';
      }
      $html .= '        </th>
                        <th class="col-lg-4 col-md-12 col-sm-12">';
                 $html.='<div class="position-relative rounded">
                         <div class="progress rounded h-40 h-60-res">';
      if(($count_asesor[24]['progress_pagos'])>=80)
                $html.='<div class="progress-bar bg-success rounded h-60-res" role="progressbar"
                         style="width: '.round(( $count_asesor[24]['total_pagado']/$count_asesor[24]['total_pedido_mespasado'])*100,2).'%;background: #03af03;"
                         aria-valuenow="'.round(( $count_asesor[24]['total_pagado']/$count_asesor[24]['total_pedido_mespasado'])*100,2).'"
                         aria-valuemin="0" aria-valuemax="100"></div>';
      else if(round(( $count_asesor[24]['total_pagado']/$count_asesor[24]['total_pedido_mespasado'])*100,0)>70)
                $html.='<div class="progress-bar bg-warning rounded  h-60-res" role="progressbar"
                         style="height:40px; width: 70%"
                         aria-valuenow="70"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>
                    <div class="progress-bar rounded h-60-res" role="progressbar"
                         style="width: '.(round(( $count_asesor[24]['total_pagado']/$count_asesor[24]['total_pedido_mespasado']),2)-70).'%;
                     background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                         aria-valuenow="'.(round(( $count_asesor[24]['total_pagado']/$count_asesor[24]['total_pedido_mespasado']),2) - 70).'"
                         aria-valuemin="0"
                         aria-valuemax="100"></div>';
      else if(round(( $count_asesor[24]['total_pagado']/$count_asesor[24]['total_pedido_mespasado'])*100,0)>50)
              $html.='<div class="progress-bar bg-warning" role="progressbar"
                       style="width: 70%"
                       aria-valuenow="70"
                       aria-valuemin="0"
                       aria-valuemax="100"></div>';
      else if(round(( $count_asesor[24]['total_pagado']/$count_asesor[24]['total_pedido_mespasado'])*100,0)>40)
              $html.='<div class="progress-bar bg-danger h-60-res" role="progressbar"
                       style="width: 40%"
                       aria-valuenow="70"
                       aria-valuemin
                       aria-valuemax="100"></div>
                      <div class="progress-bar h-60-res" role="progressbar"
                           style="width: '.(round(( $count_asesor[24]['total_pagado']/$count_asesor[24]['total_pedido_mespasado']),2)-40).'%;
                       background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                           aria-valuenow="'.(round(( $count_asesor[24]['total_pagado']/$count_asesor[24]['total_pedido_mespasado']),2)-40).'"
                           aria-valuemin="0"
                           aria-valuemax="100"></div>';
      else
              $html.='<div class="progress-bar bg-danger h-60-res" role="progressbar"
                       style="width: '.round(( $count_asesor[24]['total_pagado']/$count_asesor[24]['total_pedido_mespasado']),2).'%"
                       aria-valuenow="'.round(( $count_asesor[24]['total_pagado']/$count_asesor[24]['total_pedido_mespasado']),2).'"
                       aria-valuemin="0"
                       aria-valuemax="100"></div>';
              $html.='</div>
                      <div class="position-absolute w-100 text-center rounded" style="top: 10px;font-size: 12px;">
                            <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;"> TOTAL COBRANZA - '.Carbon::now()->subMonths(1)->monthName .' :  '.round(( $count_asesor[24]['total_pagado']/$count_asesor[24]['total_pedido_mespasado'])*100,2).'%</b> - '.$count_asesor[24]['total_pagado'].'/'.$count_asesor[24]['total_pedido_mespasado'].'</span>
                      </div>
                    </div>';

      $html.=' </th>
                  <th class="col-lg-4 col-md-12 col-sm-12">';
      $html.='<div class="position-relative rounded">
                <div class="progress rounded" style="height: 40px !important;">';

      if(round(($count_asesor[24]['total_pedido']/$count_asesor[24]['meta'])*100,0)>=80)
        $html.='<div class="progress-bar bg-success rounded" role="progressbar"
                 style="height:40px; width: '. round(($count_asesor[24]['total_pedido']/$count_asesor[24]['meta'])*100,2).'%;background: #03af03;"
                 aria-valuenow="'. round(($count_asesor[24]['total_pedido']/$count_asesor[24]['meta'])*100,2).'"
                 aria-valuemin="0" aria-valuemax="100"></div>';
      else if(round(($count_asesor[24]['total_pedido']/$count_asesor[24]['meta'])*100,0)>70)
        $html.='<div class="progress-bar bg-warning rounded" role="progressbar"
                 style="height:40px; width: 70%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
            <div class="progress-bar rounded" role="progressbar"
                 style="height:40px !important; width: '.(round(($count_asesor[24]['total_pedido']/$count_asesor[24]['meta'])*100,2)).'%;
             background: -webkit-linear-gradient( left, #ffc107,#71c11b);"
                 aria-valuenow="'.(round(($count_asesor[24]['total_pedido']/$count_asesor[24]['meta'])*100,2)-70).'"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
      else if(round(($count_asesor[24]['total_pedido']/$count_asesor[24]['meta'])*100,0)>50)
        $html.='<div class="progress-bar bg-warning" role="progressbar"
                 style="height:40px;width: 70%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
      else if(round(($count_asesor[24]['total_pedido']/$count_asesor[24]['meta'])*100,0)>40)
        $html.='<div class="progress-bar bg-danger" role="progressbar"
                 style="width: 40%"
                 aria-valuenow="70"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>
            <div class="progress-bar" role="progressbar"
                 style="width: '.round(($count_asesor[24]['total_pedido']/$count_asesor[24]['meta'])*100,2).'%;
             background: -webkit-linear-gradient( left, #dc3545,#ffc107);"
                 aria-valuenow="'.(round(($count_asesor[24]['total_pedido']/$count_asesor[24]['meta'])*100,2)-40).'"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
      else
        $html.='<div class="progress-bar bg-danger" role="progressbar"
                   style="width: '.round(($count_asesor[24]['total_pedido']/$count_asesor[24]['meta'])*100,2).'%"
                 aria-valuenow="'.round(($count_asesor[24]['total_pedido']/$count_asesor[24]['meta'])*100,2).'"
                 aria-valuemin="0"
                 aria-valuemax="100"></div>';
      $html.='</div>
    <div class="position-absolute w-100 text-center rounded h-40 h-60-res" style="top: 10px;font-size: 12px;">
             <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 16px; text-transform: uppercase;"> TOTAL PEDIDOS -  '.Carbon::now()->monthName .' : '.round(($count_asesor[24]['total_pedido']/$count_asesor[24]['meta'])*100,2).'%</b> - '.$count_asesor[24]['total_pedido'].'/'.$count_asesor[24]['meta'].'</span>
    </div>';
      $html.='</th>
              </tr>
              </tbody>';
      $html .= '</table>';
    }


    /*IZQUIERDA / DERECHA*/
    else if($request->ii==1 || $request->ii==2)
    {

      $html .= '<table class="table tabla-metas_pagos_pedidos table-dark" style="background: #e4dbc6; color: #232121">';
      $html .= '<thead>
                <tr>
                    <th width="10%">Asesor</th>
                    <th width="10%">Identificador</th>
                    <th width="10%"><span style="font-size:10px;">Pedidos del día '.Carbon::now()->day .'  </span></th>
                    <th width="25%">Cobranza  '.Carbon::now()->subMonths(1)->monthName .' </th>
                    <th width="35%">Pedidos  '.Carbon::now()->monthName .' </th>
                </tr>
                </thead>
                <tbody>';
      foreach ($progressData as $data) {
        $html.= '<tr>
             <td>' . $data["name"] . '</td>
             <td>' . $data["identificador"]  . ' ';

             if($data["supervisor"] == 46){
              $html.=  '- A';
             }else {
              $html.=  '- B';
             }
        $html.=  '
             </td>
             <td>';
        if ($data["pedidos_dia"] > 0) {
          $html.=  '<span class="px-4 pt-1 pb-1 bg-white text-center justify-content-center w-100 rounded font-weight-bold" > ' . $data["pedidos_dia"] . '</span> ';
        } else {
          $html.=  '<span class="px-4 pt-1 pb-1 bg-red text-center justify-content-center w-100 rounded font-weight-bold"> ' . $data["pedidos_dia"] . ' </span> ';
        }
        $html.=  '</td>';
        $html.= '<td>';
        if ($data["progress_pagos"] == 100) {
          $html.=  ' <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 40px">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b style="font-weight: bold !important;font-size: 18px">  '. $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . ' <p class="text-red p-0 d-inline font-weight-bold ml-2" style="font-size: 18px; color: #d9686!important"> '. ((($data["total_pedido_mespasado"]-$data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"]-$data["total_pagado"]) : '0') . '</p> </span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
        } elseif ($data["progress_pagos"] >= 80) {
          $html.=  '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 40px">
                                          <div class="rounded" role="progressbar" style="background: #8ec117 !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 18px">  ' . $data["progress_pagos"] . '% </b> - '. $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red p-0 d-inline font-weight-bold ml-2" style="font-size: 18px; color: #d9686!important"> '. ((($data["total_pedido_mespasado"]-$data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"]-$data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
        } elseif ($data["progress_pagos"] > 70) {
          $html.=  '
                    <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 40px">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(255,193,7,1) 0%, rgba(255,193,7,1) 89%, rgba(113,193,27,1) 100%) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 18px">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red p-0 d-inline font-weight-bold ml-2" style="font-size: 18px; color: #d9686!important"> '. ((($data["total_pedido_mespasado"]-$data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"]-$data["total_pagado"]) : '0')  . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
        } elseif ($data["progress_pagos"] > 60) {
          $html.=  ' <div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 40px">
                                          <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 18px">  '. $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / '. $data["total_pedido_mespasado"] . '<p class="text-red p-0 d-inline font-weight-bold ml-2" style="font-size: 18px; color: #d9686!important"> '. ((($data["total_pedido_mespasado"]-$data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"]-$data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
        } elseif ($data["progress_pagos"] > 50) {
          $html.=  '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 40px">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%) !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 18px">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . '<p class="text-red p-0 d-inline font-weight-bold ml-2" style="font-size: 18px; color: #d9686!important"> '. ((($data["total_pedido_mespasado"]-$data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"]-$data["total_pagado"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
        } else {
          $html.=  '<div class="w-100 bg-white rounded">
                              <div class="position-relative rounded">
                                  <div class="progress bg-white rounded" style="height: 40px">
                                      <div class="rounded" role="progressbar" style="background: #dc3545 !important; width: ' . $data["progress_pagos"] . '%" aria-valuenow="34.25" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                      <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 18px">  ' . $data["progress_pagos"] . '% </b> - ' . $data["total_pagado"] . ' / ' . $data["total_pedido_mespasado"] . ' <p class="text-red p-0 d-inline font-weight-bold ml-2" style="font-size: 18px; color: #d9686!important"> '. ((($data["total_pedido_mespasado"]-$data["total_pagado"]) > 0) ? ($data["total_pedido_mespasado"]-$data["total_pagado"]) : '0') . '</p></span>
                                  </div>
                              </div>
                              <sub class="d-none">% -  Pagados/ Asignados</sub>
                            </div>';
        }
        $html.=  '</td>';
        $html.=  '   <td>';
        /*META-2*/
        if ($data["meta_new"] == 0) {
          if ($data["progress_pedidos"] <= 100) {
            $html.=  '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 40px">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 18px">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / '. $data["meta_2"] . '<p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d9686!important"> '.  ((($data["meta_2"]-$data["total_pedido"]) > 0) ? ($data["meta_2"]-$data["total_pedido"]) : '0'). '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
          }else{
            $html.=  '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 40px">
                                          <div class="rounded" role="progressbar" style="background: #008ffb !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 18px">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / '. $data["meta_2"] . '<p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d9686!important"> '. ((($data["meta_2"]-$data["total_pedido"]) > 0) ? ($data["meta_2"]-$data["total_pedido"]) : '0'). '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
          }
        }
        /*META-1*/
        if ($data["meta_new"] == 1){
          if ($data["progress_pedidos"] >= 90) {
            $html.=  '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 40px">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(3,175,3,1) 0%, rgba(24,150,24,1) 60%, rgba(0,143,251,1) 100%) !important; width: '. $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 18px">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . ' <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d9686!important"> '. ((($data["meta"]-$data["total_pedido"]) > 0) ? ($data["meta"]-$data["total_pedido"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
          }
          elseif ($data["progress_pedidos"] >= 80) {
            $html.=  '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 40px">
                                          <div class="rounded" role="progressbar" style="background: #8ec117 ; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 18px">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d9686!important"> '. ((($data["meta"]-$data["total_pedido"]) > 0) ? ($data["meta"]-$data["total_pedido"]) : '0')  . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
          }elseif ($data["progress_pedidos"] >= 70) {
            $html.=  '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 40px">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(255,193,7,1) 0%, rgba(255,193,7,1) 89%, rgba(113,193,27,1) 100%) !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 18px">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d9686!important"> '. ((($data["meta"]-$data["total_pedido"]) > 0) ? ($data["meta"]-$data["total_pedido"]) : '0') . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
          } elseif ($data["progress_pedidos"] >= 60) {
            $html.=  '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 40px">
                                          <div class="rounded" role="progressbar" style="background: #ffc107 !important; width: ' . $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 18px">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / '. $data["meta"] . '  <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d9686!important"> '. ((($data["meta"]-$data["total_pedido"]) > 0) ? ($data["meta"]-$data["total_pedido"]) : '0')  . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';
          } elseif ($data["progress_pedidos"] >= 50) {
            $html.=  '<div class="w-100 bg-white rounded">
                                    <div class="position-relative rounded">
                                      <div class="progress bg-white rounded" style="height: 40px">
                                          <div class="rounded" role="progressbar" style="background: linear-gradient(90deg, rgba(220,53,69,1) 0%, rgba(194,70,82,1) 89%, rgba(255,193,7,1) 100%) !important; width: '. $data["progress_pedidos"] . '%" ></div>
                                          </div>
                                        <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                            <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 18px">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' / ' . $data["meta"] . '  <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d9686!important"> '. ((($data["meta"]-$data["total_pedido"]) > 0) ? ($data["meta"]-$data["total_pedido"]) : '0')  . '</p></span>
                                        </div>
                                    </div>
                                    <sub class="d-none">% -  Pagados/ Asignados</sub>
                                  </div>';

          } else {
            $html.=  '<div class="w-100 bg-white rounded">
                              <div class="position-relative rounded">
                                  <div class="progress bg-white rounded" style="height: 40px">
                                      <div class="rounded" role="progressbar" style="background: #dc3545;width: ' . $data["progress_pedidos"] . '%" ></div>
                                      </div>
                                  <div class="position-absolute rounded w-100 text-center" style="top: 5px;font-size: 12px;">
                                      <span style="font-weight: lighter"> <b style="font-weight: bold !important; font-size: 18px">  ' . $data["progress_pedidos"] . '% </b> - ' . $data["total_pedido"] . ' /'. $data["meta"] . '  <p class="text-red p-0 d-inline font-weight-bold ml-5" style="font-size: 18px; color: #d9686!important"> '. ((($data["meta"]-$data["total_pedido"]) > 0) ? ($data["meta"]-$data["total_pedido"]) : '0')  . '</p></span>
                                  </div>
                              </div>
                              <sub class="d-none">% -  Pagados/ Asignados</sub>
                            </div>';
          }
        }
        $html.=  '  </td>
      </tr> ';
      }

      $html .= '</tbody>';

      $html .= '</table>';
    }

    return $html;
  }

  public static function applyFilterCustom($query, CarbonInterface $date = null, $column = 'created_at')

  {
    if ($date == null) {
      $date = now();
    }
    return $query->whereBetween($column, [
      $date->clone()->startOfMonth(),
      $date->clone()->endOfMonth()->endOfDay()
    ]);
  }

}
