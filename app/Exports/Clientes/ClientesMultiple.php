<?php

namespace App\Exports\Clientes;

use App\Exports\ClientesExport;
use App\Exports\ClientesPedidosExport;
use App\Exports\ClientesSituacionExport;
use App\Exports\Templates\Sheets\PageclienteDiciembre;
use App\Exports\Templates\Sheets\PageclienteNoviembre;
use App\Exports\Templates\Sheets\PageclienteOctubre;
use App\Exports\Templates\Sheets\PaginaOne;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ClientesMultiple  implements WithMultipleSheets
{

    public function sheets(): array
    {
        return [
            new ClientesPedidosExport(),
            //new ClientesSituacionExport(),
            new PageclienteOctubre(),
            new PageclienteNoviembre(),
            new PageclienteDiciembre(),
        ];
    }
}
