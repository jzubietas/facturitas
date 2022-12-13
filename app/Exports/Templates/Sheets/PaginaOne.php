<?php

namespace App\Exports\Templates\Sheets;

use App\Abstracts\Export;
use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PaginaOne extends Export implements WithColumnFormatting
{
    public function collection()
    {
        return Cliente::with('user')->get();
    }

    public function title(): string
    {
        return parent::title();//Por defecto se toma del nombre de la clase de php, en este caso seria "Pagina One" de titulo
    }

    public function map($model): array
    {
        //mapear datos del model que no esten la tabla
     /*
        $model->nuevo_campo = //nuevo campo
     */
        $model->fehca_formato=$model->created_at->format('');
        return parent::map($model);
    }

    public function fields(): array
    {
        // columna de la base de datos => nombre de la columna en excel
        return [
            "nombre"=>"Nombre",
            "created_at"=>"Fecha",
        ];
    }

    public function columnFormats(): array
    {
        return [
            //Formato de las columnas segun la letra
            /*
             'D' => NumberFormat::FORMAT_DATE_YYYYMMDD,
             'E' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            */
        ];
    }
}
