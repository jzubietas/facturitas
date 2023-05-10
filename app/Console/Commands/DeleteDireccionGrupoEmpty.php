<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use Illuminate\Console\Command;

class DeleteDireccionGrupoEmpty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grupos:drop-empty';

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
        $grupos=DireccionGrupo::query()
            ->whereRaw('(select count(*) from pedidos where pedidos.direccion_grupo=direccion_grupos.id)=0')
            ->delete();
        $this->info("Cantidad: ".$grupos);
        return 0;
    }
}
