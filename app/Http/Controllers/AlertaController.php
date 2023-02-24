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
      if (in_array(auth()->user()->rol, [User::ROL_ADMIN, User::ROL_JEFE_LLAMADAS])) {
        $detallecontactos = DetalleContactos::whereIn('guardado',[0,1])
          ->where('confirmado',0)->orderByRaw("guardado DESC, confirmado DESC");
      }else if (in_array(auth()->user()->rol, [User::ROL_LLAMADAS])) {
        $detallecontactos = DetalleContactos::where('guardado',0)
          ->where('confirmado',0)->orderByRaw("guardado DESC, confirmado DESC");
      }


      return Datatables::of(DB::table($detallecontactos))
        ->addIndexColumn()
        ->addColumn('action', function ($detallecontactos) {
          $btn = [];
          $deshabilitar_guardado="";
          $deshabilitar_confirmado="";
          if ($detallecontactos->guardado==0){
            $deshabilitar_guardado="enabled ";
            $deshabilitar_confirmado="disabled";
          }else if ($detallecontactos->guardado==1 && $detallecontactos->confirmado==0){
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
      $asesor=User::where('id',$request->asesor_id)->first();
      $detallecontactos=DetalleContactos::create([
        'codigo_asesor' => $request->asesor_id,
        'nombre_asesor' => $asesor->identificador,
        'celular' => $cliente->celular."-". $cliente->icelular,
        'codigo_cliente' => $cliente->id,
        'nombres_cliente' => $cliente->nombre,
        'nombre_contacto' => $request->contacto_nombre,
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
