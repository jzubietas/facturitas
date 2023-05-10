<?php

namespace App\Exports\Templates\Sheets;

use App\Abstracts\Export;
use App\Models\ListadoResultado;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PageclienteDiciembre extends Export implements WithColumnFormatting,WithColumnWidths
{
    public function collection()
    {
        $_2022_12=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '12') as Periodo "),
                DB::raw(" (select 'Diciembre') as Periodo2 "),
                //'situacion',
                'listado_resultados.s_2022_12 as grupo',
                DB::raw('count(listado_resultados.s_2022_12) as total')
            )
            ->groupBy(
                's_2022_12'
            );
        $data=$_2022_12;
        return $data->get();
    }
    public function title(): string
    {
        return 'Diciembre';
    }
    public function map($model): array
    {
        $model->Periodo=strval(str_pad($model->Periodo,2,"0"));//->setDataType(DataType::TYPE_STRING);
        return parent::map($model);
    }
    public function columnWidths(): array
    {
        return [
            'A' => 8
            ,'B' => 8
            ,'C' => 8
            ,'D' => 8
            ,'E' => 8
        ];
    }
    public function fields(): array
    {
        // columna de la base de datos => nombre de la columna en excel
        return [
            "Ejercicio"=>"Ejercicio"
            ,"Periodo"=>"Periodo"
            ,"Periodo2"=>"Periodo2"
            ,"grupo"=>"grupo"
            ,"total"=>"total"
        ];
    }
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT
        ];
    }
}
