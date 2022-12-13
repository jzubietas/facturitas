<?php

namespace App\Exports\Templates;

use App\Exports\Templates\Sheets\PaginaOne;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PlantillaExportMultiple implements WithMultipleSheets
{

    public function sheets(): array
    {
        return [
            new PaginaOne(),
        ];
    }
}
