<?php

namespace App\Helpers\PayrollCalculations;

use Illuminate\Support\Facades\DB;

class CalculateOtherDeduc
{

    static function get($employee)
    {
        $other_deducs = DB::table('other_deductions')
            ->selectRaw('other_deductions.cuota, other_deductions.id, other_deductions.pend')
            ->where([
                ['employee_id', $employee],
                ['status', 'active']
            ])
            ->get();

        $total_to_pay = 0;

        foreach ($other_deducs as $glass) {
            if($glass->cuota > $glass->pend){
                $total_to_pay += $glass->pend;
            }else{
                $total_to_pay += $glass->cuota;
            }
        }
        

        return [
                'total_to_pay' => round($total_to_pay, 2),
                'others_to_pay' => $other_deducs
            ];
    }
}
