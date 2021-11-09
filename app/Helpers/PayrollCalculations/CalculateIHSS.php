<?php

namespace App\Helpers\PayrollCalculations;

class CalculateIHSS
{

    static function get($total_deven)
    {

        // total devengado * 0.05

        // SI RESULTADO DEL CALCULO ES MAYOR A 122.41 ENTONCES SE DEJA 122.21 COMO RESULTADO
        // SINO SE PONE EL RESULTADO QUE SALGA.

        $ihss = $total_deven * 0.05;

        if ($ihss >= 122.41) {
            return 122.41;
        } else {
            return round($ihss, 2);
        }
    }
}
