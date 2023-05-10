<?php

namespace App\Exports\Templates;

use App\Exports\Templates\Sheets\PagereporteLlamada;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Http\Request;

class PlantillaExportMultipleLlamada implements WithMultipleSheets
{
    use Exportable;
    //protected $anio;

    public function __construct()
    {
        //$this->anio=$anio;
    }

    public function sheets(): array
    {
        return [
            new PagereporteLlamada(),
        ];
    }
}
