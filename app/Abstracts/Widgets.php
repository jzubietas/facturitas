<?php

namespace App\Abstracts;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\View\Component;

abstract class Widgets extends Component
{

    /**
     * @var \Illuminate\Support\Carbon
     */
    public $startDate;
    /**
     * @var \Illuminate\Support\Carbon
     */
    public $endDate;
    public $genId;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->startDate = now()->startOfMonth();
        $this->endDate = now()->endOfMonth();
        $this->genId = Str::random(40);

        if (request()->has("selected_month")) {
            try {
                $month = request('selected_month');
                $months = [
                    'enero' => 1,
                    'febrero' => 2,
                    'marzo' => 3,
                    'abril' => 4,
                    'mayo' => 5,
                    'junio' => 6,
                    'julio' => 7,
                    'agosto' => 8,
                    'septiembre' => 9,
                    'octubre' => 10,
                    'noviembre' => 11,
                    'diciembre' => 12,
                ];
                $this->startDate = now()->startOfYear()->addMonths($months[$month]-1)->startOfMonth();
                $this->endDate = $this->startDate->clone()->endOfMonth()->endOfDay();
            } catch (\Exception $ex) {
            }
        } elseif (request()->has("start_date") && request()->has("end_date")) {
            try {
                $this->startDate = Carbon::parse(request('start_date'));
                $this->endDate = Carbon::parse(request('end_date'));
            } catch (\Exception $ex) {
            }
        }
    }

    public function applyFilter($query, $column = 'created_at')
    {
        return $query->whereBetween($column, [
            $this->startDate->clone(),
            $this->endDate->clone()->endOfDay()
        ]);
    }

    public function getDateTitle()
    {
        if ($this->startDate != null) {
            $formatA = $this->startDate->locale('es_PE')->translatedFormat('F - Y');
            if ($this->endDate != null) {
                $formatB = $this->endDate->locale('es_PE')->translatedFormat('F - Y');
                if ($this->startDate->format('m-Y') !=  $this->endDate->format('m-Y')) {
                    return $formatA . ' - ' . $formatB;
                }
            }
            return Str::ucfirst($formatA);
        }
        return '';
    }
}
