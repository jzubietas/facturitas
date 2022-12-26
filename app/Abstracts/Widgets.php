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

        if (request()->has("start_date") && request()->has("end_date")) {
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
            $formatA = $this->startDate->format('m/Y');
            if ($this->endDate != null) {
                $formatB = $this->endDate->format('m/Y');
                if ($formatA == $formatB) {
                    return $formatA;
                } else {
                    return $formatA . ' - ' . $formatB;
                }
            }
            return $formatA;
        }
        return '';
    }
}
