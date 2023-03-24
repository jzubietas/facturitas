<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ConsoleTVs\Charts\Facades\Charts;

class ChartController extends Controller
{
    //

    public function getData(Request $request)
    {
        $labels = ['Jan', 'Feb', 'Mar'];
        $values = [10, 20, 30];

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}
