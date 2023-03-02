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
            $alerta->update([
                'finalized_at' => now()
            ]);
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
    public function listtablecontactos(Request $request){
      $data = DetalleContactos::join('clientes as c', 'detalle_contactos.codigo_cliente', 'c.id')
        ->join('users as u', 'c.user_id', 'u.id')
        ->where('confirmado',0)
        ->orderByRaw("guardado DESC, confirmado DESC")
        ->select(['detalle_contactos.*']);
      if (in_array(auth()->user()->rol, [User::ROL_ADMIN, User::ROL_JEFE_LLAMADAS])) {
        $data = $data->whereIn('guardado',[0,1]);
      }else if (in_array(auth()->user()->rol, [User::ROL_LLAMADAS])) {
        $data = $data->where('guardado',0);
      }else if (in_array(auth()->user()->rol, [User::ROL_MOTORIZADO])) {
        $data = $data->where('guardado',0);
      }else if (in_array(auth()->user()->rol, [User::ROL_ENCARGADO])) {
        $data = $data->whereIn('guardado',[0,1]);
      }
      if (Auth::user()->rol == "Llamadas") {
        $usersasesores = User::where('users.rol', 'Asesor')
          ->where('users.estado', '1')
          ->where('users.llamada', Auth::user()->id)
          ->select(
            DB::raw("users.identificador as identificador")
          )
          ->pluck('users.identificador');
        $data = $data->WhereIn("u.identificador", $usersasesores);


      } else if (Auth::user()->rol == "Jefe de llamadas") {
        /*$usersasesores = User::where('users.rol', 'Asesor')
            ->where('users.estado', '1')
            ->where('users.llamada', Auth::user()->id)
            ->select(
                DB::raw("users.identificador as identificador")
            )
            ->pluck('users.identificador');

        $data = $data->WhereIn("u.identificador", $usersasesores);*/
      } elseif (Auth::user()->rol == "Asesor") {
        $usersasesores = User::where('users.rol', 'Asesor')
          ->where('users.estado', '1')
          ->where('users.identificador', Auth::user()->identificador)
          ->select(
            DB::raw("users.identificador as identificador")
          )
          ->pluck('users.identificador');
        $data = $data->WhereIn("u.identificador", $usersasesores);

      } else if (Auth::user()->rol == "Encargado") {
        $usersasesores = User::where('users.rol', 'Asesor')
          ->where('users.estado', '1')
          ->where('users.supervisor', Auth::user()->id)
          ->select(
            DB::raw("users.identificador as identificador")
          )
          ->pluck('users.identificador');

        $data = $data->WhereIn("u.identificador", $usersasesores);
      } else if (Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {
        //$asesorB=User::activo()->where('identificador','=','B')->pluck('id')
        $data = $data->Where("u.identificador", '=', 'B');
      }

      return Datatables::of(($data))
        ->addIndexColumn()
        ->addColumn('action', function ($data) {
          $btn = [];
          $deshabilitar_guardado="";
          $deshabilitar_confirmado="";
          if ($data->guardado==0){
            $deshabilitar_guardado="enabled ";
            $deshabilitar_confirmado="disabled";
          }else if ($data->guardado==1 && $data->confirmado==0){
            $deshabilitar_guardado="disabled";
            $deshabilitar_confirmado="enabled";
          }
          $btn[] = '<div><ul class="m-0 p-1" aria-labelledby="dropdownMenuButton" style="display: flex; grid-gap: 2px;">';

          if (in_array(auth()->user()->rol, [User::ROL_ADMIN, User::ROL_JEFE_LLAMADAS])) {
            $btn[] = '<button style="font-size:18px" class="m-0 p-2 btn btn-sm btn-success dropdown-item text-break text-wrap btnGuardado" '.$deshabilitar_guardado.'><i class="fa fa-save text-success mr-8"></i></button>';
            $btn[] = '<button style="font-size:18px" class="m-0 p-2 btn btn-sm btn-danger dropdown-item text-break text-wrap btnConfirmado" '.$deshabilitar_confirmado.'><i class="fa fa-check danger mr-8"></i></button>';
          }else if (in_array(auth()->user()->rol, [User::ROL_LLAMADAS])) {
            $btn[] = '<button style="font-size:18px" class="m-0 p-2 btn btn-sm btn-success dropdown-item text-break text-wrap btnGuardado" '.$deshabilitar_guardado.'><i class="fa fa-save text-success mr-8"></i></button>';
          }


          $btn[] = '</ul></div>';
          return join('', $btn);
        })
        ->rawColumns(['action'])
        ->make(true);
      //return datatables($detallecontactos)->toJson();
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

  public function guardado(Request $request)
  {
    //return $request->all();
    $detallecontactos=DetalleContactos::where('id',$request->detalle_contactos_id)->update([
      'guardado' => true,
      'confirmado' => false,
    ]);
    return $detallecontactos;
  }

  public function confirmado(Request $request)
  {
    //return $request->all();
    $detallecontactos=DetalleContactos::where('id',$request->detalle_contactos_id)->update([
      'guardado' => true,
      'confirmado' => true,
    ]);
    return $detallecontactos;
  }
}
