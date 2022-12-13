<?php

namespace App\Exports\Templates\Sheets;

use App\Abstracts\Export;
use App\Models\Cliente;
use App\Models\Porcentaje;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PageclienteInfo extends Export implements WithColumnFormatting
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
                ,'u.name as nombre_asesor'
                ,'clientes.provincia'
                ,'clientes.distrito'
                ,'clientes.direccion'
                ,'clientes.referencia'
                ,'clientes.estado' 
                ,'clientes.deuda'
                ,'clientes.pidio'    
                ,'clientes.created_at as fecha'               
                //,DB::raw(" (select ' '  ) as fecha ")
                ,DB::raw(" (select ' '  ) as dia ")
                ,DB::raw(" (select ' '  ) as mes ")
                ,DB::raw(" (select ' '  ) as anio ")
            )
        ->where('clientes.id',1)
        ->get();
    }

    public function title(): string
    {
        //return parent::title();//Por defecto se toma del nombre de la clase de php, en este caso seria "Pagina One" de titulo
        return 'Info de Cliente';
    }

    public function map($model): array
    {
        //mapear datos del model que no esten la tabla
     /*
        $model->nuevo_campo = //nuevo campo
     */
        //$model->fehca_formato=$model->created_at->format('');
        $model->porcentajefsb= Porcentaje::select('porcentaje')
            ->where('cliente_id',$model->id)
            ->where('nombre','FISICO - sin banca')->first();
        $model->porcentajefb= Porcentaje::select('porcentaje')
            ->where('cliente_id',$model->id)
            ->where('nombre','FISICO - banca')->first();
        $model->porcentajeesb= Porcentaje::select('porcentaje')
            ->where('cliente_id',$model->id)
            ->where('nombre','ELECTRONICA - sin banca')->first();
        $model->porcentajeeb= Porcentaje::select('porcentaje')
            ->where('cliente_id',$model->id)
            ->where('nombre','ELECTRONICA - banca')->first();

        $model->eneroa = Pedido::where('estado', '1')/*->whereYear(DB::raw('Date(created_at)'), $request->anio)*/->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '1')->count();
        $model->eneroa=($model->eneroa<0)? 0:$model->eneroa;
        $model->enerop = Pedido::where('estado', '1')/*->whereYear(DB::raw('Date(created_at)'), $request->anio+1)*/->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '1')->count();

        $model->febreroa = Pedido::where('estado', '1')/*->whereYear(DB::raw('Date(created_at)'), $request->anio)*/->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '2')->count();
        $model->febrerop = Pedido::where('estado', '1')/*->whereYear(DB::raw('Date(created_at)'), $request->anio+1)*/->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '2')->count();

            

        //$model->created_at->format('');
        return parent::map($model);
    }

    public function fields(): array
    {
        // columna de la base de datos => nombre de la columna en excel

        return [
            "id"=>"Identificador"
            ,"nombre"=>"Nombre"
            ,"dni"=>"DNI"
            ,"icelular"=>"Ientificador celular"
            ,"celular"=>"Celular"
            ,"nombre_asesor"=>"Nombre Asesor"
            ,"provincia"=>"provincia"
            ,"distrito"=>"distrito"
            ,"referencia"=>"referencia"
            ,"estado"=>"estado"
            ,"deuda"=>"deuda"
            ,"pidio"=>"pidio"
            ,"fecha"=>"fecha"
            ,"dia"=>"dia"
            ,"mes"=>"mes"
            ,"anio"=>"anio"
            //,"created_at"=>"Fecha",
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
            'M' => NumberFormat::FORMAT_DATE_YYYYMMDD
        ];
    }
}

