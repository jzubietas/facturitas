<?php

namespace App\Exports\Templates;

use App\Exports\Templates\Sheets\PageclienteInfo;
use App\Exports\Templates\Sheets\PageclienteSituacion;
use App\Exports\Templates\Sheets\PageclienteOctubre;
use App\Exports\Templates\Sheets\PageclienteinfoSetiembre;
use App\Exports\Templates\Sheets\PageclienteinfoAgosto;
use App\Exports\Templates\Sheets\PageclienteinfoJulio;
use App\Exports\Templates\Sheets\PageclienteinfoOctubre;
use App\Exports\Templates\Sheets\PageclienteNoviembre;
use App\Exports\Templates\Sheets\PageclienteinfoNoviembre;
use App\Exports\Templates\Sheets\PageclienteDiciembre;
use App\Exports\Templates\Sheets\PageclienteinfoDiciembre;
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
            //new PageclienteInfo($this->anio),
            //new PageclienteinfoJulio(),
            //new PageclienteinfoAgosto(),
            //new PageclienteinfoSetiembre(),
            //new PageclienteOctubre(),
            new PageclienteinfoOctubre(),
            //new PageclienteNoviembre(),
           new PageclienteinfoNoviembre(),
            //new PageclienteDiciembre(),
            new PageclienteinfoDiciembre(),
        ];
    }
}
