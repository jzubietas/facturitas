<?php

namespace App\Exports\Templates\Sheets;

use App\Abstracts\Export;
use App\Models\ListadoResultado;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PageclienteOctubre extends Export implements WithColumnFormatting,WithColumnWidths
{
    public function collection()
    {
        $cliente_list = [];

        //now()->startOfMonth()->format("Y-m-d H:i:s")

        $_2022_10=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '10') as Periodo "),
                DB::raw(" (select 'Octubre') as Periodo2 "),
                'listado_resultados.s_2022_10 as grupo',
                DB::raw('count(listado_resultados.s_2022_10) as total')
            //'cantidad'
            )
            ->groupBy(
                's_2022_10'
            );

        $data=$_2022_10;

        //$pedidos = $pedidosLima->union($pedidosProvincia);

        //$data=$data->get();
        //return Cliente::with('user')->get();
        return $data->get();
    }

    public function title(): string
    {
        //return parent::title();//Por defecto se toma del nombre de la clase de php, en este caso seria "Pagina One" de titulo
        return 'Octubre';
    }

    public function map($model): array
    {
        //mapear datos del model que no esten la tabla
     /*
        $model->nuevo_campo = //nuevo campo
     */
        //$model->fehca_formato=$model->created_at->format('');
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
            //Formato de las columnas segun la letra
            /*
             'D' => NumberFormat::FORMAT_DATE_YYYYMMDD,
             'E' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            */
            'B' => NumberFormat::FORMAT_TEXT

        ];
    }
}
