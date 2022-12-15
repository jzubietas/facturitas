<?php

namespace App\Exports\Templates\Sheets;

use App\Abstracts\ExportYear;
use App\Models\Cliente;
use App\Models\Porcentaje;
use App\Models\Pedido;
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
                ,'clientes.situacion'
                ,DB::raw("(select DATE_FORMAT(dp1.created_at,'%Y-%m-%d %h:%i:%s') from pedidos dp1 where dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fecha")

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
            ,"provincia"=>"Provincia"
            ,"distrito"=>"Distrito"
            ,"direccion"=>"Direccion"
            ,"referencia"=>"Referencia"
            ,"estado"=>"Estado"
            ,"deuda"=>"Deuda"
            ,"pidio"=>"Pidio"
            ,"fecha"=>"Fecha"
            ,"codigo"=>"codigo"
            ,"situacion"=>"Situacion"
            ,'deposito'=>'Deposito'
            ,"porcentajefsb"=>"Porcentaje Fisico sin banca"
            ,"porcentajefb"=>"Porcentaje Fisico Bancarizado"
            ,"porcentajeesb"=>"Porcentaje Electronico sin banca"
            ,"porcentajeeb"=>"Porcentaje Electronico Bancarizado"
            ,"eneroa"=>"Enero a","enerop"=>"Enero p"
            ,"febreroa"=>"febrero a","febrerop"=>"Febrero p"
            ,"marzoa"=>"marzo a","marzop"=>"marzo p"
            ,"abrila"=>"abril a","abrilp"=>"abril p"
            ,"mayoa"=>"mayo a","mayop"=>"mayo p"
            ,"junioa"=>"junio a","juniop"=>"junio p"
            ,"julioa"=>"julio a","juliop"=>"julio p"
            ,"agostoa"=>"agosto a","agostop"=>"agosto p"
            ,"setiembrea"=>"setiembre a","setiembrep"=>"setiembre p"
            ,"octubrea"=>"octubre a","octubrep"=>"octubre p"
            ,"noviembrea"=>"noviembre a","noviembrep"=>"noviembre p"
            ,"diciembrea"=>"diciembre a","diciembrep"=>"diciembre p"
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

        /*d62828  ABANDONO
        fca311  ABANDONO RECIENTE
        blanco  base fria
        b5e48c	nuevo
        00b4d8		RECUPERADO RECIENTE
        3a86ff		RECUPERADO ABANDONO
        a9def9		RECURRENTE*/
        
        $style_recurrente = array(
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => '336655']
                ]
            ]
        );
        $stylerecuperadoabandono = array(
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => '336655']
                ]
            ]
        );
        $stylerecuperadoreciente = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            ),
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'bfd200',
                ]
            ],
        );
        $stylenuevo = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            ),
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'ffcfd2',
                ]
            ],
        );
        $stylebasefria = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            ),
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'eff7f6',
                ]
            ],
        );
        $styleabandono = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
            ),
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'ff4d6d',
                ]
            ],
        );
        $styleabandonoreciente = array(
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'e85d04']
                ]
            ]
        );
        $styledefault = array(
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => '000000']
                ]
            ]
        );

        /*
         * RECURRENTE-----90e0ef
            RECUPERADO ABANDONO----b5e48c
            RECUPERADO RECIENTE---bfd200
            NUEVO------ffcfd2
            BASE FRIA----eff7f6
            ABANDONO----ff4d6d
            ABANDONO RECIENTE----e85d04
         * */

        foreach ($event->sheet->getRowIterator() as $row)
        {
            if($event->sheet->getCellByColumnAndRow(17,$row->getRowIndex())->getValue()=='RECURRENTE')
            {
                $event->sheet->getStyle("Q".$row->getRowIndex())->applyFromArray($style_recurrente);
            }
            else if($event->sheet->getCellByColumnAndRow(17,$row->getRowIndex())->getValue()=='RECUPERADO ABANDONO')
            {
                $event->sheet->getStyle("Q".$row->getRowIndex())->applyFromArray($stylerecuperadoabandono);
            }
            else if($event->sheet->getCellByColumnAndRow(17,$row->getRowIndex())->getValue()=='RECUPERADO RECIENTE')
            {
                $event->sheet->getStyle("Q".$row->getRowIndex())->applyFromArray($stylerecuperadoreciente);
            }
            else if($event->sheet->getCellByColumnAndRow(17,$row->getRowIndex())->getValue()=='NUEVO')
            {
                $event->sheet->getStyle("Q".$row->getRowIndex())->applyFromArray($stylenuevo);
            }
            else if($event->sheet->getCellByColumnAndRow(17,$row->getRowIndex())->getValue()=='BASE FRIA')
            {
                $event->sheet->getStyle("Q".$row->getRowIndex())->applyFromArray($stylebasefria);
            }
            else if($event->sheet->getCellByColumnAndRow(17,$row->getRowIndex())->getValue()=='ABANDONO')
            {
                $event->sheet->getStyle("Q".$row->getRowIndex())->applyFromArray($styleabandono);
            }
            else if($event->sheet->getCellByColumnAndRow(17,$row->getRowIndex())->getValue()=='ABANDONO RECIENTE')
            {
                $event->sheet->getStyle("Q".$row->getRowIndex())->applyFromArray($styleabandonoreciente);
            }else{
                $event->sheet->getStyle("Q".$row->getRowIndex())->applyFromArray($styledefault);
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
        /*
        $event->sheet->styleCells(
            'Q',
            [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => '336655']
                ]
            ]
        ); */
    }


}

