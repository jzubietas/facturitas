<?php

namespace App\Exports\Templates;


use App\Exports\Templates\Sheets\PageclienteinfoDiciembre;
use App\Exports\Templates\Sheets\PageclienteinfoEnero2023;
use App\Exports\Templates\Sheets\PageclienteinfoFebrero2023;
use App\Exports\Templates\Sheets\PageclienteinfoMarzo2023;
use App\Exports\Templates\Sheets\PageclienteinfoNoviembre;
use App\Exports\Templates\Sheets\PageclienteSituacion;
use App\Exports\Templates\Sheets\PagepedidosInfo;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PlantillaExportPedidoMultiple implements WithMultipleSheets
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
          //new PageclienteSituacion(),
          new PageclienteinfoNoviembre(),
          new PageclienteinfoDiciembre(),
          new PageclienteinfoEnero2023(),
          new PageclienteinfoFebrero2023(),
          new PageclienteinfoMarzo2023(),
        ];
    }
}
