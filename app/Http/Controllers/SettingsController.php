<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DireccionGrupo;
use App\Models\User;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    public function settingAdmin(Request $request)
    {
        return view('settings.administration');
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

    public function authorizationMotorizado(Request $request, User $user)
    {
        DireccionGrupo::clearNoRecibidoAuthorization($user->id);
        return $user->id;
    }
}
