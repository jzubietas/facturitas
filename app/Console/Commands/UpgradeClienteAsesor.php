<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Ruc;
use App\Models\User;
use Illuminate\Console\Command;

class UpgradeClienteAsesor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:client:asesor';

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

        $userGroups = User::query()->where('rol', '=', User::ROL_ASESOR)->get()->groupBy('identificador')->values();
        $data = [];

        foreach ($userGroups as $users) {
            $users = collect($users)->sortBy('identificador');
            $data[] = [
                "ids" => $users->pluck('id'),
                "remplazar_por" => $users->pluck('id')->first(),
            ];
        }

        foreach ($data as $item) {
            Cliente::query()->whereIn('user_id',$item['ids']->all())->update([
                'user_id'=>$item['remplazar_por']
            ]);
            Ruc::query()->whereIn('user_id',$item['ids']->all())->update([
                'user_id'=>$item['remplazar_por']
            ]);
            Pedido::query()->whereIn('user_id',$item['ids']->all())->update([
                'user_id'=>$item['remplazar_por']
            ]);
        }

        $this->table([
            "user_id_nuevo",
            "remplazar_por",
        ], $data);

        return 0;
    }
}
