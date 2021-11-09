<?php

namespace App\Helpers\PayrollCalculations;

class CalculatePropor {

    static function get($ordinary_salary)
    {

        // La multiplicación del salario ordinario por 0.0909

        return round($ordinary_salary * 0.0909, 2);
    }

}