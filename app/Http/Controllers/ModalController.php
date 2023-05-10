<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use App\Models\AttachCorrection;
use App\Models\Cliente;
use App\Models\Correction;
use App\Models\DetallePedido;
use App\Models\ImagenAtencion;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ModalController extends Controller
{
    public function ajax_modal_correccionpedidos(Request $request)
    {

        if($request->opcion)
        {
            $opcion=$request->opcion;
            $codigo_pdf='';
            $condicion_pedido="";
            $condiciones_en_op=[
                Pedido::ATENDIDO_OPE_INT
                ,Pedido::ENVIADO_OPE_INT
                ,Pedido::RECIBIDO_JEFE_OPE_INT
                //,Pedido::ENVIO_COURIER_JEFE_OPE_INT
                ,Pedido::ENTREGADO_SIN_SOBRE_OPE_INT
            ];
            $condiciones_despues_op=[
                Pedido::REPARTO_COURIER_INT
                ,Pedido::RECEPCIONADO_OLVA_INT
                ,Pedido::ENVIO_COURIER_JEFE_OPE_INT
                ,Pedido::RECEPCION_COURIER_INT
                ,Pedido::ENTREGADO_CLIENTE_INT
                ,Pedido::MOTORIZADO_INT
                ,Pedido::CONFIRM_VALIDADA_CLIENTE_INT
                ,Pedido::ENTREGADO_SIN_SOBRE_CLIENTE_INT
                ,Pedido::ENTREGADO_SIN_ENVIO_CLIENTE_INT
                ,Pedido::MOTORIZADO_INT
                ,Pedido::CONFIRM_MOTORIZADO_INT
                ,Pedido::EN_CAMINO_OLVA_INT
                ,Pedido::ENTREGADO_PROVINCIA_INT
            ];
            switch ($opcion)
            {
                case '1':
                    $hiden=$request->correccion_pc;
                    $sustento=$request->sustento_pc;
                    $detalle=$request->detalle_pc;
                    $codigo=$request->modalcorreccionpedido;

                    $pedido=Pedido::where('codigo',$codigo)->first();
                    $detallepedido=DetallePedido::where('codigo',$codigo)->first();
                    $correction=Correction::create([
                        'type' => 'PEDIDO COMPLETO',
                        'code'=>$pedido->codigo,
                        'ruc'=>$detallepedido->ruc,
                        'razon_social'=>$detallepedido->nombre_empresa,
                        'asesor_id'=>$pedido->user_id,
                        'asesor_identify'=>$pedido->identificador,
                        'fecha_correccion'=>now(),
                        'motivo'=>$sustento,
                        'adjuntos'=>0,
                        'detalle'=>$detalle,
                        'estado'=>true,
                        'condicion_envio'=>Pedido::CORRECCION_OPE,
                        'condicion_envio_code'=>Pedido::CORRECCION_OPE_INT
                    ]);
                    if ($request->hasFile('correcion_pc_captura')) {
                        $captura = $request->file('correcion_pc_captura')->store('pedidos/correcciones', 'pstorage');
                        AttachCorrection::create([
                            'correction_id'=>$correction->id,
                            'type'=>'captura',
                            'name'=>$captura,
                            'file_name'=>$captura,
                            'mime_type'=>$request->file('correcion_pc_captura')->getMimeType(),
                            'disk'=>'pedidos/correcciones',
                            'estado'=>1,
                        ]);
                    }
                    $condicion_pedido=$pedido->condicion_envio_code;
                    if(in_array($condicion_pedido,$condiciones_en_op))
                    {
                        $pedido->update([
                            'estado_correccion'=>"1",
                            'condicion_envio_anterior'=>$pedido->condicion_envio,
                            'condicion_envio_code_anterior'=>$pedido->condicion_envio_code,
                            'condicion_envio'=>Pedido::CORRECCION_OPE,
                            'condicion_envio_code'=>Pedido::CORRECCION_OPE_INT
                        ]);
                        $codigo_pdf=$pedido->id;
                    }else if(in_array($condicion_pedido,$condiciones_despues_op))
                    {
                        $pedido->update([
                            'estado_correccion'=>"1",
                        ]);
                        $post = Pedido::where('id',$pedido->id)->first();
                        $resourcorrelativo = $post->replicate();
                        $correla=$post->codigo;
                        $conta_correcion=Pedido::where('codigo','like',$correla.'-C%')->count();
                        $resourcorrelativo->codigo = $pedido->codigo.'-C'.($conta_correcion+1);
                        $resourcorrelativo->created_at = Carbon::now();
                        $resourcorrelativo->pago = "1";
                        $resourcorrelativo->pagado = "2";
                        $resourcorrelativo->condicion_envio_code = Pedido::CORRECCION_OPE_INT;
                        $resourcorrelativo->condicion_envio = Pedido::CORRECCION_OPE;
                        $resourcorrelativo->da_confirmar_descarga=0;

                        $resourcorrelativo->estado_correccion = '1';
                        $resourcorrelativo->save();
                        Pedido::where("id",$resourcorrelativo->id)->update([
                            'correlativo' =>  'PED'.$resourcorrelativo->id
                        ]);
                        $post_det = DetallePedido::where("pedido_id",$pedido->id)->first();
                        $resourcorrelativo_det = $post_det->replicate();
                        $resourcorrelativo_det->pedido_id = $resourcorrelativo->id;
                        $resourcorrelativo_det->codigo = $resourcorrelativo->codigo;
                        $resourcorrelativo_det->saldo = 0;
                        $resourcorrelativo_det->save();

                        Correction::where('id',$correction->id)->first()->update([
                            'code'=>$resourcorrelativo->codigo,
                        ]);

                        $destinationPath = base_path('public/storage/adjuntos/');
                        $files = $request->file('adjunto');
                        if ($request->hasFile('adjunto'))
                        {
                            foreach ($files as $file)
                            {
                                $file_name = Carbon::now()->second . $file->getClientOriginalName();
                                $file->move($destinationPath, $file_name);
                                ImagenAtencion::create([
                                    'pedido_id' => $resourcorrelativo->id,
                                    'adjunto' => $file_name,
                                    'estado' => '1',
                                    'confirm' => '1'
                                ]);
                            }
                        }

                        $codigo_pdf=$resourcorrelativo->id;
                    }
                    break;
                case '2':
                    $hiden=$request->correccion_f;
                    $sustento=$request->sustento_f;
                    $detalle=$request->detalle_f;
                    $codigo=$request->modalcorreccionpedido;
                    //return $request->correcion_f_facturas;
                    $pedido=Pedido::where('codigo',$codigo)->first();
                    $detallepedido=DetallePedido::where('codigo',$codigo)->first();
                    $correction=Correction::create([
                        'type' => 'FACTURAS',
                        'code'=>$codigo,
                        'ruc'=>$detallepedido->ruc,
                        'razon_social'=>$detallepedido->nombre_empresa,
                        'asesor_id'=>$pedido->user_id,
                        'asesor_identify'=>$pedido->identificador,
                        'fecha_correccion'=>now(),
                        'motivo'=>$sustento,
                        'aduntos'=>0,
                        'detalle'=>$detalle,
                        'estado'=>1,
                        'condicion_envio'=>Pedido::CORRECCION_OPE,
                        'condicion_envio_code'=>Pedido::CORRECCION_OPE_INT
                    ]);

                    $facturas = $request->file('correcion_f_facturas');
                    if (isset($facturas)) {
                        foreach ($facturas as $factura) {
                            $factura_file = $factura->store('pedidos/correcciones', 'pstorage');
                            AttachCorrection::create([
                                'correction_id'=>$correction->id,
                                'type'=>'factura',
                                'name'=>$factura->getClientOriginalName(),
                                'file_name'=>$factura_file,
                                'mime_type'=>$factura->getMimeType(),
                                'disk'=>'pedidos/correcciones',
                                'estado'=>1,
                            ]);
                        }
                    }
                    $adjuntos = $request->file('correcion_f_adjuntos');
                    if (isset($adjuntos)) {
                        foreach ($adjuntos as $adjunto) {
                            $adjunto_file = $adjunto->store('pedidos/correcciones', 'pstorage');
                            AttachCorrection::create([
                                'correction_id'=>$correction->id,
                                'type'=>'adjunto',
                                'name'=>$adjunto->getClientOriginalName(),
                                'file_name'=>$adjunto_file,
                                'mime_type'=>$adjunto->getMimeType(),
                                'disk'=>'pedidos/correcciones',
                                'estado'=>1,
                            ]);
                        }
                    }
                    $condicion_pedido=$pedido->condicion_envio_code;
                    if(in_array($condicion_pedido,$condiciones_en_op))
                    {
                        $pedido->update([
                            'estado_correccion'=>"1",
                            'condicion_envio_anterior'=>$pedido->condicion_envio,
                            'condicion_envio_code_anterior'=>$pedido->condicion_envio_code,
                            'condicion_envio'=>Pedido::CORRECCION_OPE,
                            'condicion_envio_code'=>Pedido::CORRECCION_OPE_INT
                        ]);
                        $codigo_pdf=$pedido->id;
                    }else if(in_array($condicion_pedido,$condiciones_despues_op))
                    {
                        $pedido->update([
                            'estado_correccion'=>"1",
                        ]);
                        $post = Pedido::where('id',$pedido->id)->first();
                        $resourcorrelativo = $post->replicate();
                        $correla=$post->codigo;
                        $conta_correcion=Pedido::where('codigo','like',$correla.'-C%')->count();
                        $resourcorrelativo->codigo = $pedido->codigo.'-C'.($conta_correcion+1);
                        $resourcorrelativo->created_at = Carbon::now();
                        $resourcorrelativo->pago = "1";
                        $resourcorrelativo->pagado = "2";
                        $resourcorrelativo->condicion_envio_code = Pedido::CORRECCION_OPE_INT;
                        $resourcorrelativo->condicion_envio = Pedido::CORRECCION_OPE;
                        $resourcorrelativo->estado_correccion = '1';
                        $resourcorrelativo->da_confirmar_descarga=0;
                        $resourcorrelativo->save();
                        Pedido::where("id",$resourcorrelativo->id)->update([
                            'correlativo' =>  'PED'.$resourcorrelativo->id
                        ]);
                        $post_det = DetallePedido::where("pedido_id",$pedido->id)->first();
                        $resourcorrelativo_det = $post_det->replicate();
                        $resourcorrelativo_det->pedido_id = $resourcorrelativo->id;
                        $resourcorrelativo_det->codigo = $resourcorrelativo->codigo;
                        $resourcorrelativo_det->saldo = 0;
                        $resourcorrelativo_det->save();

                        Correction::where('id',$correction->id)->first()->update([
                            'code'=>$resourcorrelativo->codigo,
                        ]);

                        $destinationPath = base_path('public/storage/adjuntos/');
                        $files = $request->file('adjunto');
                        if ($request->hasFile('adjunto'))
                        {
                            foreach ($files as $file)
                            {
                                $file_name = Carbon::now()->second . $file->getClientOriginalName();
                                $file->move($destinationPath, $file_name);
                                ImagenAtencion::create([
                                    'pedido_id' => $resourcorrelativo->id,
                                    'adjunto' => $file_name,
                                    'estado' => '1',
                                    'confirm' => '1'
                                ]);
                            }
                        }
                        $codigo_pdf=$resourcorrelativo->id;
                    }
                    break;
                case '3':
                    $hiden=$request->correccion_g;
                    $sustento=$request->sustento_g;
                    $adjuntos=$request->correcion_g_adjuntos;
                    $detalle=$request->detalle_g;
                    $codigo=$request->modalcorreccionpedido;
                    $pedido=Pedido::where('codigo',$codigo)->first();
                    $detallepedido=DetallePedido::where('codigo',$codigo)->first();

                    $correction=Correction::create([
                        'type' => 'GUIAS',
                        'code'=>$codigo,
                        'ruc'=>$detallepedido->ruc,
                        'razon_social'=>$detallepedido->nombre_empresa,
                        'asesor_id'=>$pedido->user_id,
                        'asesor_identify'=>$pedido->identificador,
                        'fecha_correccion'=>now(),
                        'motivo'=>$sustento,
                        'aduntos'=>0,
                        'detalle'=>$detalle,
                        'estado'=>1,
                        'condicion_envio'=>Pedido::CORRECCION_OPE,
                        'condicion_envio_code'=>Pedido::CORRECCION_OPE_INT
                    ]);
                    $adjuntos = $request->file('correcion_g_adjuntos');
                    if (isset($adjuntos)) {
                        foreach ($adjuntos as $adjunto) {
                            $adjunto_file = $adjunto->store('pedidos/correcciones', 'pstorage');
                            AttachCorrection::create([
                                'correction_id'=>$correction->id,
                                'type'=>'adjunto',
                                'name'=>$adjunto->getClientOriginalName(),
                                'file_name'=>$adjunto_file,
                                'mime_type'=>$adjunto->getMimeType(),
                                'disk'=>'pedidos/correcciones',
                                'estado'=>1,
                            ]);
                        }
                    }
                    $condicion_pedido=$pedido->condicion_envio_code;
                    if(in_array($condicion_pedido,$condiciones_en_op))
                    {
                        $pedido->update([
                            'estado_correccion'=>"1",
                            'condicion_envio_anterior'=>$pedido->condicion_envio,
                            'condicion_envio_code_anterior'=>$pedido->condicion_envio_code,
                            'condicion_envio'=>Pedido::CORRECCION_OPE,
                            'condicion_envio_code'=>Pedido::CORRECCION_OPE_INT
                        ]);
                        $codigo_pdf=$pedido->id;
                    }else if(in_array($condicion_pedido,$condiciones_despues_op))
                    {
                        $pedido->update([
                            'estado_correccion'=>"1",
                        ]);
                        $post = Pedido::where('id',$pedido->id)->first();
                        $resourcorrelativo = $post->replicate();
                        $correla=$post->codigo;
                        $conta_correcion=Pedido::where('codigo','like',$correla.'-C%')->count();
                        $resourcorrelativo->codigo = $pedido->codigo.'-C'.($conta_correcion+1);
                        $resourcorrelativo->created_at = Carbon::now();
                        $resourcorrelativo->pago = "1";
                        $resourcorrelativo->pagado = "2";
                        $resourcorrelativo->condicion_envio_code = Pedido::CORRECCION_OPE_INT;
                        $resourcorrelativo->condicion_envio = Pedido::CORRECCION_OPE;
                        $resourcorrelativo->estado_correccion = '1';
                        $resourcorrelativo->da_confirmar_descarga=0;
                        $resourcorrelativo->save();
                        Pedido::where("id",$resourcorrelativo->id)->first()->update([
                            'correlativo' =>  'PED'.$resourcorrelativo->id
                        ]);
                        $post_det = DetallePedido::where("pedido_id",$pedido->id)->first();
                        $resourcorrelativo_det = $post_det->replicate();
                        $resourcorrelativo_det->pedido_id = $resourcorrelativo->id;
                        $resourcorrelativo_det->codigo = $resourcorrelativo->codigo;
                        $resourcorrelativo_det->saldo = 0;
                        $resourcorrelativo_det->save();

                        Correction::where('id',$correction->id)->first()->update([
                            'code'=>$resourcorrelativo->codigo,
                        ]);

                        $destinationPath = base_path('public/storage/adjuntos/');
                        $files = $request->file('adjunto');
                        if ($request->hasFile('adjunto'))
                        {
                            foreach ($files as $file)
                            {
                                $file_name = Carbon::now()->second . $file->getClientOriginalName();
                                $file->move($destinationPath, $file_name);
                                ImagenAtencion::create([
                                    'pedido_id' => $resourcorrelativo->id,
                                    'adjunto' => $file_name,
                                    'estado' => '1',
                                    'confirm' => '1'
                                ]);
                            }
                        }
                        $codigo_pdf=$resourcorrelativo->id;
                    }
                    break;
                case '4':
                    $hiden=$request->correccion_b;
                    $sustento=$request->sustento_b;
                    $codigo=$request->modalcorreccionpedido;
                    $pedido=Pedido::where('codigo',$codigo)->first();
                    $detallepedido=DetallePedido::where('codigo',$codigo)->first();

                    $correction=Correction::create([
                        'type' => 'BANCARIZACIONES',
                        'code'=>$codigo,
                        'ruc'=>$detallepedido->ruc,
                        'razon_social'=>$detallepedido->nombre_empresa,
                        'asesor_id'=>$pedido->user_id,
                        'asesor_identify'=>$pedido->identificador,
                        'fecha_correccion'=>now(),
                        'motivo'=>$sustento,
                        'aduntos'=>0,
                        'detalle'=>'SIN DETALLE',
                        'estado'=>1,
                        'condicion_envio'=>Pedido::CORRECCION_OPE,
                        'condicion_envio_code'=>Pedido::CORRECCION_OPE_INT
                    ]);
                    $adjuntos = $request->file('correcion_b_adjuntos');
                    if (isset($adjuntos)) {
                        foreach ($adjuntos as $adjunto) {
                            $adjunto_file = $adjunto->store('pedidos/correcciones', 'pstorage');
                            AttachCorrection::create([
                                'correction_id'=>$correction->id,
                                'type'=>'adjunto',
                                'name'=>$adjunto->getClientOriginalName(),
                                'file_name'=>$adjunto_file,
                                'mime_type'=>$adjunto->getMimeType(),
                                'disk'=>'pedidos/correcciones',
                                'estado'=>1,
                            ]);
                        }
                    }
                    $condicion_pedido=$pedido->condicion_envio_code;
                    if(in_array($condicion_pedido,$condiciones_en_op))
                    {
                        $pedido->update([
                            'estado_correccion'=>"1",
                            'condicion_envio_anterior'=>$pedido->condicion_envio,
                            'condicion_envio_code_anterior'=>$pedido->condicion_envio_code,
                            'condicion_envio'=>Pedido::CORRECCION_OPE,
                            'condicion_envio_code'=>Pedido::CORRECCION_OPE_INT
                        ]);
                        $codigo_pdf=$pedido->id;
                    }else if(in_array($condicion_pedido,$condiciones_despues_op))
                    {
                        $pedido->update([
                            'estado_correccion'=>"1",
                        ]);
                        $post = Pedido::find($pedido->id);
                        $resourcorrelativo = $post->replicate();
                        $correla=$post->codigo;
                        $conta_correcion=Pedido::where('codigo','like',$correla.'-C%')->count();
                        $resourcorrelativo->codigo = $pedido->codigo.'-C'.($conta_correcion+1);
                        $resourcorrelativo->created_at = Carbon::now();
                        $resourcorrelativo->pago = "1";
                        $resourcorrelativo->pagado = "2";
                        $resourcorrelativo->condicion_envio_code = Pedido::CORRECCION_OPE_INT;
                        $resourcorrelativo->condicion_envio = Pedido::CORRECCION_OPE;
                        $resourcorrelativo->da_confirmar_descarga=0;
                        $resourcorrelativo->estado_correccion = '1';
                        $resourcorrelativo->save();
                        Pedido::where("id",$resourcorrelativo->id)->first()->update([
                            'correlativo' =>  'PED'.$resourcorrelativo->id
                        ]);
                        $post_det = DetallePedido::where("pedido_id",$pedido->id)->first();
                        $resourcorrelativo_det = $post_det->replicate();
                        $resourcorrelativo_det->pedido_id = $resourcorrelativo->id;
                        $resourcorrelativo_det->codigo = $resourcorrelativo->codigo;
                        $resourcorrelativo_det->saldo = 0;
                        $resourcorrelativo_det->save();

                        Correction::where('id',$correction->id)->first()->update([
                            'code'=>$resourcorrelativo->codigo,
                        ]);

                        $destinationPath = base_path('public/storage/adjuntos/');
                        $files = $request->file('adjunto');
                        if ($request->hasFile('adjunto'))
                        {
                            foreach ($files as $file)
                            {
                                $file_name = Carbon::now()->second . $file->getClientOriginalName();
                                $file->move($destinationPath, $file_name);
                                ImagenAtencion::create([
                                    'pedido_id' => $resourcorrelativo->id,
                                    'adjunto' => $file_name,
                                    'estado' => '1',
                                    'confirm' => '1'
                                ]);
                            }
                        }
                        $codigo_pdf=$resourcorrelativo->id;
                    }
                    break;
            }
            return response()->json(['html' => $correction->id,'codigo'=>$codigo_pdf]);
        }

    }

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
                            $msj='Se solicitó la creación de referido al cliente '.$name.', con el numero <b>'.$clientenuevo_op1.'</b> y nombre de contacto <b>'.$clientenuevocontacto_op1.'</b>. Se necesita atención.';
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
                                'user_id' => $userr->id,
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
