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
      $detallecontactos = DetalleContactos::orderBy('created_at', 'DESC');
      return Datatables::of(DB::table($detallecontactos))
        ->addIndexColumn()
        ->addColumn('action', function ($detallecontactos) {
          $btn = '';
          return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
      //return datatables($detallecontactos)->toJson();
    }
    public function store(Request $request)
    {
       //return $request->all();
      /*$tipo = $request->tipo;
      if (!in_array($tipo, ['notice', 'success', 'info', 'error'])) {
          $tipo = 'notice';
      }
      $users = [auth()->id()];
      if ($request->user_add_role) {
          $users = User::rol($request->user_add_role)->activo()->pluck('id');
      }
      $alertas = [];
      foreach ($users as $id) {
          $alertas [] = Alerta::create([
              'user_id' => $id,
              'tipo' => $tipo,
              'subject' => $request->title,
              'message' => $request->nota,
              'date_at' => $request->fecha,
              'date_at' => $request->fecha,
          ]);
      }*/
      $cliente=Cliente::where('id',$request->cliente_id)->first();
      $asesor=User::where('id',$request->asesor_id)->first();
      $detallecontactos=DetalleContactos::create([
        'codigo_asesor' => $request->asesor_id,
        'nombre_asesor' => $asesor->name,
        'celular' => $cliente->celular,
        'codigo_cliente' => $cliente->id,
        'nombres_cliente' => $cliente->nombre,
        'nombre_contacto' => $request->contacto_nombre,
      ]);
        return $detallecontactos;
    }
}
