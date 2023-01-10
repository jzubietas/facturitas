<?php

namespace App\Exports\Templates;

use App\Exports\Templates\Sheets\Envios\Motorizado\Confirmar;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Http\Request;

class PlantillaMotorizadoConfirmarExportMultiple implements WithMultipleSheets
{
    use Exportable;
    protected $anio;

    public function __construct($anio)
    {
        $this->anio=$anio;
    }

    public function sheets(): array
    {
        return [
            new PageMotorizadoConfirmar(),
        ];
    }
}
