<?php

namespace App\Exports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsuariosExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function usuarios1($request) {
        $usuarios1 = User::all();

        $this->usuarios1 = $usuarios1;
        return $this;
    }
    public function view(): View {
        return view('usuarios.excel.index', [
            'usuarios1'=> $this->usuarios1,
        ]);
    }

}