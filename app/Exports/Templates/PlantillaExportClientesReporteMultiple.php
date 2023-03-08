<?php
namespace App\Exports\Templates;
use App\Exports\Templates\Sheets\Clientes\PageclienteReporteMultiple;
use Maatwebsite\Excel\Concerns\Exportable;

class PlantillaExportClientesReporteMultiple implements \Maatwebsite\Excel\Concerns\WithMultipleSheets
{
    use Exportable;

    public static $situacion='';
    public static $anio='';

    public function __construct($situacion,$anio)
    {
        self::$situacion=$situacion;
        self::$anio=$anio;
    }

    public function sheets(): array
    {
        return [
            new PageclienteReporteMultiple(self::$situacion,self::$anio),
        ];
    }
}
