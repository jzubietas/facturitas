<?php
namespace App\Exports\Templates;
use App\Exports\Templates\Sheets\Movimientos\PagemovimientoReporteMultiple;
use Maatwebsite\Excel\Concerns\Exportable;

class PlantillaExportMovimientosReporteMultiple implements \Maatwebsite\Excel\Concerns\WithMultipleSheets
{
    use Exportable;

    private $desde='';
    private  $hasta='';

    public function __construct($desde,$hasta)
    {
        $this->desde=$desde;
        $this->hasta=$hasta;
    }

    public function sheets(): array
    {
        return [
            new PagemovimientoReporteMultiple($this->desde,$this->hasta),
        ];
    }
}
