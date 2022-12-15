<?php

namespace App\Exports\Templates;

use App\Exports\Templates\Sheets\PageclienteInfo;
use App\Exports\Templates\Sheets\PageclienteSituacion;
use App\Exports\Templates\Sheets\PageclienteOctubre;
use App\Exports\Templates\Sheets\PageclienteNoviembre;
use App\Exports\Templates\Sheets\PageclienteDiciembre;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Http\Request;

class PlantillaExportMultiple implements WithMultipleSheets
{
    use Exportable;
    protected $anio;

    public function __construct($anio)
    {
        $this->anio=$anio;
    }

    public function sheets(): array
    {
        return [
            new PageclienteInfo(null,$this->anio),
            //new PageclienteSituacion(/*$this->anio*/),
            new PageclienteOctubre(),
            new PageclienteNoviembre(),
            new PageclienteDiciembre(),
        ];
    }
}
