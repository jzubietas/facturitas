<?php

namespace App\Console\Commands;

use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;

class AddColumnPedidosCorrelativo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:add-colum-pedido-correlativo';

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
        \Schema::table('pedidos', function (Blueprint $table) {
            $table->string("correlativo",7)->nullable()->after('id');
        });

        

        return 0;
    }
}
