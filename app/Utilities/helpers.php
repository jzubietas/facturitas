<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

if (!function_exists("generate_bar_code")) {
    /**
     * @param string|numeric $data
     * @param int $width
     * @param int $height
     * @param string $color
     * @param bool $exportUrlData
     * @param string $type
     * @return object|string
     * @throws \Com\Tecnick\Barcode\Exception
     */
    function generate_bar_code($data, int $width = -2, int $height = -100, string $color = 'black', bool $exportUrlData = true, string $type = "C39")
    {
        $barcode = new \Com\Tecnick\Barcode\Barcode();

        if ($width > 0) {
            $width = -$width;
        }
        if ($height > 0) {
            $height = -$height;
        }

        $bobj = $barcode->getBarcodeObj(
            $type,            // Tipo de Barcode o Qr
            $data,    // Datos
            $width,            // Width
            $height,            // Height
            $color,        // Color del codigo
            array(0, 0, 0, 0)    // Padding
        );

        $data = $bobj->getPngData();
        if ($exportUrlData) {
            return "data:image/png;base64," . base64_encode($data);
        }
        return $data;
    }
}

if (!function_exists("generate_correlativo")) {
    function generate_correlativo($prefix, $next, $digit = 4)
    {
        return $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);
    }
}

if (!function_exists("money_f")) {
    function money_f($amount, $currency = 'PEN', $locale = 'es-PE')
    {
        $a = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        return $a->formatCurrency($amount, $currency);
    }
}

if (!function_exists("get_color_role")) {
    function get_color_role()
    {
        return [
            'Administrador' => ['#343a40', '#fff'],
            'Apoyo administrativo' => ['#525252', '#fff'],
            'ASESOR ADMINISTRATIVO' => ['#7c7c7c', '#000'],

            'Jefe de operaciones' => ['#e74c3c', '#fff'],
            'Jefe de llamadas' => '#e83e8c',
            'Encargado' => '#6f42c1',
            'Llamadas' => '#fd7e14',
            'Asesor' => ['#f39c12', '#000'],

            'Asistente de Pagos' => ['#28a745', '#fff'],

            'Logística' => '#795548',
            'Operario' => '#03a9f4',
            'FORMACIÓN' => '#20c997',
            'PRACTICANTE' => '#9e9e9e',
        ];
    }
}

if (!function_exists("add_query_filtros_por_roles")) {
    function add_query_filtros_por_roles($query, $table, $column = 'identificador')
    {
        if (Auth::user()->rol == User::ROL_ASESOR) {

            $query->Where($table . '.' . $column, auth()->user()->identificador);

        } else if (Auth::user()->rol == User::ROL_ENCARGADO) {

            $usersasesores = User::where('rol', User::ROL_ASESOR)
                ->activo()
                ->where('supervisor', auth()->user()->id)
                ->pluck('identificador');
            $query->whereIn($table . '.' . $column, $usersasesores);

        } else if (Auth::user()->rol == User::ROL_LLAMADAS) {

            $usersasesores = User::where('rol', User::ROL_ASESOR)
                ->activo()
                ->where('llamada', auth()->user()->id)
                ->pluck('identificador');
            $query->whereIn($table . '.' . $column, $usersasesores);

        } else if (Auth::user()->rol == User::ROL_JEFE_LLAMADAS) {

            $query->where($table . '.' . $column, '<>', 'B');

        }
    }
}
