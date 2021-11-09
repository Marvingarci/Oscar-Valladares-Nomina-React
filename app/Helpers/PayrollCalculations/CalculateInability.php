<?php

namespace App\Helpers\PayrollCalculations;

use App\Models\Inability;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalculateInability
{
    static function get($employee, $start_date, $final_date, $days_worked)
    {
        $start_date = new Carbon($start_date);
        $final_date = new Carbon($final_date);

        $inabilities = DB::table('inabilities')
            ->whereRaw('
            employee_id = ? and Date(start_date) between ? and ?
        ', [$employee, $start_date, $final_date])
            ->get();


        $total_inabilities = 0;
        // $inabilities_to_pay = [];

        if ($inabilities != null) {

            foreach ($inabilities as $inability) {

                $st_inh = new Carbon($inability->start_date);
                $ed_inh = new Carbon($inability->end_date);
                $fn_wk = new Carbon($final_date);

                $periodInab = $st_inh->diffInDaysFiltered(function ($date) {
                    return !$date->isSunday();
                }, ($ed_inh)) + 1;

                if ($inability->type == "ec") {
                    if ($periodInab > 3) {
                        $threeDays = 3 * 274.21;
                        $otherDays = ($periodInab - 3) * (274.21 * 0.34);
                        $total_inabilities += $threeDays + $otherDays;
                    } else {
                        $total_inabilities += $periodInab * 274.21;
                    }
                } else {
                    $total_inabilities += $periodInab * (274.21 * 0.34);
                }

                ////////////////////////////////////////////////////////////////////////////////////////

                // $st_inh = new Carbon($inability->start_date);
                // $ed_inh = new Carbon($inability->end_date);
                // $fn_wk = new Carbon($final_date);

                // $periodInab = $st_inh->diffInDaysFiltered(function ($date) {
                //     return !$date->isSunday();
                // }, ($ed_inh)) + 1;

                // // if ($periodInab > $days_worked) {
                // if ($periodInab > $fn_wk->dayOfWeek) {

                //     $new_period_inh = $st_inh->diffInDaysFiltered(function ($date) {
                //         return !$date->isSunday();
                //     }, ($fn_wk)) + 1;

                //     $threeDays = 3 * 274.21;
                //     $otherDays = ($new_period_inh - 3) * (274.21 * 0.34);
                //     $total_inabilities += $threeDays + $otherDays;

                //     $updateInhability = Inability::find($inability->id);
                //     $updateInhability->end_date = $final_date;
                //     $updateInhability->save();

                //     $remainingDays = Inability::create([
                //         'employee_id' => $inability->employee_id,
                //         'caption' => $inability->caption,
                //         'start_date' => $st_inh->addDays($new_period_inh + 1)->format('Y-m-d'),
                //         'end_date' => $ed_inh
                //     ]);
                // } else {

                //     if ($periodInab > 3) {

                //         if ($ed_inh->dayOfWeek > $fn_wk->dayOfWeek) {

                //             $new_period_inh = $st_inh->diffInDaysFiltered(function ($date) {
                //                 return !$date->isSunday();
                //             }, ($fn_wk)) + 1;

                //             if ($new_period_inh > 3) {
                //                 $threeDays = 3 * 274.21;
                //                 $otherDays = ($new_period_inh - 3) * (274.21 * 0.34);
                //                 $total_inabilities += $threeDays + $otherDays;
                //             } else {
                //                 $total_inabilities += $new_period_inh * 274.21;
                //             }
                //         } else {

                //             $threeDays = 3 * 274.21;
                //             $otherDays = ($periodInab - 3) * (274.21 * 0.34);
                //             $total_inabilities += $threeDays + $otherDays;
                //         }
                //     } else {

                //         $total_inabilities += $periodInab * 274.21;
                //     }
                // }

                ////////////////////////////////////////////////////////////////////////////////////////////////

                // $st_inb = new Carbon($inability->start_date);
                // $ed_inb = new Carbon($inability->end_date);
                // $st_wk = new Carbon($start_date);
                // $fn_wk = new Carbon($final_date);

                // if ($inability->days_paid >= 3) {

                //     if ($ed_inb  > $fn_wk) {

                //         $new_period_inb = $st_wk->diffInDaysFiltered(function ($date) {
                //             return !$date->isSunday();
                //         }, ($fn_wk)) + 1;

                //         $total_inabilities +=  $new_period_inb * (274.21 * 0.34);

                //         array_push($inabilities_to_pay, [
                //             'id' => $inability->id,
                //             'days_paid' => $inability->days_paid + $new_period_inb
                //         ]);
                //     } else {

                //         $new_period_inb = $st_wk->diffInDaysFiltered(function ($date) {
                //             return !$date->isSunday();
                //         }, ($ed_inb)) + 1;

                //         $total_inabilities +=  $new_period_inb * (274.21 * 0.34);

                //         array_push($inabilities_to_pay, [
                //             'id' => $inability->id,
                //             'days_paid' => $inability->days_paid + $new_period_inb
                //         ]);
                //     }
                // } else {

                //     $new_period_inb = $st_wk->diffInDaysFiltered(function ($date) {
                //         return !$date->isSunday();
                //     }, ($fn_wk)) + 1;

                //     if ($ed_inb  > $fn_wk) {

                //         if ($new_period_inb > 3) {
                //             $threeDays = (3 - $inability->days_paid) * 274.21;
                //             $otherDays = ($new_period_inb - (3 - $inability->days_paid)) * (274.21 * 0.34);
                //             $total_inabilities += $threeDays + $otherDays;
                //         } else {
                //             $total_inabilities += $new_period_inb * 274.21;
                //         }

                //         array_push($inabilities_to_pay, [
                //             'id' => $inability->id,
                //             'days_paid' => $inability->days_paid + $new_period_inb
                //         ]);
                //     } else {

                //         if ($new_period_inb > 3) {
                //             $threeDays = 3 * 274.21;
                //             $otherDays = ($new_period_inb - 3) * (274.21 * 0.34);
                //             $total_inabilities += $threeDays + $otherDays;
                //         } else {
                //             $total_inabilities += $new_period_inb * 274.21;
                //         }

                //         array_push($inabilities_to_pay, [
                //             'id' => $inability->id,
                //             'days_paid' => $inability->days_paid + $new_period_inb
                //         ]);
                //     }
                // }
            }
        }

        // return [
        //     'total_to_pay' => round($total_inabilities, 2),
        //     'inabilities_to_pay' => $inabilities_to_pay
        // ];

        return round($total_inabilities, 2);
    }
}
