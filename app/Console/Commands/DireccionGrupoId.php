<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use Illuminate\Console\Command;

class DireccionGrupoId extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'direccionGroupId {direccionId}';

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
    $model = DireccionGrupo::where('id', $this->argument('direccionId'))->get();
    foreach ($model as $grupo){
      DireccionGrupo::restructurarCodigos($grupo);
    }
    return 0;
  }
}
