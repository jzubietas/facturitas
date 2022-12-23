<?php

namespace App\Exports\Templates;

use App\Exports\Templates\Sheets\PagerutaenvioLimaCentro;
use App\Exports\Templates\Sheets\PagerutaenvioLimaNorte;
use App\Exports\Templates\Sheets\PagerutaenvioLimaSinasignar;
use App\Exports\Templates\Sheets\PagerutaenvioLimaSur;
use App\Exports\Templates\Sheets\PagerutaenvioProvincia;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PlantillaExportRutaenvioMultiple implements WithMultipleSheets
{
    use Exportable;
    public $fecha;
    public function __construct($fecha)
    {
        $this->fecha=$fecha;
    }

    public function retornafiltro(): string{
        return $this->fecha;
    }
    public function sheets(): array
    {
        return [
            //new PagerutaenvioLimaSinasignar($this->fecha),
            new PagerutaenvioLimaNorte($this->fecha),
            new PagerutaenvioLimaCentro($this->fecha),
            new PagerutaenvioLimaSur($this->fecha),
            new PagerutaenvioProvincia($this->fecha)
        ];
    }
}
