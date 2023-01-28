<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModalController extends Controller
{
    //
    public function ajax_modal1_response(Request $request)
    {
        if($request->opcion)
        {
            $opcion=$request->opcion;
            switch ($opcion)
            {
                case '1':
                    //base fria y referido
                    $asesor_op1=$request->asesor_op1;//identificador
                    $cliente_op1=$request->cliente_op1;
                    $clientenuevo_op1=$request->clientenuevo_op1;
                    $captura_op1=$request->captura_op1;
                    $letra=Cliente::where("id",$cliente_op1)->activo()->first()->icelular;
                    $referencia=Cliente::where("id",$cliente_op1)->activo()->first()->celular;
                    /*try {
                        DB::beginTransaction();*/

                    $cliente = Cliente::create([
                            'nombre' => '',
                            'celular' => $clientenuevo_op1,
                            'icelular'=> $letra,
                            'user_id' =>  User::where("identificador",$asesor_op1)->activo()->first()->id,
                            'tipo' => '0',
                            'provincia' => '',
                            'distrito' => '',
                            'direccion' => '',
                            'referencia' => $referencia,
                            'dni' => '',
                            'deuda' => '0',
                            'pidio' => '0',
                            'estado' => '1'
                        ]);
                        /*DB::commit();
                        return response()->json(['html' => $cliente->id]);
                    } catch (\Throwable $th) {
                        return response()->json(['html' => "0"]);
                    }*/
                    return response()->json(['html' => $cliente->id]);

                    break;
                case '2':
                    //autorizacion para poner pedido
                    $asesor_op2=$request->asesor_op2;
                    $cliente_op2=$request->cliente_op2;
                    $cantidadpedidos_op2=$request->cantidadpedidos_op2;
                    $captura_op2=$request->captura_op2;

                    /*$cliente = Cliente::query()->where("id", '=', $cliente_op2)->update([
                        'crea_temporal' => 1,
                        'activado_pedido' => $cantidadpedidos_op2,
                        'activado_tiempo' => 5,
                        'temporal_update' => now()->addMinutes(5),
                    ]);
                    return response()->json(['html' => $cliente->id]);*/

                    return response()->json(['html' => "0"]);
                    break;
                case '3':
                    $asesor_op3=$request->asesor_op3;
                    $cliente_op3=$request->cliente_op3;
                    $pedido_op3=$request->pedido_op3;
                    if($pedido_op3)
                    {
                        /*$pago=Pago::query()->where('correlativo',$pago_op3)->activo()->first();
                        $pedido=Pedido::query()->where('codigo',$pedido_op3)->activo()->first();
                        if($pago && $pedido)
                        {
                            $pedido->update([
                                'pago'=>"0",
                                'pagado'=>"0",
                            ]);
                            $pago_pedido=PagoPedido::where("pedido_id",$pedido->id)->activo();
                            $pago_pedido->update(["estado"=>"0"]);
                            if($pago_pedido)
                            {
                                $count_pago_pedido=PagoPedido::where("pago_id",$pago->id)->activo()->count();
                                if($count_pago_pedido==0)
                                {
                                    $pago_pedido->update(["estado"=>"0"]);
                                }
                            }

                        }*/

                        return response()->json(['html' => "1"]);
                    }else{
                        return response()->json(['html' => "0"]);
                    }
                    break;
                case '4':
                    $asesor_op4=$request->asesor_op4;
                    $cliente_op4=$request->cliente_op4;
                    $contacto_op4=$request->contacto_op4;
                    if($cliente_op4)
                    {
                        $cliente=Cliente::query()->where("id",$cliente_op4)->update([
                            'agenda'=>$contacto_op4,
                        ]);
                        return response()->json(['html' => $cliente->id]);
                    }
                    return response()->json(['html' => "0"]);
                    break;
            }
        }


    }
}
