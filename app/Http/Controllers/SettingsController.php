<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    public function settingAdmin(Request $request)
    {
        return view('settings.administration');
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
}
