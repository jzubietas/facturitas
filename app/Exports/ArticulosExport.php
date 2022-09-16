<?php

namespace App\Exports;

use App\Models\Articulo;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ArticulosExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $articulos = Articulo::join('categoria_articulos as cat', 'articulos.categoria_articulo_id', 'cat.id')
            ->select(
                'cat.nombre as categoria',
                'articulos.codigo',
                'articulos.nombre as articulo',
                'articulos.descripcion',
                'articulos.stock',
                'articulos.stock_minimo',
                'articulos.precio_compra',
                'articulos.precio'
            )
            ->where('articulos.estado', '1')
            ->get();
        return view('articulos.excel.index', compact('articulos'));
    }
}
