<?php

namespace App\View\Components\common;

use Illuminate\View\Component;

class BsProgressbar extends Component
{
    public $progress=0;

    /**
     * @param int $progress
     */
    public function __construct(float $progress)
    {
        $this->progress = $progress;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.bs-progressbar');
    }
}
