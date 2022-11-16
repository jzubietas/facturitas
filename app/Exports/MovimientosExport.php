<?php

namespace App\Exports;

use App\Models\MovimientoBancario;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MovimientosExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function movimientos1($request) {
        $movimientos1 = MovimientoBancario::all();

        $this->movimientos1 = $movimientos1;
        return $this;
    }
    public function view(): View {
        return view('movimientos.excel.index', [
            'movimientos1'=> $this->movimientos1,
        ]);
    }

}