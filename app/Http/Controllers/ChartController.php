<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\Facades\Charts;

class ChartController extends Controller
{
    //

    public function getData(Request $request)
    {
        $labels = ['01', '01.5', '02'];
        $labels1=User::query()->rolAsesor()->get();
        $values = [10, 20, 30];


        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}
