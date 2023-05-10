<?php

namespace App\Exports\Templates\Sheets\basefria;

use App\Abstracts\Export;
use App\Exports\Templates\Sheets\Envios\AfterSheet;
use App\Exports\Templates\Sheets\Envios\Fill;
use App\Models\Cliente;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});
class PageBasefria extends Export implements WithColumnFormatting,WithColumnWidths
{
    public function collection()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $data=Cliente::join('users as u','u.id','clientes.user_id')
            ->activo()->where('tipo','0')->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'clientes.*','u.identificador as identificador',
                DB::raw("concat(clientes.celular,'-',ifnull(clientes.icelular,'') ) as celular_"),
                'clientes.id as ide'
            ]);

        /*if (Auth::user()->rol == User::ROL_LLAMADAS) {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $data = $data->WhereIn("u.identificador", $usersasesores);

        }else*/
        if (Auth::user()->rol == User::ROL_ASESOR) {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');
            $data = $data->WhereIn("clientes.user_id", $usersasesores);
        }
        /*else if (Auth::user()->rol == User::ROL_ENCARGADO) {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn("u.identificador", $usersasesores);
        }elseif (Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {
            $data = $data->Where("u.identificador", '=', 'B');
        }elseif (Auth::user()->rol == "Operario") {
        $asesores = User::whereIN('users.rol', ['Asesor', 'Administrador', 'ASESOR ADMINISTRATIVO'])
            ->where('users.estado', '1')
            ->Where('users.operario', Auth::user()->id)
            ->select(
                DB::raw("users.identificador as identificador")
            )
            ->pluck('users.identificador');
        $pedidos = $data->WhereIn('u.identificador', $asesores);

        }*/


        return $data->get();;
    }
    public function fields(): array
    {
        return [
            "rownum" => "ITEM"
            ,"ide"=>"ID"
            ,"nombre"=>"NOMBRE"
            ,"celular_"=>"CELULAR"
            ,"identificador"=>"ASESOR ASIGNADO"
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 8//item
            ,'B' => 8//identificador
            ,'C' => 8//celular
            ,'D' => 8//rucs
            ,'E' => 8//deuda
        ];
    }
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
        ];
    }
    public function title(): string
    {
        return 'Base fria '.Auth::user()->identificador;
    }
    public function map($model): array
    {
        $model->ide='BF'.strval(str_pad($model->ide,6,"0",STR_PAD_LEFT));
        return parent::map($model);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => [self::class, 'afterSheet']
        ];
    }

    public static function afterSheet(AfterSheet $event){

        $color_A1='e18b16';

        $event->sheet->getStyle('C')->getAlignment()->setWrapText(true);
        $event->sheet->getStyle('E')->getAlignment()->setWrapText(true);

        $event->sheet->styleCells(
            'A1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => $color_A1]
                ]
            ]
        );
        $event->sheet->styleCells(
            'B1',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => $color_A1]
                ]
            ]
        );

    }
}
