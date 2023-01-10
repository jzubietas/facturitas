<?php

namespace App\Exports\Templates;

use App\Exports\Templates\Sheets\PagemotorizadoConfirmar;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Http\Request;

class PlantillaMotorizadoConfirmarMultiple implements WithMultipleSheets
{
    use Exportable;
    protected $motorizado;
    protected $hasta;
    public function __construct($motorizado,$hasta)
    {
        $this->motorizado=$motorizado;
        $this->hasta=$hasta;
    }
    public function sheets(): array
    {
        return [
            new PagemotorizadoConfirmar(),
        ];
    }
}
