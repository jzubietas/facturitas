<?php
namespace App\Exports\Templates\Sheets;

use App\Abstracts\Export;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PageclienteinfoSetiembre extends Export implements WithColumnFormatting,WithColumnWidths
{
    public function collection()
    {
        $cliente= Cliente::with('user')
            ->join('users as u', 'clientes.user_id', 'u.id')
            ->select(
                'clientes.id'
                ,'u.identificador as id_asesor'
                ,'clientes.nombre'
                ,'clientes.dni'
                ,'clientes.icelular'
                ,'clientes.celular'
                //,'clientes.situacion'
                ,DB::raw(" (select a.s_2022_09 from listado_resultados a where a.id=clientes.id ) as situacion ")
                ,DB::raw("(select DATE_FORMAT(dp1.created_at,'%Y-%m-%d %h:%i:%s') from pedidos dp1 where dp1.estado=1 and  dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fecha"),
            )
            ->where('clientes.estado', '1')
            ->where('clientes.tipo', '1');

            if (Auth::user()->rol == "Llamadas") {
                $usersasesores = User::where('users.rol', 'Asesor')
                    ->where('users.estado', '1')
                    ->where('users.llamada', Auth::user()->id)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');

                $cliente=$cliente->whereIn('clientes.user_id',$usersasesores);
            }

            return  $cliente->get();
    }
    public function fields(): array
    {
        return [
            "id"=>"Id"
            ,"id_asesor"=>"Asesor"
            ,"nombre"=>"Nombre"
            ,"dni"=>"Dni"
            ,"icelular"=>"Identificador celular"
            ,"celular"=>"Celular"
            ,"situacion"=>"Situacion"
            ,"created_at"=>"Creado"
        ];
    }
    public function title(): string
    {
        return 'Detalle Setiembre';
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

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_YYYYMMDD

        ];
    }
}
