<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use Illuminate\Console\Command;

class CalcularUltimoPedidoIndividual extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:ultimopedido:individual {celular}';

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
        $clientes=Cliente::where('celular',$this->argument('celular'))->get();
        $progress = $this->output->createProgressBar($clientes->count());
        foreach($clientes as $cliente)
        {
            $idcliente=$cliente->id;
            $this->warn($cliente->id);
            Cliente::updateUltimoPedidoCliente($idcliente);
            $progress->advance();
        }
        $progress->finish();
        $this->info('FIN');
    }
}
