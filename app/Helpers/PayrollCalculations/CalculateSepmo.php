<?php

namespace App\Helpers\PayrollCalculations;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalculateSepmo
{
    static function get($employee, $start_date, $final_date, $days_worked, $ordinary_salary)
    {
        // El salario ordinario + la incapacidad / los dias trabajados
        $total_to_pay = 0;

        $start_date = new Carbon($start_date);
        $final_date = new Carbon($final_date);

        $daysOfWeek = $start_date->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isSunday();
        }, $final_date) + 1;

        if ($days_worked == $daysOfWeek) {

            $inability = CalculateInability::get($employee, $start_date, $final_date, $days_worked);

            $total_to_pay = ($ordinary_salary + $inability) / $days_worked;
        } else {
            $inabilities = DB::table('inabilities')
                ->whereRaw('
            employee_id = ? and Date(start_date) between ? and ?
        ', [$employee, $start_date, $final_date])
                ->get();

            if ($inabilities != null && $days_worked > 0) {
                foreach ($inabilities as $inability) {

                    $st_inh = new Carbon($inability->start_date);
                    $ed_inh = new Carbon($inability->end_date);
                    $fn_wk = new Carbon($final_date);

                    $periodInab = $st_inh->diffInDaysFiltered(function ($date) {
                        return !$date->isSunday();
                    }, ($ed_inh)) + 1;

                    if (($days_worked + $periodInab) == $daysOfWeek) {
                        $inability = CalculateInability::get($employee, $start_date, $final_date, $days_worked);

                        $total_to_pay += ($ordinary_salary + $inability) / $daysOfWeek;
                    }
                }
            }
        }

        return round($total_to_pay, 2);
    }
}
