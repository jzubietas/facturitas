<?php
namespace App\Exports\Templates;
use App\Exports\Templates\Sheets\Pedidos\PagepedidoperdonarcourierReporteMultiple;
use Maatwebsite\Excel\Concerns\Exportable;

class PlantillaExportPedidosPerdonarCourierReporteMultiple implements \Maatwebsite\Excel\Concerns\WithMultipleSheets
{
    use Exportable;

    private $situacion='';
    private  $anio='';

    public function __construct($situacion,$anio)
    {
        $this->situacion=$situacion;
        $this->anio=$anio;
    }

    public function sheets(): array
    {
        return [
            new PagepedidoperdonarcourierReporteMultiple($this->situacion,$this->anio),
        ];
    }
}
