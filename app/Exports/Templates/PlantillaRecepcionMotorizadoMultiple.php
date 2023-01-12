<?php

namespace App\Exports\Templates;

use App\Exports\Templates\Sheets\PagerecepcionMotorizado;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Http\Request;

class PlantillaRecepcionMotorizadoMultiple implements WithMultipleSheets
{
    use Exportable;
    public int $user_motorizado_p;
    public string $fecha_envio_p;
    public function __construct($user_motorizado,$fecha_envio)
    {
        $this->user_motorizado_p=$user_motorizado;
        $this->fecha_envio_p=$fecha_envio;
    }
    public function sheets(): array
    {
        return [
            new PagerecepcionMotorizado($this->user_motorizado_p,$this->fecha_envio_p),
        ];
    }
}
