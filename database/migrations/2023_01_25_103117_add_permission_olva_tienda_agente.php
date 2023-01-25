<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddPermissionOlvaTiendaAgente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissiom = Permission::create([
            'name' => 'envios.encargado.tienda_agente',
            'guard_name' => 'web',
            'modulo' => 'Envio',
            'description' => 'Vista encargado Olva TIENDA/AGENTE',
        ]);
        $role = Role::where('name', \App\Models\User::ROL_ADMIN)->first();
        $role2 = Role::where('name', \App\Models\User::ROL_ENCARGADO)->first();
        $role->permissions()->attach($permissiom->id);
        $role2->permissions()->attach($permissiom->id);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
