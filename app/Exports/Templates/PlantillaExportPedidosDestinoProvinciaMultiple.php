<?php
namespace App\Exports\Templates;
use App\Exports\Templates\Sheets\Pedidos\PagepedidosDestinoProvincia;
use Maatwebsite\Excel\Concerns\Exportable;

class PlantillaExportPedidosDestinoProvinciaMultiple implements \Maatwebsite\Excel\Concerns\WithMultipleSheets
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
            new PagepedidosDestinoProvincia(),
        ];
    }
}
