<?php
namespace App\Exports\Templates;
use App\Exports\Templates\Sheets\basefria\PageBasefria;
use App\Exports\Templates\Sheets\Envios\PageclienteCuatromesesDeben;
use App\Exports\Templates\Sheets\Envios\PageclienteCuatromesesHaciaatras;
use App\Exports\Templates\Sheets\Envios\PageclienteCuatromesesNodeben;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class PlantillaExportBasefriaMultiple implements WithMultipleSheets
{
    use Exportable;

    public string $anio;

    public function sheets(): array
    {
        return [
            new PageBasefria(),
        ];
    }
}
