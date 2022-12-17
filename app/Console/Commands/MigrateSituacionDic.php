<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Console\Command;

class MigrateSituacionDic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:pedido:situacion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $old = [];
    protected $status = [];
    protected $resultados = [];

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
        $this->RECURRENTE();
        return;
        Cliente::query()
            //->where('celular','=','999033256')
            ->whereRaw('(select count(*) from pedidos where pedidos.cliente_id=clientes.id and pedidos.estado=1 and pedidos.pago in (0,1) and pedidos.pagado in (0,1) and pedidos.created_at>=\'' . now()->startOfMonth()->format("Y-m-d H:i:s") . '\')>0')
            ->chunk(1000, function ($clientes) {
                foreach ($clientes as $cliente) {
                    $this->info("Cliente: " . $cliente->nombre . " (" . $cliente->id . ")");
                    $this->migrate($cliente);
                }
            });

        foreach ($this->resultados as $situacion => $registros) {
            \DB::table("clientes")->whereIn('id', $registros)->update([
                "situacion" => $this->getNewStatus($situacion)
            ]);
        }
        $this->resultados = [];
        Cliente::query()
            ->whereRaw('(select count(*) from pedidos where pedidos.cliente_id=clientes.id and pedidos.estado=1 and pedidos.pago in (0,1) and pedidos.pagado in (0,1) and pedidos.created_at>=\'' . now()->startOfMonth()->format("Y-m-d H:i:s") . '\')=0')
            ->whereRaw('(select count(*) from pedidos where pedidos.cliente_id=clientes.id and pedidos.estado=1 and pedidos.pago in (0,1) and pedidos.pagado in (0,1) and pedidos.created_at>=\'' . now()->subMonth()->startOfMonth()->format("Y-m-d H:i:s") . '\')>0')
            ->chunk(1000, function ($clientes) {
                foreach ($clientes as $cliente) {
                    $this->info("Cero Cliente: " . $cliente->nombre . " (" . $cliente->id . ")");
                    $this->migrateCero($cliente);
                }
            });

        foreach ($this->resultados as $situacion => $registros) {
            \DB::table("clientes")->whereIn('id', $registros)->update([
                "situacion" => $this->getNewStatusCero($situacion)
            ]);
        }

        file_put_contents(__DIR__ . "/backup_prod.json", json_encode($this->old));
        return 0;
    }

    public function RECURRENTE()
    {
        Cliente::query()
            ->whereRaw('(select count(*) from pedidos where pedidos.cliente_id=clientes.id and pedidos.estado=1 and pedidos.pago in (0,1) and pedidos.pagado in (0,1) and pedidos.created_at>=\'2022-12-01\')=0')
            ->whereRaw('(select count(*) from pedidos where pedidos.cliente_id=clientes.id and pedidos.estado=1 and pedidos.pago in (0,1) and pedidos.pagado in (0,1) and pedidos.created_at between \'2022-10-01 00:00:00\' and \'2022-10-31 11:59:00\')>0')
            ->chunk(1000, function ($clientes) use (&$data) {

                $dataR = [];
                $data = [];
                foreach ($clientes as $cliente) {
                    $pedidoscount = $cliente->pedidos()->where('pedidos.cliente_id', $cliente->id)->where('pedidos.estado', '=', 1)
                        ->whereIn('pedidos.pago', [0, 1])
                        ->whereIn('pedidos.pagado', [0, 1])
                        ->whereBetween('pedidos.created_at', ['2022-11-01 00:00:00', '2022-11-30 11:59:00'])
                        ->count();

                    if ($pedidoscount > 0) {
                        $dataR[] = $cliente->id;
                    } else {
                        $data[] = $cliente->id;
                    }

                    $this->info("Cero Cliente: " . $cliente->nombre . " (" . $cliente->id . ")");
                }
                \DB::table("clientes")->whereIn('id', $dataR)->update([
                    "situacion" => 'RECURRENTE'
                ]);

                \DB::table("clientes")->whereIn('id', $data)->update([
                    "situacion" => 'ABANDONO RECIENTE'
                ]);
            });
    }

    public function getNewStatus($status)
    {
        switch ($status) {
            case "ABANDONO RECIENTE":
                return "RECUPERADO RECIENTE";
            case "RECURRENTE":
            case "RECUPERADO":
            case "RECUPERADO ABANDONO":
            case "NUEVO":
                return "RECURRENTE";
            case "ABANDONO PERMANENTE":
                return "RECUPERADO ABANDONO";
            case "NO EXISTE":
                return "NUEVO";
            default:
                throw new \Exception("No exte $status");
        }
    }

    public function getNewStatusCero($status)
    {
        switch ($status) {
            case "ABANDONO RECIENTE":
                return "RECUPERADO PERMANENTE";
            case "ABANDONO PERMANENTE":
                return "ABANDONO PERMANENTE";
            case "RECURRENTE":
            case "RECUPERADO":
            case "RECUPERADO ABANDONO":
            case "RECUPERADO RECIENTE":
                return "ABANDONO RECIENTE";
            case "NUEVO":
            case "RECUPERADO PERMANENTE":
                return "RECURRENTE";
            case "NO EXISTE":
                return "NO EXISTE";
            default:
                throw new \Exception("No exte cero $status");
        }
    }

    public function migrate(Cliente $cliente)
    {
        if (now()->format("Y-m") == "2022-12") {
            $status = "";
            switch ($cliente->situacion) {
                case "ABANDONO RECIENTE":
                    $status = "RECUPERADO RECIENTE";
                    break;
                case "RECURRENTE":
                case "RECUPERADO":
                case "RECUPERADO ABANDONO":
                case "RECUPERADO RECIENTE":
                case "NUEVO":
                    $status = "RECURRENTE";
                    break;
                case "ABANDONO PERMANENTE":
                    $status = "RECUPERADO ABANDONO";
                    break;
                case "NO EXISTE":
                    $status = "NUEVO";
                    break;
            }

            if (!empty($status)) {

                $situacion = $cliente->situacion;
                if ($situacion == $status) {
                    return;
                }
                //999033256
                if (!isset($this->resultados[$status])) {
                    $this->resultados[$status] = [];
                }

                $this->resultados[$situacion][] = $cliente->id;
                $this->status[$situacion] = $status;
                /*$cliente->update([
                    "situacion" => $status
                ]);*/
                $this->warn($cliente->celular . " :Cambio de situacion: " . $situacion . "  ----> " . $status);
                $this->old[] = [
                    "cliente_id" => $cliente->id,
                    "situacion" => $status,
                    "situacion_old" => $situacion,
                ];
            }
        }
    }

    public function migrateCero(Cliente $cliente)
    {
        if (now()->format("Y-m") == "2022-12") {
            $status = "";
            switch ($cliente->situacion) {
                case "ABANDONO RECIENTE":
                    $status = "RECUPERADO PERMANENTE";
                    break;
                case "ABANDONO PERMANENTE":
                    $status = "ABANDONO PERMANENTE";
                    break;
                case "RECURRENTE":
                    $status = "ABANDONO RECIENTE";
                    break;
                case "RECUPERADO":
                case "RECUPERADO ABANDONO":
                case "RECUPERADO RECIENTE":
                case "NUEVO":
                case "RECUPERADO PERMANENTE":
                    $status = "RECURRENTE";
                    break;
                case "NO EXISTE":
                    $status = "NO EXISTE";
                    break;
            }

            if (!empty($status)) {

                $situacion = $cliente->situacion;
                if ($situacion == $status) {
                    return;
                }
                //999033256
                if (!isset($this->resultados[$status])) {
                    $this->resultados[$status] = [];
                }

                $this->resultados[$situacion][] = $cliente->id;
                $this->status[$situacion] = $status;
                /*$cliente->update([
                    "situacion" => $status
                ]);*/
                $this->warn($cliente->celular . " :Cambio de situacion: " . $situacion . "  ----> " . $status);
                $this->old[] = [
                    "cliente_id" => $cliente->id,
                    "situacion" => $status,
                    "situacion_old" => $situacion,
                ];
            }
        }
    }
}
