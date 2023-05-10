<?php
namespace App\Exports\Templates;
use App\Exports\Templates\Sheets\Envios\PageclienteDosmeses;
use App\Exports\Templates\Sheets\Envios\PageclientsFallenDebt;
use Maatwebsite\Excel\Concerns\Exportable;

class PlantillaExportClientsFallendDebtMultiple implements \Maatwebsite\Excel\Concerns\WithMultipleSheets
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
            new PageclientsFallenDebt(),
        ];
    }
}
