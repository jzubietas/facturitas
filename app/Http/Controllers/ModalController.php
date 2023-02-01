<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
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
                    $clientenuevo_op1=(($request->clientenuevo_op1)? $request->clientenuevo_op1:0);
                    $clientenuevocontacto_op1=(($request->clientenuevocontacto_op1)? $request->clientenuevocontacto_op1:'');
                    $captura_op1=$request->captura_op1;
                    $letra=Cliente::where("id",$cliente_op1)->activo()->first()->icelular;
                    $referencia=Cliente::where("id",$cliente_op1)->activo()->first()->celular;

                    $name="";
                    if($cliente_op1)
                    {
                        $name=Cliente::where('id',$cliente_op1)->activo()->first()->nombre;
                    }

                    try {
                        DB::beginTransaction();

                        $users=User::where("rol",User::ROL_ADMIN)->activo()->get();

                        $msj='';
                        if($clientenuevocontacto_op1=='')
                        {
                            $msj='Se solicitó la creación de referido al cliente '.$name.', con el numero '.$clientenuevo_op1.'. Se necesita atención.';
                        }else{
                            $msj='Se solicitó la creación de referido al cliente '.$name.', con el numero '.$clientenuevo_op1.' y nombre '.$clientenuevocontacto_op1.'. Se necesita atención.';
                        }
                        foreach ($users as $userr)
                        {
                            $alerta = Alerta::create([
                                'user_id' => $userr->id,
                                'tipo'=>'error',
                                'subject' => 'BASE FRIA Y REFERIDO',
                                'message' => $msj,
                                'date_at' => now(),
                            ]);
                        }
                        DB::commit();
                        return response()->json(['html' => $alerta->id]);
                    } catch (\Throwable $th) {
                        return response()->json(['html' => "0"]);
                    }
                    /*try {
                        DB::beginTransaction();

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
                        DB::commit();
                        return response()->json(['html' => $cliente->id]);
                    } catch (\Throwable $th) {
                        return response()->json(['html' => "0"]);
                    }*/
                    //return response()->json(['html' => $cliente->id]);

                    break;
                case '2':
                    //autorizacion para poner pedido
                    $asesor_op2=$request->asesor_op2;
                    $cliente_op2=$request->cliente_op2;
                    $cantidadpedidos_op2=( ($request->cantidadpedidos_op2)? $request->cantidadpedidos_op2:"0" );
                    $captura_op2=$request->captura_op2;
                    $name="";
                    if($cliente_op2)
                    {
                        $name=Cliente::where('id',$cliente_op2)->activo()->first()->nombre;
                    }
                    /*try {
                        DB::beginTransaction();*/
                        $users=User::where("rol",User::ROL_ADMIN)->activo()->get();
                        foreach ($users as $userr)
                        {
                            $alerta = Alerta::create([
                                'user_id' => $users->id,
                                'tipo'=>'error',
                                'subject' => 'AUTORIZACION PARA SUBIR PEDIDO',
                                'message' => 'Se solicitó la creación de '.$cantidadpedidos_op2.' pedido(s) al cliente '.$name.'. Se necesita atención.',
                                'date_at' => now(),
                            ]);
                        }
                        //DB::commit();
                        return response()->json(['html' => $alerta->id]);
                    /*} catch (\Throwable $th) {
                        return response()->json(['html' => "0"]);
                    }*/
                    break;
                case '3':
                    $asesor_op3=$request->asesor_op3;
                    $cliente_op3=$request->cliente_op3;
                    $pedido_op3=$request->pedido_op3;

                    try {
                        DB::beginTransaction();
                        $users=User::where("rol",User::ROL_ADMIN)->activo()->get();
                        foreach ($users as $userr)
                        {
                            $alerta = Alerta::create([
                                'user_id' => $users->id,
                                'tipo'=>'error',
                                'subject' => 'ELIMINACION DE PAGO',
                                'message' => 'Se solicitó la eliminacion de pago al pedido '.$pedido_op3.'. Se necesita atención.',
                                'date_at' => now(),
                            ]);
                        }
                        DB::commit();
                        return response()->json(['html' => $alerta->id]);
                    } catch (\Throwable $th) {
                        return response()->json(['html' => "0"]);
                    }


                    /*if($pedido_op3)
                    {
                        //$pago=Pago::query()->where('correlativo',$pago_op3)->activo()->first();
                        $pedido=Pedido::query()->where('codigo',$pedido_op3)->activo()->first();
                        if($pedido)
                        {
                            $pedido->update([
                                'pago'=>"0",
                                'pagado'=>"0",
                            ]);
                            $detalle_pedido=DetallePedido::where('pedido_id',$pedido->id)->update(["saldo"=>($pedido->total*1)]);
                            $pago_pedido=PagoPedido::where("pedido_id",$pedido->id)->activo();
                            $pago_pedido->update(["estado"=>"0"]);
                            return response()->json(['html' => "1"]);
                        }
                        return response()->json(['html' => "0"]);
                    }else{
                        return response()->json(['html' => "0"]);
                    }*/
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
