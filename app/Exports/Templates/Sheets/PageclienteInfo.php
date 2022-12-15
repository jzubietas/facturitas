<?php

namespace App\Exports\Templates\Sheets;

use App\Abstracts\ExportYear;
use App\Models\Cliente;
use App\Models\Porcentaje;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
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

class PageclienteInfo extends ExportYear implements WithColumnFormatting, FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    //public $anio;
    /*public function __construct($anio)
    {

        parent::__construct();
        //$this->$anio=2021;
    }*/

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
                //'p.codigo as codigo'
                ,'clientes.celular'
                ,'u.name as nombre_asesor'
                ,'clientes.provincia'
                ,'clientes.distrito'
                ,'clientes.direccion'
                ,'clientes.referencia'
                ,'clientes.estado'
                ,'clientes.deuda'
                ,'clientes.pidio'
                ,'clientes.situacion as estadopedido'

                ,DB::raw("(select DATE_FORMAT(dp1.created_at,'%d-%m-%Y %h:%i:%s') from pedidos dp1 where dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fecha")
                ,DB::raw("(select DATE_FORMAT(dp2.created_at,'%d') from pedidos dp2 where dp2.cliente_id=clientes.id order by dp2.created_at desc limit 1) as dia")
                ,DB::raw("(select DATE_FORMAT(dp2.created_at,'%m') from pedidos dp2 where dp2.cliente_id=clientes.id order by dp2.created_at desc limit 1) as mes")
                ,DB::raw("(select DATE_FORMAT(dp3.created_at,'%Y') from pedidos dp3 where dp3.cliente_id=clientes.id order by dp3.created_at desc limit 1) as anio")
                ,DB::raw(" (select (dp.codigo) from pedidos dp where dp.cliente_id=clientes.id order by dp.created_at desc limit 1) as codigo ")
            )
        //->whereIn('clientes.id',[1,2,3])
        ->where('clientes.estado', '1')
        ->where('clientes.tipo', '1')
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
        try {
            $model->porcentajefsb= Porcentaje::select('porcentaje')
            ->where('cliente_id',$model->id)
            ->where('nombre','FISICO - sin banca')->first()->porcentaje;
          } catch (\Exception $e) {
            $model->porcentajefsb=0;
          }

          try {
            $model->porcentajefb= Porcentaje::select('porcentaje')
            ->where('cliente_id',$model->id)
            ->where('nombre','FISICO - banca')->first()->porcentaje;
          } catch (\Exception $e) {
            $model->porcentajefb=0;
          }

          try {
            $model->porcentajeesb= Porcentaje::select('porcentaje')
            ->where('cliente_id',$model->id)
            ->where('nombre','ELECTRONICA - sin banca')->first()->porcentaje;
          } catch (\Exception $e) {
            $model->porcentajeesb=0;
          }

          try {
            $model->porcentajeeb= Porcentaje::select('porcentaje')
            ->where('cliente_id',$model->id)
            ->where('nombre','ELECTRONICA - banca')->first()->porcentaje;
          } catch (\Exception $e) {
            $model->porcentajeeb=0;
          }


        $model->eneroa = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '1')->count();
        $model->eneroa=($model->eneroa<0)? 0:$model->eneroa;
        $model->enerop = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio+1)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '1')->count();

        $model->febreroa = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '2')->count();
        $model->febrerop = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio+1)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '2')->count();

        $model->marzoa = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '3')->count();
        $model->marzop = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio+1)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '3')->count();

        $model->abrila = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '4')->count();
        $model->abrilp = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio+1)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '4')->count();

        $model->mayoa = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '5')->count();
        $model->mayop = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio+1)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '5')->count();

        $model->junioa = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '6')->count();
        $model->juniop = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio+1)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '6')->count();

        $model->julioa = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '7')->count();
        $model->juliop = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio+1)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '7')->count();

        $model->agostoa = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '8')->count();
        $model->agostop = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio+1)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '8')->count();

        $model->setiembrea = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '9')->count();
        $model->setiembrep = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio+1)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '9')->count();

        $model->octubrea = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '10')->count();
        $model->octubrep = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio+1)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '10')->count();

        $model->noviembrea = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '11')->count();
        $model->noviembrep = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio+1)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '11')->count();

        $model->diciembrea = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '12')->count();
        $model->diciembrep = Pedido::where('estado', '1')->whereYear(DB::raw('Date(created_at)'), $this->anio+1)->where('cliente_id', $model->id)
            ->where(DB::raw('MONTH(created_at)'), '12')->count();



        if ($model->deuda == '1') {
            $model->deposito = 'DEBE';
        } else {
            $model->deposito = 'CANCELADO';
        }

        //$dateM = Carbon::now()->format('m');
        //$dateY = Carbon::now()->format('Y');

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
            ,"id_asesor"=>"Identificador Asesor"
            ,"nombre_asesor"=>"Nombre Asesor"
            ,"provincia"=>"provincia"
            ,"distrito"=>"distrito"
            ,"direccion"=>"direccion"
            ,"referencia"=>"referencia"
            ,"estado"=>"estado"
            ,"deuda"=>"deuda"
            ,"pidio"=>"pidio"
            ,"fecha"=>"fecha"
            ,"dia"=>"dia"
            ,"mes"=>"mes"
            ,"anio"=>"anio"
            ,"codigo"=>"codigo"
            ,"estadopedido"=>"estadopedido"
            ,'deposito'=>'deposito'
            ,"porcentajefsb"=>"porcentajefsb"
            ,"porcentajefb"=>"porcentajefb"
            ,"porcentajeesb"=>"porcentajeesb"
            ,"porcentajeeb"=>"porcentajeeb"
            ,"eneroa"=>"eneroa","enerop"=>"enerop"
            ,"febreroa"=>"febreroa","febrerop"=>"febrerop"
            ,"marzoa"=>"marzoa","marzop"=>"marzop"
            ,"abrila"=>"abrila","abrilp"=>"abrilp"
            ,"mayoa"=>"mayoa","mayop"=>"mayop"
            ,"junioa"=>"junioa","juniop"=>"juniop"
            ,"julioa"=>"julioa","juliop"=>"juliop"
            ,"agostoa"=>"agostoa","agostop"=>"agostop"
            ,"setiembrea"=>"setiembrea","setiembrep"=>"setiembrep"
            ,"octubrea"=>"octubrea","octubrep"=>"octubrep"
            ,"noviembrea"=>"noviembrea","noviembrep"=>"noviembrep"
            ,"diciembrea"=>"diciembrea","diciembrep"=>"diciembrep"
            //,"created_at"=>"Fecha",
        ];


                //'p.codigo as codigo',

    }

    public function columnFormats(): array
    {

        return [
            //Formato de las columnas segun la letra
            /*
             'D' => NumberFormat::FORMAT_DATE_YYYYMMDD,
             'E' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            */
            'O' => NumberFormat::FORMAT_DATE_YYYYMMDD
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => [self::class, 'afterSheet']
        ];
    }

    public static function afterSheet(AfterSheet $event){

        $style1 = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            ),
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFF000']
            ]
        );
        $style2 = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            ),
            'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['rgb' => '000FFF']
        ]
        );
        $style3 = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            ),
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '123121']
            ]
        );

        foreach ($event->sheet->getRowIterator() as $row)
        {
            if($event->sheet->getCellByColumnAndRow(2,$row->getRowIndex())=='ABANDONO RECIENTE')
            {
                $event->sheet->getStyle("A".$row->getRowIndex())->applyFromArray($style1);
            }
            else if($event->sheet->getCellByColumnAndRow(2,$row->getRowIndex())=='RECURRENTE')
            {
                $event->sheet->getStyle("B".$row->getRowIndex())->applyFromArray($style1);
            }
            else
            {
                $event->sheet->getStyle("C".$row->getRowIndex())->applyFromArray($style2);
            }

            //$row->getRowIndex();
        }

        /*$event->sheet->getStyleByColumnAndRow(3,3,)->applyFromArray(array(
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => 'FF0000')
            )
        ));*/
        //#800080

        /*foreach ($event->sheet->getRowIterator() as $row) {
            $cellIterator=$row;

                foreach($cellIterator as $cell)
                {
                    if($cell->getValue() != 'ABANDONO') {
                        $event->sheet->getStyle('T' . $cell->getColumn())->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'FF0000')
                                )
                            )
                        );
                    } else {
                        $event->sheet->getStyle('T' . $cell->getColumn())->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'FF0000')
                                )
                            )
                        );
                    }



            }
        }*/
        /*echo 'ROW: ', $cell->getRow(), PHP_EOL;
                   echo 'COLUMN: ', $cell->getColumn(), PHP_EOL;
                   echo 'COORDINATE: ', $cell->getCoordinate(), PHP_EOL;
                   echo 'RAW VALUE: ', $cell->getValue(), PHP_EOL;*/


        /*$event->sheet->styleCells(
            'T',
            [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '996633']
                ]
            ]
        );*/

//Range Columns
        /*$event->sheet->styleCells(
            'B2:E2',
            [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '336655']
                ]
            ]
        );*/
    }


}

