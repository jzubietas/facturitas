<?php

namespace App\Exports\Templates\Sheets;

use App\Abstracts\Export;
use App\Models\DireccionGrupo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;

use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class PagerecepcionMotorizado extends Export implements WithColumnFormatting, FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    use RemembersRowNumber;

    public int $motorizado_id = 0;
    public string $fecha_envio_h = '';

    public function __construct($user_motorizado_p, $fecha_envio_p)
    {
        parent::__construct();
        $this->motorizado_id = $user_motorizado_p;
        $this->fecha_envio_h = $fecha_envio_p;
    }

    public function collection()
    {
        $direccion = DireccionGrupo::where('direccion_grupos.estado', '1')
            ->join('users as u', 'direccion_grupos.user_id', 'u.id')
            ->join('clientes as c', 'direccion_grupos.cliente_id', 'c.id')
            ->select([
                DB::raw('concat(direccion_grupos.celular) as celular_recibe'),
                'direccion_grupos.correlativo',
                'direccion_grupos.codigos',
                DB::raw("(CASE when direccion_grupos.destino='LIMA' then  direccion_grupos.nombre
                                    when direccion_grupos.destino='PROVINCIA' then  direccion_grupos.direccion
                                ) as contacto_recibe_tracking"),
                'direccion_grupos.producto',
                'direccion_grupos.cantidad as QTY',
                'direccion_grupos.nombre_cliente',
                'direccion_grupos.direccion',
                'direccion_grupos.referencia',
                'direccion_grupos.distrito',
            ]);
        if (!$this->motorizado_id) {
            $direccion = $direccion->where('direccion_grupos.motorizado_id', $this->motorizado_id);
        }
        if (!$this->fecha_envio_h) {
            $direccion = $direccion->where('cast(direccion_grupos.fecha_recepcion as date)', $this->fecha_envio_h);
        }

        return $direccion->get();
    }

    public function title(): string
    {
        //return parent::title();//Por defecto se toma del nombre de la clase de php, en este caso seria "Pagina One" de titulo
        return 'Motorizado ' . $this->motorizado_id . ' ' . $this->fecha_envio_h;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8 //ITEM
            , 'B' => 8 //CODIGO
            , 'C' => 8 //ASESOR
            , 'D' => 8 //Cliente
            , 'E' => 8 //FECHA ENVIO
            , 'F' => 8 //RAZON SOCIAL
            , 'G' => 8 //DESTINO
            , 'H' => 8 //DIRECCION ENVIO
            , 'I' => 8 //REFERENCIA
            , 'J' => 8 //ESTADO DE ENVIO
        ];
    }

    public function map($model): array
    {
        //$model->Periodo=strval(str_pad($model->Periodo,2,"0"));
        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            "correlativo" => "ITEM"
            , "codigos" => "Codigo"
            , "identificador" => "Asesor"
            , "nombre" => "Cliente"
            , "fecha_recepcion" => "Fecha de Envio"
            , "producto" => "Razon Social"
            , "destino" => "Destino"
            , "direccion" => "Direccion de Envio"
            , "referencia" => "Referencia"
            , "condicion_envio" => "Estado de Envio"
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_YYYYMMDD
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => [self::class, 'afterSheet']
        ];
    }

    public static function afterSheet(AfterSheet $event)
    {
        $color_recurente = 'a9def9';
        $color_recuperadoabandono = '3a86ff';
        $color_recuperadoreciente = '00b4d8';
        $color_nuevo = 'b5e48c';
        $color_basefria = 'ffffff';
        $color_abandono = 'd62828';
        $color_abandonoreciente = 'fca311';
        $color_default = 'eff7f6';

        $style_recurrente = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_recurente)
            )
        );
        $style_recuperadoabandono = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_recuperadoabandono)
            )
        );
        $style_recuperadoreciente = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_recuperadoreciente)
            )
        );
        $style_nuevo = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_nuevo)
            )
        );
        $style_basefria = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_basefria)
            )
        );
        $style_abandono = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_abandono)
            )
        );
        $style_abandonoreciente = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_abandonoreciente)
            )
        );
        $styledefault = array(
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => $color_default)
            )
        );

        $row_cell_ = 14;
        $letter_cell = 'N';

    }
}

