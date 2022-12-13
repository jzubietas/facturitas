<?php

namespace App\Exports\Templates;

use App\Exports\Templates\Sheets\PageclienteInfo;
use App\Exports\Templates\Sheets\PageclienteSituacion;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PlantillaExportMultiple implements WithMultipleSheets
{

    public function sheets(): array
    {
        return [
            new PageclienteInfo(),
            new PageclienteSituacion(),
        ];
    }
}
