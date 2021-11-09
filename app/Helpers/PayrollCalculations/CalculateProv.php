<?php

namespace App\Helpers\PayrollCalculations;

use Illuminate\Support\Facades\DB;

class CalculateProv
{

    static function get($employee)
    {
        $provisions = DB::table('provisions')
            ->selectRaw('provisions.cuota, provisions.pend, provisions.id')
            ->where([
                ['employee_id', $employee],
                ['status', 'active']
            ])
            ->get();

        $total_to_pay = 0;

        foreach ($provisions as $glass) {
            if($glass->cuota > $glass->pend){
                $total_to_pay += $glass->pend;
            }else{
                $total_to_pay += $glass->cuota;
            }
        }
        

        return [
                'total_to_pay' => round($total_to_pay, 2),
                'provisions_to_pay' => $provisions
            ];

        // foreach ($provisions as $provision) {
        //     $total_to_pay += $provision->cuota;
        // }

        // return round($total_to_pay, 2);
    }
}
