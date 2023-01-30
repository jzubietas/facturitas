<?php
namespace App\Exports\Templates;
use App\Exports\Templates\Sheets\Envios\PageclienteCuatromeses;
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
            new PageclienteCuatromeses(),
        ];
    }
}
