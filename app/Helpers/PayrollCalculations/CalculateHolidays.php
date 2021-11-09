<?php

namespace App\Helpers\PayrollCalculations;

use Carbon\Carbon;
use App\Helpers\PayrollCalculations\CalculateOrdinarySalary;
use Illuminate\Support\Facades\DB;

class CalculateHolidays
{
    static function get($employee, $start_date, $final_date)
    {

        $total_to_pay = 0;

        // Setpimo de la ultima semamana devengada
        // sino se le calcula el septimo con los dias trabajados de la semana

        $holidays = DB::table('holidays')->select('date')->get();

        if ($holidays != null) {

            $holidaysInWeek = [];

            foreach ($holidays as $holiday) {
                $day = Carbon::create($holiday->date);
                if ($day->betweenIncluded($start_date, $final_date)) {
                    array_push($holidaysInWeek, $day->format('Y-m-d'));
                };
            }

            if (sizeof($holidaysInWeek) > 0) {
                // recupero el ulitmo septimo del empleado.
                $sepmo = DB::table('payroll_detail')
                    ->select('amount')
                    ->join('salary_rules', 'payroll_detail.salary_rule_id', '=', 'salary_rules.id')
                    ->join('payrolls', 'payroll_detail.payroll_id', '=', 'payrolls.id')
                    ->where([
                        ['salary_rules.code', 'sepmo'],
                        ['payrolls.employee_id', $employee]

                    ])
                    ->orderBy('payroll_detail.id', 'desc')
                    ->limit(1)
                    ->first();

                // si el empleado no tuvo septimo la semana anterior, 
                // se divide la produccion actual entre los dias trabajados
                if ($sepmo != null) {
                    $total_to_pay =  $sepmo->amount * sizeof($holidaysInWeek);
                } else {
                    foreach ($holidaysInWeek as $holiday) {
                        $ordinary_salary =  CalculateOrdinarySalary::ejecutar($employee, $start_date, $holiday);
                        $ordinary_salary = $ordinary_salary[0]->ordinary_salary;

                        $total_to_pay += $ordinary_salary / Carbon::create($holiday)->dayOfWeek;
                    }
                }
            }

            return round($total_to_pay, 2);
        }
    }
}
