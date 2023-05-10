<?php
namespace App\Exports\Templates;
use App\Exports\Templates\Sheets\Envios\PageclienteCuatromesesDeben;
use App\Exports\Templates\Sheets\Envios\PageclienteCuatromesesHaciaatras;
use App\Exports\Templates\Sheets\Envios\PageclienteCuatromesesNodeben;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class PlantillaExportClientescuatromesesMultiple implements WithMultipleSheets
{
    use Exportable;

    public string $anio;

    public function sheets(): array
    {
        return [
            new PageclienteCuatromesesDeben(),
            new PageclienteCuatromesesNodeben(),
            new PageclienteCuatromesesHaciaatras(),
        ];
    }
}
