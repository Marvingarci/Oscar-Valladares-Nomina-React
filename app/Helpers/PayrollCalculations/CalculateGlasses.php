<?php

namespace App\Helpers\PayrollCalculations;

use Illuminate\Support\Facades\DB;

class CalculateGlasses
{

    static function get($employee)
    {
        $glasses = DB::table('glasses')
            ->selectRaw('glasses.id, glasses.cuota, glasses.pend')
            ->where([
                ['employee_id', $employee],
                ['status', 'active']
            ])
            ->get();

        $total_to_pay = 0;

        foreach ($glasses as $glass) {
            if($glass->cuota > $glass->pend){
                $total_to_pay += $glass->pend;
            }else{
                $total_to_pay += $glass->cuota;
            }
        }
        

        return [
                'total_to_pay' => round($total_to_pay, 2),
                'glasses_to_pay' => $glasses
            ];

    }
}
