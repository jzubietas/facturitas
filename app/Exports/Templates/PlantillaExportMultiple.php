<?php

namespace App\Exports\Templates;

use App\Exports\Templates\Sheets\PageclienteInfo;
use App\Exports\Templates\Sheets\PageclienteSituacion;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Http\Request;

class PlantillaExportMultiple implements WithMultipleSheets
{

    private $anio;

    public function __construct($anio)
    {
        $this->anio=$anio;
    }

    public function sheets(): array
    {
        return [
        new PageclienteInfo(/*$this->anio*/),
        new PageclienteSituacion(/*$this->anio*/),
        ];
    }
}
