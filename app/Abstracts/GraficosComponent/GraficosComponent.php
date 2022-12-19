<?php

namespace App\Abstracts\GraficosComponent;

use App\Abstracts\Widgets;
use Illuminate\Support\Str;
abstract class GraficosComponent extends Widgets
{
    public $title = null;
    public $labelX = null;
    public $labelY = null;
    public $container = null;
    public $height = '370';
    public $genId = '370';

    public function __construct($title=null, $labelX=null, $labelY=null,  string $height='370')
    {
        parent::__construct();
        $this->title = $title;
        $this->labelX = $labelX;
        $this->labelY = $labelY;
        $this->height = $height;
        $this->genId = Str::slug($title,'').Str::random(40);
    }

}
