<?php

namespace App\Console\Commands\normalizar;

use App\Models\Cliente;
use Illuminate\Console\Command;

class ClienteTemporal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalizar:cliente_temporal';

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
        Cliente::query()->activo()->where('crea_temporal', '1')
            ->where('activado_pedido', '=', '0')
            ->update([
                'crea_temporal' => '0',
                'activado_tiempo' => '0',
            ]);
        Cliente::query()->activo()->where('crea_temporal', '1')
            ->where('temporal_update', '<', now())
            ->update([
                'crea_temporal' => '0',
                'activado_pedido' => '0',
                'activado_tiempo' => '0',
            ]);
        return 0;
    }
}
