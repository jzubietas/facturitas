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
use Illuminate\Http\Request;

class PageclienteDiciembre extends Export implements WithColumnFormatting,WithColumnWidths
{
    public function collection()
    {
        return Cliente::with('user')
            ->join('users as u', 'clientes.user_id', 'u.id')
            ->select(
                'clientes.id'
                ,'u.identificador as id_asesor'
                ,'clientes.nombre'
                ,'clientes.dni'
                ,'clientes.icelular'
                ,'clientes.celular'
                //,'clientes.situacion as situacion'
                ,DB::raw(" (select s_2022_12 from listado_resultados  where id = clientes.id ) as situacion "),
            )
            //->whereIn('clientes.id',[1,2,3])
            ->where('clientes.estado', '1')
            ->where('clientes.tipo', '1')
            ->get();
    }

    public function title(): string
    {
        //return parent::title();//Por defecto se toma del nombre de la clase de php, en este caso seria "Pagina One" de titulo
        return 'Diciembre';
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
