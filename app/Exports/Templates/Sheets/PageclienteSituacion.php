<?php

namespace App\Exports\Templates\Sheets;

use App\Abstracts\Export;
use App\Models\Cliente;
use App\Models\ListadoResultado;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class PageclienteSituacion extends Export implements WithColumnFormatting,WithColumnWidths
{
    public function collection()
    {
        $cliente_list = [];


        $_2021_11=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
            ->select(
                DB::raw(" (select '2021') as Ejercicio "),
                DB::raw(" (select '11') as Periodo "),
                DB::raw(" (select 'Noviembre') as Periodo2 "),
                'listado_resultados.s_2021_11 as grupo',
                DB::raw('count(listado_resultados.s_2021_11) as total')
                //'cantidad'
            )
            ->groupBy(
                's_2021_11'
            );

        $_2021_12=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
            ->select(
                DB::raw(" (select '2021') as Ejercicio "),
                DB::raw(" (select '12') as Periodo "),
                DB::raw(" (select 'Diciembre') as Periodo2 "),
                'listado_resultados.s_2021_12 as grupo',
                DB::raw('count(listado_resultados.s_2021_12) as total')
                //'cantidad'
            )
            ->groupBy(
                's_2021_12'
            );

        $_2022_01=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '01') as Periodo "),
                DB::raw(" (select 'Enero') as Periodo2 "),
                'listado_resultados.s_2022_01 as grupo',
                DB::raw('count(listado_resultados.s_2022_01) as total')
                //'cantidad'
            )
            ->groupBy(
                's_2022_01'
            );

        $_2022_02=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '02') as Periodo "),
                DB::raw(" (select 'Febrero') as Periodo2 "),
                'listado_resultados.s_2022_02 as grupo',
                DB::raw('count(listado_resultados.s_2022_02) as total')
                //'cantidad'
            )
            ->groupBy(
                's_2022_02'
            );

        $_2022_03=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '03') as Periodo "),
                DB::raw(" (select 'Marzo') as Periodo2 "),
                'listado_resultados.s_2022_03 as grupo',
                DB::raw('count(listado_resultados.s_2022_03) as total')
                //'cantidad'
            )
            ->groupBy(
                's_2022_03'
            );

            $_2022_04=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '04') as Periodo "),
                DB::raw(" (select 'Abril') as Periodo2 "),
                'listado_resultados.s_2022_04 as grupo',
                DB::raw('count(listado_resultados.s_2022_04) as total')
                //'cantidad'
            )
            ->groupBy(
                's_2022_04'
            );

            $_2022_05=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '05') as Periodo "),
                DB::raw(" (select 'Mayo') as Periodo2 "),
                'listado_resultados.s_2022_05 as grupo',
                DB::raw('count(listado_resultados.s_2022_05) as total')
                //'cantidad'
            )
            ->groupBy(
                's_2022_05'
            );

            $_2022_06=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '06') as Periodo "),
                DB::raw(" (select 'Junio') as Periodo2 "),
                'listado_resultados.s_2022_06 as grupo',
                DB::raw('count(listado_resultados.s_2022_06) as total')
                //'cantidad'
            )
            ->groupBy(
                's_2022_06'
            );

            $_2022_07=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '07') as Periodo "),
                DB::raw(" (select 'Julio') as Periodo2 "),
                'listado_resultados.s_2022_07 as grupo',
                DB::raw('count(listado_resultados.s_2022_07) as total')
                //'cantidad'
            )
            ->groupBy(
                's_2022_07'
            );

            $_2022_08=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '08') as Periodo "),
                DB::raw(" (select 'Agosto') as Periodo2 "),
                'listado_resultados.s_2022_08 as grupo',
                DB::raw('count(listado_resultados.s_2022_08) as total')
                //'cantidad'
            )
            ->groupBy(
                's_2022_08'
            );

            $_2022_09=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '09') as Periodo "),
                DB::raw(" (select 'Setiembre') as Periodo2 "),
                'listado_resultados.s_2022_09 as grupo',
                DB::raw('count(listado_resultados.s_2022_09) as total')
                //'cantidad'
            )
            ->groupBy(
                's_2022_09'
            );

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

            $_2022_11=ListadoResultado::join('clientes as c','c.id','listado_resultados.id')
            ->select(
                DB::raw(" (select '2022') as Ejercicio "),
                DB::raw(" (select '11') as Periodo "),
                DB::raw(" (select 'Noviembre') as Periodo2 "),
                'listado_resultados.s_2022_11 as grupo',
                DB::raw('count(listado_resultados.s_2022_11) as total')
                //'cantidad'
            )
            ->groupBy(
                's_2022_11'
            );

        $data=$_2021_11
                ->union($_2021_12)
                ->union($_2022_01)
                ->union($_2022_02)
                ->union($_2022_03)
                ->union($_2022_04)
                ->union($_2022_05)
                ->union($_2022_06)
                ->union($_2022_07)
                ->union($_2022_08)
                ->union($_2022_09)
                ->union($_2022_10)
                ->union($_2022_11);

        //$pedidos = $pedidosLima->union($pedidosProvincia);

        //$data=$data->get();
        //return Cliente::with('user')->get();
        return $data->get();
    }

    public function title(): string
    {
        //return parent::title();//Por defecto se toma del nombre de la clase de php, en este caso seria "Pagina One" de titulo
        return 'Info Situacion';
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
