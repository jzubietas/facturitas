<?php

namespace App\Exports\Templates;

use App\Exports\Templates\Sheets\PageclienteInfo;
use App\Exports\Templates\Sheets\PageclienteinfoFebrero2023;
use App\Exports\Templates\Sheets\PageclienteSituacion;
//use App\Exports\Templates\Sheets\PageclienteOctubre;
//use App\Exports\Templates\Sheets\PageclienteinfoSetiembre;
//use App\Exports\Templates\Sheets\PageclienteinfoAgosto;
//use App\Exports\Templates\Sheets\PageclienteinfoJulio;
//use App\Exports\Templates\Sheets\PageclienteinfoOctubre;
//use App\Exports\Templates\Sheets\PageclienteNoviembre;
use App\Exports\Templates\Sheets\PageclienteinfoNoviembre;
//use App\Exports\Templates\Sheets\PageclienteDiciembre;
use App\Exports\Templates\Sheets\PageclienteinfoDiciembre;
use App\Exports\Templates\Sheets\PageclienteinfoEnero2023;
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
            new PageclienteSituacion(),
           new PageclienteinfoNoviembre(),
            new PageclienteinfoDiciembre(),
            new PageclienteinfoEnero2023(),
            new PageclienteinfoFebrero2023()
        ];
    }
}
