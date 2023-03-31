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
    function generate_bar_code($data, int $width = -2, int $height = -70, string $color = 'black', bool $exportUrlData = true, string $type = "C128A")
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

if (!function_exists("generate_correlativo_pago")) {
    function generate_correlativo_pago($prefix, $next, $digit = 4)
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
            'Jefe de courier' => '#795548',
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

if (!function_exists("add_query_filtros_por_roles_pedidos")) {
    function add_query_filtros_por_roles_pedidos($query, $column = 'u.identificador')
    {
        if (Auth::user()->rol == "Operario") {
            $asesores = User::rolAsesor()
                ->activo()
                ->Where('operario', Auth::user()->id)
                ->pluck('identificador');
            $query = $query->whereIn($column, $asesores);
        } else if (Auth::user()->rol == "Jefe de operaciones") {
            $operarios = User::where('rol', 'Operario')
                ->activo()
                ->where('jefe', Auth::user()->id)
                ->pluck('id');

            $asesores = User::rolAsesor()
                ->activo()
                ->WhereIn('operario', $operarios)
                ->pluck('identificador');

            $query = $query->whereIn($column, $asesores);
        } else if (Auth::user()->rol == "Asesor") {
            $query = $query->where($column, Auth::user()->identificador);
        } else if (Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {
            $query = $query->where($column, Auth::user()->identificador);
        } else if (Auth::user()->rol == "Super asesor") {
            $query = $query->where($column, Auth::user()->identificador);
        } else if (Auth::user()->rol == "Encargado") {
            $usersasesores = User::rolAsesor()
                ->activo()
                ->where('supervisor', Auth::user()->id)
                ->pluck('identificador');

            $query = $query->whereIn($column, $usersasesores);
        }
        return $query;
    }
}
if (!function_exists("can")) {
    function can($can)
    {
        return \auth()->user()->can($can);
    }
}
if (!function_exists("foto_url")) {
    function foto_url($path, $disk = 'pstorage')
    {
        if (!$path) {
            return $path;
        }
        return Storage::disk($disk)->url($path);
    }
}

if (!function_exists("pdf_to_image")) {
    /**
     * @throws ImagickException
     */
    function pdf_to_image($path)
    {
        $imagick = new Imagick();
        $imagick->readImage($path);
        $imagick->trimImage(0.1);
        $imagick->setImageFormat('jpg');
        $imagick->cropImage(538, 569, 20, 30);
        //$imagick->writeImage(public_path('.tester.tmp'));
        return "data:image/png;base64," . base64_encode($imagick->getImageBlob());//file_get_contents(public_path('.tester.tmp')));
    }
}

if (!function_exists("get_olva_tracking")) {
    function get_olva_tracking($tracking, $year = 23)
    {
        try{
            $response = Http::acceptJson()
                ->get('https://reports.olvaexpress.pe/webservice/rest/getTrackingInformation', [
                    'tracking' => $tracking,
                    'emision' => $year,
                    'apikey' => 'a82e5d192fae9bbfee43a964024498e87dfecb884b67c7e95865a3bb07b607dd',
                    'details' => 1
                ]);
            return $response->json();
        }catch(RequestException $e)
        {
            $arra=['status'=>0,"error"=>"mensaje"];
            return $arra->json();
        }


    }
}


if (!function_exists("user")) {
    /**
     * @return User|null
     */
    function user()
    {

        return \auth()->user();
    }
}

if (!function_exists("user_rol")) {
    function user_rol($rol = null)
    {
        $userrol = user()->rol;
        if ($rol) {
            return $userrol == $rol;
        }
        return $userrol;
    }
}
