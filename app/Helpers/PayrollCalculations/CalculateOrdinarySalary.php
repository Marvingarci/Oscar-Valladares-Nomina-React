<?php

namespace App\Helpers\PayrollCalculations;

use Illuminate\Support\Facades\DB;

class CalculateOrdinarySalary
{

    public static function ejecutar($employee, $start_date, $final_date)
    {
        return DB::select('SELECT ordinary_salary(?, ?, ?) as ordinary_salary', [$employee, $start_date, $final_date]);
    }
}
