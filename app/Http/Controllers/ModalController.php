<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
                    $asesor_op1=$request->asesor_op1;
                    $cliente_op1=$request->cliente_op1;
                    $clientenuevo_op1=$request->clientenuevo_op1;
                    $captura_op1=$request->captura_op1;
                    break;
                case '2':break;
                    $asesor_op2=$request->asesor_op2;
                    $cliente_op2=$request->cliente_op2;
                    $cantidadpedidos_op2=$request->cantidadpedidos_op2;
                    $captura_op2=$request->captura_op2;
                case '3':
                    $asesor_op3=$request->asesor_op3;
                    $cliente_op3=$request->cliente_op3;
                    break;
                case '4':
                    $asesor_op4=$request->asesor_op4;
                    $cliente_op4=$request->cliente_op4;
                    $contacto_op4=$request->contacto_op4;
                    break;
            }
        }


    }
}
