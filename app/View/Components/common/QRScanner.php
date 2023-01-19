<?php

namespace App\View\Components\common;

use Illuminate\View\Component;

class QRScanner extends Component
{
    public $moduleTitle = '';
    public $responsable;
    public $accion;
    public $tipo;
    public $extras = [];
    public $ajaxparams = [];
    public $tablesIds = [];

    public $reparto = 1;

    public $withFecha = false;

    /**
     * @param string $moduleTitle
     * @param string $responsable
     * @param string $accion
     * @param string $tipo
     * @param array $extras
     */
    public function __construct(string $moduleTitle, string $responsable, string $accion, string $tipo, array $extras = [], array $tablesIds = [], $withFecha = false, $reparto = 1)
    {
        $this->moduleTitle = $moduleTitle;
        $this->responsable = $responsable;
        $this->accion = $accion;
        $this->tipo = $tipo;
        $this->extras = $extras;
        $this->tablesIds = $tablesIds;
        $this->withFecha = $withFecha;
        $this->reparto = $reparto;
        $this->ajaxparams = [
            'responsable' => $responsable,
            'accion' => $accion,
            'tipo' => $tipo,
        ];
        foreach ($extras as $key => $value) {
            $this->ajaxparams[$key] = $value;
        }
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.q-r-scanner');
    }
}
