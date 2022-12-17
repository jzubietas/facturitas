<?php

namespace App\Abstracts\GraficosComponent;

use Illuminate\Support\Str;
use Illuminate\View\Component;
abstract class GraficosComponent extends Component
{
    public $title = null;
    public $labelX = null;
    public $labelY = null;
    public $container = null;
    public $height = '370';
    public $genId = '370';

    public function __construct($title=null, $labelX=null, $labelY=null,  string $height='370')
    {
        $this->title = $title;
        $this->labelX = $labelX;
        $this->labelY = $labelY;
        $this->height = $height;
        $this->genId = Str::slug($title,'').Str::random(40);
    }

}
