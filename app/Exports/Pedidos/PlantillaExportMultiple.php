<?php

namespace App\Exports\Templates;

use App\Exports\Templates\Sheets\PagepedidosInfo;
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
            //new PageclienteinfoOctubre(),
            //new PageclienteNoviembre(),
           new PageclienteinfoNoviembre(),
            //new PageclienteDiciembre(),
            new PageclienteinfoDiciembre(),
            new PageclienteinfoEnero2023(),
        ];
    }
}
