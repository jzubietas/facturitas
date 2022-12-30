<?php

namespace App\Console\Commands;

use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddPermisions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:add-p';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /*\Schema::table('permissions', function (Blueprint $table) {
            $table->integer("confirm")->nullable()->after('adjunto');
        });*/

        /*\DB::table('permissions')->insertOrIgnore([
            'name' => 'operacion.atendidos.revertir'
            ,'description' => 'Revertir Pedidos Atendidos'
            ,'modulo' => 'Operacion'
            ,'guard_name' => 'web'
            //,'' => ''
        ]);*/

        \DB::table('permissions')->insertOrIgnore([
            'name' => 'operacion.motorizado'
            ,'description' => 'Motorizados'
            ,'modulo' => 'Operacion'
            ,'guard_name' => 'web'
            //,'' => ''
        ]);

        \DB::table('permissions')->insertOrIgnore([
            'name' => 'operacion.motorizado.confirm'
            ,'description' => 'Boton Motorizados Confirmacion'
            ,'modulo' => 'Operacion'
            ,'guard_name' => 'web'
            //,'' => ''
        ]);

        \DB::table('permissions')->insertOrIgnore([
            'name' => 'operacion.confirmmotorizado'
            ,'description' => 'Motorizados Confirmar'
            ,'modulo' => 'Operacion'
            ,'guard_name' => 'web'
            //,'' => ''
        ]);

        \DB::table('permissions')->insertOrIgnore([
            'name' => 'operacion.confirmmotorizado.confirm'
            ,'description' => 'Boton ConfirmMotorizados Confirmar'
            ,'modulo' => 'Operacion'
            ,'guard_name' => 'web'
            //,'' => ''
        ]);

        $rol=Role::first();

        $rol->givePermissionTo(Permission::whereIn('name',['operacion.motorizado','operacion.motorizado.confirm','operacion.confirmmotorizado','operacion.confirmmotorizado.confirm'])->pluck('id'));

        return 0;
    }
}
