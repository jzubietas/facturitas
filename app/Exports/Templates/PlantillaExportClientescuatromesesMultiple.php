<?php
namespace App\Exports\Templates;
use App\Exports\Templates\Sheets\Envios\PageclienteCuatromeses;
use App\Exports\Templates\Sheets\Envios\PageclienteCuatromesesDeben;
use App\Exports\Templates\Sheets\Envios\PageclienteCuatromesesHaciaatras;
use App\Exports\Templates\Sheets\Envios\PageclienteCuatromesesNodeben;
use Maatwebsite\Excel\Concerns\Exportable;

class PlantillaExportClientescuatromesesMultiple implements \Maatwebsite\Excel\Concerns\WithMultipleSheets
{
    use Exportable;

    protected $anio;

    /*public function __construct($anio)
    {
    $this->anio=$anio;
    }
    */
    public function sheets(): array
    {
        return [
            new PageclienteCuatromesesDeben(),
            new PageclienteCuatromesesNodeben(),
            new PageclienteCuatromesesHaciaatras(),
        ];
    }
}
