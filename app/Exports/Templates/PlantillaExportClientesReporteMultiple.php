<?php
namespace App\Exports\Templates;
use App\Exports\Templates\Sheets\Clientes\PageclienteReporteMultiple;
use Maatwebsite\Excel\Concerns\Exportable;

class PlantillaExportClientesReporteMultiple implements \Maatwebsite\Excel\Concerns\WithMultipleSheets
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
            new PageclienteReporteMultiple($this->situacion,$this->anio),
        ];
    }
}
