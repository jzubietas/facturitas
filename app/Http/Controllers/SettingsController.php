<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
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
