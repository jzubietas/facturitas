<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MigrarOlvaSeguimiento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrar:olva-seguimiento';

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
        $grupos = DireccionGrupo::query()
            ->activo()
            ->where('distribucion', 'OLVA')
            ->where('condicion_envio_code', Pedido::ENTREGADO_CLIENTE_INT)
            ->get();
        $progress = $this->output->createProgressBar($grupos->count());
        foreach ($grupos as $grupo) {
            DireccionGrupo::cambiarCondicionEnvio($grupo, Pedido::RECEPCIONADO_OLVA_INT, [
                'motorizado_status' => 0,
                'motorizado_sustento_text' => 0,
                'motorizado_sustento_foto' => '',
            ]);

            $progress->advance();
        }
        $progress->finish();
        $grupos = DireccionGrupo::query()
            ->activo()
            ->where('distribucion', 'OLVA')
            ->where('condicion_envio', 'SEGUIMIENTO PROVINCIA - COURIER')
            ->get();
        $progress = $this->output->createProgressBar($grupos->count());
        foreach ($grupos as $grupo) {
            DireccionGrupo::cambiarCondicionEnvio($grupo, Pedido::ENTREGADO_CLIENTE_INT, [
                'motorizado_status' => 0,
                'motorizado_sustento_text' => 0,
                'motorizado_sustento_foto' => '',
            ]);
            $progress->advance();
        }
        $progress->finish();

        $permission = Permission::create([
            'name' => 'courier.seguimientoprovincia',
            'description' => 'Courier',
            'modulo' => 'Courier',
            'guard_name' => 'web',
        ]);
        $roles = Role::query()->where('name', 'Administrador')->get();
        foreach ($roles as $role){
            $role->permissions()->syncWithoutDetaching($permission->id);
        }
        return 0;
    }
}
