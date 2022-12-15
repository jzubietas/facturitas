<?php
namespace App\Exports\Templates\Sheets;

use App\Abstracts\Export;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PageclienteinfoNoviembre extends Export implements WithColumnFormatting,WithColumnWidths
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
                //,'clientes.situacion'
                ,DB::raw(" (select a.s_2022_11 from listado_resultados a where a.id=clientes.id ) as situacion ")
                ,'clientes.created_at'
            )
            ->where('clientes.estado', '1')
            ->where('clientes.tipo', '1')
            ->get();
    }
    public function title(): string
    {
        return 'Detalle Noviembre';
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
            ,'F' => 8
            ,'G' => 8
            ,'H' => 8
        ];
    }

    public function fields(): array
    {
        // columna de la base de datos => nombre de la columna en excel
        return [
            "Id"=>"Id"
            ,"Asesor"=>"Asesor"
            ,"Nombre"=>"Nombre"
            ,"Dni"=>"Dni"
            ,"icelular"=>"icelular"
            ,"celular"=>"celular"
            ,"situacion"=>"situacion"
            ,"created_at"=>"created_at"
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
            'H' => NumberFormat::FORMAT_DATE_YYYYMMDD

        ];
    }
}
