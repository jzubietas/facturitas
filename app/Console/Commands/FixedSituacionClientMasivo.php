<?php

namespace App\Console\Commands;

use App\Jobs\PostCreatePedido;
use App\Models\Cliente;
use Illuminate\Console\Command;

class FixedSituacionClientMasivo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:situacion:fixed';

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
        $query = Cliente::whereNull('situacion')
            ->orWhere('situacion', '=', '');

        $count = $query->count();
        $progress = $this->output->createProgressBar($count);
        $query->chunk(1000, function ($clientes) use ($progress) {
            foreach ($clientes as $cliente) {
                //$this->warn("Cliente: " . $cliente->id . '  --  ' . $cliente->nombre);
                PostCreatePedido::dispatchSync($cliente->id);
                //$this->info("Fnish Cliente: " . $cliente->id . '  --  ' . $cliente->nombre);
                $progress->advance();
            }
        });
        $progress->finish();
        return 0;
    }
}
