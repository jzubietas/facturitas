<?php

namespace App\Exports\Templates;

use App\Exports\Templates\Sheets\PagerecepcionMotorizado;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Http\Request;

class PlantillaRecepcionMotorizadoMultiple implements WithMultipleSheets
{
    use Exportable;
    public int $motorizado_id;
    public string $fecha_envio;
    public int $condicion_envio;
    public function __construct($motorizado_id, $fecha_envio,$condicion_envio)
    {
        $this->motorizado_id=$motorizado_id;
        $this->fecha_envio=$fecha_envio;
        $this->condicion_envio=$condicion_envio;
    }
    public function sheets(): array
    {
        return [
            new PagerecepcionMotorizado($this->motorizado_id,$this->fecha_envio,$this->condicion_envio),
        ];
    }
}
