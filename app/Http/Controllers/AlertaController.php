<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use App\Models\Cliente;
use App\Models\DetalleContactos;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AlertaController extends Controller
{

    public function index()
    {

    }

    public function confirmar(Request $request)
    {
        $action = $request->action;
        if ($action == 'aceptar') {

            $alerta = Alerta::findOrFail($request->alerta_id);
            if ($alerta->subject!="RECOJO"){
                $alerta->update([
                    'finalized_at' => now()
                ]);
            }
            return $alerta;

        } elseif ($action == 'cancelar') {
            $alerta = Alerta::findOrFail($request->alerta_id);
            $alerta->update([
                'read_at' => now()
            ]);
            return $alerta;
        }
        return response();
    }

    public function store(Request $request)
    {
       //return $request->all();
      $cliente=Cliente::where('id',$request->cliente_id)->first();
      $user_id=Cliente::where('id',$cliente->id)->first()->user_id;
      $asesor=User::where('id',$user_id)->first();

      $detallecontactos=DetalleContactos::create([
        'codigo_asesor' => $asesor->identificador,
        'nombre_asesor' => $asesor->name,
        'celular' => $cliente->celular."-". $cliente->icelular,
        'codigo_cliente' => $cliente->id,
        'nombres_cliente' => $cliente->nombre,
        'nombre_contacto' => $request->contacto_nombre,
        'codigo_registra' => $request->id_usuario,
      ]);
        return $detallecontactos;
    }

  public function cargarstore(Request $request)
  {
    //return $request->all();
    $cliente=Cliente::where('id',$request->cliente_id)->first();
    $user_id=Cliente::where('id',$cliente->id)->first()->user_id;
    $asesor=User::where('id',$user_id)->first();

    $detallecontactos=DetalleContactos::create([
      'codigo_asesor' => $asesor->identificador,
      'nombre_asesor' => $asesor->name,
      'celular' => $cliente->celular."-". $cliente->icelular,
      'codigo_cliente' => $cliente->id,
      'nombres_cliente' => $cliente->nombre,
      'nombre_contacto' => $request->contacto_nombre,
      'codigo_registra' => $request->id_usuario,
    ]);
    return $detallecontactos;
  }


}
