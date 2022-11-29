<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Models\Cliente;

class EnviosLima1Export implements FromArray, WithMultipleSheets
{
    
    protected $sheets;

    public function __construct(array $sheets)
    {
        $this->sheets = $sheets;
    }

    public function array(): array
    {
        return $this->sheets;
    }

    public function sheets(): array
    {
        $sheets = [
            new EnviosLimaNorte()
        ];

        return $sheets;
    }


}