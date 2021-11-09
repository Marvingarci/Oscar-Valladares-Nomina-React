<?php

namespace App\Helpers\PayrollCalculations;

use Illuminate\Support\Facades\DB;

class CalculateLoans
{
    static function get($employee)
    {
        $loans = DB::table('loans')
            ->selectRaw('loans.cuota, loans.id, loans.pend')
            ->where([
                ['employee_id', $employee],
                ['status', 'active']
            ])
            ->get();

        $total_to_pay = 0;

        foreach ($loans as $glass) {
            if($glass->cuota > $glass->pend){
                $total_to_pay += $glass->pend;
            }else{
                $total_to_pay += $glass->cuota;
            }
        }
        

        return [
                'total_to_pay' => round($total_to_pay, 2),
                'loans_to_pay' => $loans
            ];
    }
}
