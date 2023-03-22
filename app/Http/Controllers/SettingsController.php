<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DireccionGrupo;
use App\Models\Directions;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{

    public function settingAdmin(Request $request)
    {
        $jefe_operaciones_courier = User::query()->leftJoin('directions as tpj', 'tpj.user_id' , 'users.id' )->whereIn('users.rol', [User::ROL_JEFE_OPERARIO, User::ROL_JEFE_COURIER])
            ->select([
                'users.*',
                'tpj.direccion_recojo',
                'tpj.numero_recojo',
                'tpj.destino',
                'tpj.referencia',
                'tpj.cliente',

            ])->get();

        return view('settings.administration', compact('jefe_operaciones_courier',));

    }

    public function settingTimeClienteStore(Request $request)
    {
        $this->validate($request, [
            'cantidad_pedido' => 'required|integer',
            'cantidad_tiempo' => 'required|numeric',
            'cliente_celular' => 'required',
        ], [
            'cantidad_pedido.required' => 'La cantidad del pedido es requerido',
            'cantidad_pedido.integer' => 'La cantidad del pedido debe ser un numero entero',
            'cantidad_tiempo.required' => 'El tiempo es requerido',
            'cantidad_tiempo.numeric' => 'El tiempo debe ser numerico',
            'cliente_celular.required' => 'El cliente es requerido',
        ]);

        $cliente = Cliente::query()->where("celular", '=', $request->cliente_celular)->update([
            'crea_temporal' => 1,
            'activado_pedido' => $request->cantidad_pedido,
            'activado_tiempo' => $request->cantidad_tiempo,
            'temporal_update' => now()->addMinutes($request->cantidad_tiempo),
        ]);

        return response()->json([
            "success" => true,
            'updated' => $cliente
        ]);
    }

    public function settingAdminStore(Request $request)
    {
        $file = $request->file("attachment_one");
        $file2 = $request->file("attachment_two");
        setting()->load();
        if ($file) {
            $path = $file->store("administracion/adjuntos", "pstorage");
            $oldDisk = setting('administracion.attachments.1_5.disk');
            $oldPath = setting('administracion.attachments.1_5.path');
            if ($path && $oldDisk && $oldPath) {
                \Storage::disk($oldDisk)->delete($oldPath);
            }
            setting([
                "administracion.attachments.1_5.path" => $path,
                "administracion.attachments.1_5.disk" => 'pstorage'
            ]);
        }

        if ($file2) {
            $path = $file2->store("administracion/adjuntos", "pstorage");
            $oldDisk = setting('administracion.attachments.6_12.disk');
            $oldPath = setting('administracion.attachments.6_12.path');
            if ($path && $oldDisk && $oldPath) {
                \Storage::disk($oldDisk)->delete($oldPath);
            }
            setting([
                "administracion.attachments.6_12.path" => $path,
                "administracion.attachments.6_12.disk" => 'pstorage'
            ]);
        }
        setting()->save();
        $oldDisk = setting('administracion.attachments.1_5.disk');
        $oldPath = setting('administracion.attachments.1_5.path');

        $oldDisk2 = setting('administracion.attachments.6_12.disk');
        $oldPath2 = setting('administracion.attachments.6_12.path');
        return response()->json([
            "attachment_one" => \Storage::disk($oldDisk)->url($oldPath),
            "attachment_two" => \Storage::disk($oldDisk2)->url($oldPath2),
        ]);
    }

    public function settingStore(Request $request)
    {
        $this->validate($request, [
            'key' => 'required',
            'value' => 'required'
        ]);
        setting()->load();
        setting([$request->key => bcrypt($request->value)])
            ->save();
        return setting()->all();
    }

    public function settingStoreAgenda(Request $request)
    {
        $this->validate($request, [
            'key' => 'required',
            'value' => 'required'
        ]);
        setting()->load();
        setting([$request->key => bcrypt($request->value)])
            ->save();
        return setting()->all();
    }

    public function authorizationMotorizado(Request $request, $user)
    {
        if ($request->has('direccion_grupo') && $request->get('action') == 'reprogramacion') {
            $direccion = DireccionGrupo::query()->findOrFail($request->direccion_grupo);

            DireccionGrupo::cambiarCondicionEnvio($direccion, Pedido::ENVIO_MOTORIZADO_COURIER_INT, [
                'fecha_salida' => $direccion->reprogramacion_at,
                'reprogramacion_accept_user_id' => auth()->id(),
                'reprogramacion_accept_at' => now(),
                'motorizado_status' => 0,
                'motorizado_sustento_text' => 'Por reprogramar a la fecha ' . $direccion->reprogramacion_at->format('d-m-Y'),
            ]);

            DireccionGrupo::clearSolicitudAuthorization($user, 'reprogramacion');
            return $direccion->id;
        } elseif ($request->has('direccion_grupo') && $request->get('action') == 'cancel_reprogramacion') {
            $direccion = DireccionGrupo::query()->findOrFail($request->direccion_grupo);

            $direccion->update([
                'reprogramacion_at' => null,
            ]);
            DireccionGrupo::clearSolicitudAuthorization($user, 'reprogramacion');
            return $direccion->id;
        } else {
            DireccionGrupo::clearSolicitudAuthorization($user);
        }
        return $user->id;

    }

    public function agregardireccionjefeoperaciones(Request $request)
    {
      /*return $request->all();*/
        $distrito = "Los Olivos";
        $Distrito= $distrito;

        $referencia_JO= $request->referencia_jfo;
        $destino_JO= $request->destino_jfo;
        $cliente_JO= $request->cliente_jfo;

        $direccion_JO = $request->direccion_jfo;
        $numero_JO = $request->numero_jfo;
        $id_user = $request->user_id;
        $rol_user = User::where( 'id', $id_user )->first()->rol;

        $validar= Directions::query()->where('user_id', $id_user)->where('rol', $rol_user)->count();
        $data=array("rol"=> $rol_user , "user_id"=> $id_user, "distrito"=> $Distrito, "direccion_recojo"=>$direccion_JO,"numero_recojo"=>$numero_JO, "referencia"=>$referencia_JO, "destino"=>$destino_JO, "cliente"=>$cliente_JO);
        if ($validar > 0){
            Directions::query()->where('user_id', $id_user)->where('rol', $rol_user)->update($data);
        }else{
            Directions::query()->insert($data);
        }
        /*return $request->all();*/
      return response()->json(['html' => $data]);

    }


    public function getdireecionentrega(Request $request)
    {
        $codigo_pedido= $request->codigo_pedido;//userid de asesor
        $pedido=Pedido::where('codigo',$codigo_pedido)->first();

        $operario=User::where('id',$pedido->user_id)->first()->operario;
        $jefeop=User::where('id',$operario)->first()->jefe;

        //$result_direccion=User::where('id',$jefeop)->first()->id;
        $result_direccion=Directions::query()->where('user_id',$jefeop)->first()->direccion_recojo;



        return $result_direccion;

    }


}
