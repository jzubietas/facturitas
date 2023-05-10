<?php

namespace App\Exports\Templates;
use App\Exports\Templates\Sheets\Envios\ListadoOlva;
use Maatwebsite\Excel\Concerns\Exportable;
class PlantillaExportaListadoOlva implements \Maatwebsite\Excel\Concerns\WithMultipleSheets
{
    use Exportable;
    public function sheets(): array
    {
        return [
            new ListadoOlva(),
        ];
    }
}
