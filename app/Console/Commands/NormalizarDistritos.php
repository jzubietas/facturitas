<?php

namespace App\Console\Commands;

use App\Models\Distrito;
use App\Models\Pedido;
use Illuminate\Console\Command;

class NormalizarDistritos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalizar:distritos';

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
        Distrito::query()
            ->whereIn('distrito', [
                'ANCON',
                // 'CALLAO - MI PERU',
                'CARABAYLLO',
                'COMAS',
                'COMAS - COLLIQUE',
                'INDEPENDENCIA',
                // 'SANTA ROSA',
                'VENTANILLA',
                'SAN MARTIN DE PORRES',
            ])
            ->update([
                'zona' => 'NORTE'
            ]);

        Distrito::query()
            ->whereIn('distrito', [
                'SAN ISIDRO - CHACARILLA',
            ])
            ->delete();

        Distrito::query()->updateOrCreate([
            'distrito' => 'LURIN',
            'zona' => 'SUR',
            'provincia' => 'LIMA',
        ]);

        Distrito::query()->updateOrCreate([
            'distrito' => 'PACHACAMAC',
            'zona' => 'SUR',
            'provincia' => 'LIMA',
        ]);
        Distrito::query()->updateOrCreate([
            'distrito' => 'MANCHAY',
            'zona' => 'SUR',
            'provincia' => 'LIMA',
        ]);
        $distritosOlva = Distrito::query()->where('zona', 'OLVA')->pluck('distrito');
        Pedido::query()
            ->whereIn('env_distrito', $distritosOlva)
            ->update([
                'env_nombre_cliente_recibe' => 'OLVA CURRIER',
                'env_celular_cliente_recibe' => '--',
                'env_zona' => 'OLVA',
                'env_direccion' => 'OLVA',
                'env_distrito' => 'LOS OLIVOS',
            ]);
        Pedido::query()
            ->where('env_nombre_cliente_recibe','like','%OLVA%')
            ->update([
                'env_nombre_cliente_recibe' => 'OLVA CURRIER',
                'env_celular_cliente_recibe' => '--',
                'env_zona' => 'OLVA',
                'env_direccion' => 'OLVA',
                'env_distrito' => 'LOS OLIVOS',
            ]);
        Pedido::query()
            ->where('destino','=','PROVINCIA')
            ->update([
                'env_nombre_cliente_recibe' => 'OLVA CURRIER',
                'env_celular_cliente_recibe' => '--',
                'env_zona' => 'OLVA',
                'env_direccion' => 'OLVA',
                'env_distrito' => 'LOS OLIVOS',
            ]);
        return 0;
    }
}
