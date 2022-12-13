<?php

namespace App\Console\Commands;

use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;

class AddColumStatusCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:add-colum';

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
            $table->integer("condicion_code")->nullable()->after('condicion');
            $table->integer("condicion_envio_code")->nullable()->after('condicion_envio');
        });

        \Schema::table('pagos', function (Blueprint $table) {
            $table->integer("condicion_code")->nullable()->after('condicion')->change();
            $table->integer("subcondicion_code")->nullable()->after('subcondicion');
        });

        Pedido::query()
            ->whereNull('condicion')
            ->whereNull('condicion_int')
            ->update([
                'condicion_code' => 1,
                'condicion' => 'POR ATENDER'
            ]);

        $pedidos = Pedido::query()
            ->whereNull('condicion')
            ->whereNotNull('condicion_int')
            ->get();

        foreach ($pedidos as $pedido) {
            $pedido->update([
                'condicion' => 'POR ATENDER',
                'condicion_code' => '1',
                'condicion_int' => '1',
            ]);
        }

        foreach (Pedido::$estadosCondicion as $estado => $code) {
            Pedido::query()->where('condicion', '=', $estado)->update([
                'condicion_code' => $code
            ]);
        }

        foreach (Pedido::$estadosCondicionEnvio as $estado => $code) {
            Pedido::query()->where('condicion_envio', '=', $estado)->update([
                'condicion_envio_code' => $code
            ]);
        }

        foreach (Pedido::$estadosCondicionEnvio as $estado => $code) {
            Pedido::query()->where('condicion_envio', '=', "$code")->update([
                'condicion_envio' => $estado,
                'condicion_envio_code' => $code,
            ]);
        }

        foreach (Pago::$migrateSubCondiciones as $estado => $code) {
            Pago::query()->where('subcondicion', '=', $estado)->update([
                'subcondicion_code' => $code,
            ]);
        }

        return 0;
    }
}
