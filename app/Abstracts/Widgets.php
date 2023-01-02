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

        if (request()->has("selected_date")) {
            try {
                $currentDate = Carbon::createFromFormat('m-Y', request('selected_date', now()->format('m-Y')));
                $this->startDate = $currentDate->startOfMonth();
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

    public function applyFilter($query, $column = 'created_at', $dateStart = null, $dateEnd = null)
    {
        if ($dateStart == null) {
            $dateStart = $this->startDate;
        }
        if ($dateEnd == null) {
            $dateEnd = $this->endDate;
            if ($dateStart != null) {
                $dateEnd = $dateStart->clone()->endOfMonth()->endOfDay();
            }
        }
        if (is_array($column)) {
            foreach ($column as $col) {
                $query = $query->whereBetween($col, [
                    $dateStart->clone()->startOfMonth(),
                    $dateEnd->clone()->endOfDay()
                ]);
            }
            return $query;
        } else {
            return $query->whereBetween($column, [
                $dateStart->clone()->startOfMonth(),
                $dateEnd->clone()->endOfDay()
            ]);
        }

    }

    public function getDateTitle()
    {
        if ($this->startDate != null) {
            $formatA = $this->startDate->locale('es_PE')->translatedFormat('F - Y');
            if ($this->endDate != null) {
                $formatB = $this->endDate->locale('es_PE')->translatedFormat('F - Y');
                if ($this->startDate->format('m-Y') != $this->endDate->format('m-Y')) {
                    return $formatA . ' - ' . $formatB;
                }
            }
            return Str::ucfirst($formatA);
        }
        return '';
    }
}
