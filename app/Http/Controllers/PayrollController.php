<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Glasses;
use App\Models\Loan;
use App\Models\Provision;
use App\Models\OtherDeduction;
use App\Helpers\PayrollCalculations\CalculateGlasses;
use App\Helpers\PayrollCalculations\CalculateHolidays;
use App\Helpers\PayrollCalculations\CalculateIHSS;
use App\Helpers\PayrollCalculations\CalculateInability;
use App\Helpers\PayrollCalculations\CalculateLoans;
use App\Helpers\PayrollCalculations\CalculateOtherDeduc;
use App\Helpers\PayrollCalculations\CalculatePropor;
use App\Helpers\PayrollCalculations\CalculateProv;
use App\Helpers\PayrollCalculations\CalculateSepmo;
use App\Helpers\PayrollCalculations\CalculateOrdinarySalary;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\PayrollStoreRequest;
use App\Http\Requests\PayrollByPositionStoreRequest;
use App\Http\Requests\PayrollUpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Inertia\Inertia;



class PayrollController extends Controller
{
    // protected $ordinary_salary = 0; //salario bruto

    public function index()
    {
        return Inertia::render('Nomina/Payrolls/PayrollIndex', [
            'filters' => Request::all('search', 'trashed'),
            'payrolls' => Payroll::filter(Request::only('search', 'trashed'))
                ->with('employee')
                ->paginate(10)
                ->appends(Request::all())
        ]);
    }


    public function store(PayrollStoreRequest $request)
    {

        $employeeHasPayroll = DB::table('payrolls')
            ->selectRaw('count(id) payrolls')
            ->whereRaw(
                '
                employee_id = ?
                AND DATE(start_date) BETWEEN ? AND ?
                AND DATE(final_date) BETWEEN ? AND ?
                ',
                [
                    $request->employee_id,
                    $request->start_date,
                    $request->final_date,
                    $request->start_date,
                    $request->final_date
                ]
            )
            ->first();

        if ($employeeHasPayroll->payrolls == 0) {
            $payroll = Payroll::create($request->validated());

            foreach ($request->salary_rules as $rule) {
                $payroll->salary_rules()->attach($rule['id'], ['amount' => $rule['amount']]);
            }

            if ($request->status === "pagado") {
                $this->MakeDeductions($request->loans_to_pay, $request->glasses_to_pay, $request->provisions_to_pay, $request->others_to_pay);
            }


            $weekOfYear = new Carbon($payroll->start_date);

            return back()->with([
                'aditionalData' => [
                    'payroll' => $payroll->load([
                        'salary_rules:id,concept,type,code',
                        'employee.department',
                        'employee.position',
                        'employee.company'
                    ]),
                    'weekOfYear' => $weekOfYear->weekOfYear
                ],
                'message' => 'Nómina Guardada con Éxito'
            ]);
        } else {
            return back()->with([
                'message' => 'El empleado ya cuenta con una nómina para esta semana'
            ]);
        }
    }

    public function MakeDeductions($loans, $glasses, $prov, $others)
    {
        foreach ($glasses as $glass_to_pay) {
            $updateGlass = Glasses::find($glass_to_pay['id']);

            if ($updateGlass->pend < $updateGlass->cuota) {
                $glass_to_pay['cuota'] = $updateGlass->pend;
            }

            $updateGlass->update([
                'pend' => $updateGlass->pend - $glass_to_pay['cuota']
            ]);

            if ($updateGlass->pend <= 0) {
                $updateGlass->update([
                    'status' => 'inactive'
                ]);
            }
        }
        foreach ($loans as $glass_to_pay) {
            $updateGlass = Loan::find($glass_to_pay['id']);

            if ($updateGlass->pend < $updateGlass->cuota) {
                $glass_to_pay['cuota'] = $updateGlass->pend;
            }

            $updateGlass->update([
                'pend' => $updateGlass->pend - $glass_to_pay['cuota']
            ]);

            if ($updateGlass->pend <= 0) {
                $updateGlass->update([
                    'status' => 'inactive'
                ]);
            }
        }
        foreach ($prov as $glass_to_pay) {
            $updateGlass = Provision::find($glass_to_pay['id']);

            if ($updateGlass->pend < $updateGlass->cuota) {
                $glass_to_pay['cuota'] = $updateGlass->pend;
            }

            $updateGlass->update([
                'pend' => $updateGlass->pend - $glass_to_pay['cuota']
            ]);

            if ($updateGlass->pend <= 0) {
                $updateGlass->update([
                    'status' => 'inactive'
                ]);
            }
        }
        foreach ($others as $glass_to_pay) {
            $updateGlass = OtherDeduction::find($glass_to_pay['id']);

            if ($updateGlass->pend < $updateGlass->cuota) {
                $glass_to_pay['cuota'] = $updateGlass->pend;
            }

            $updateGlass->update([
                'pend' => $updateGlass->pend - $glass_to_pay['cuota']
            ]);

            if ($updateGlass->pend <= 0) {
                $updateGlass->update([
                    'status' => 'inactive'
                ]);
            }
        }
    }

    public function updateCreated(HttpRequest $request)
    {
        $payrollsByPosition = $request->payroll;

        foreach ($payrollsByPosition as $payroll) {
            $toPay = Payroll::find($payroll['id']);
            $toPay->status = 'pagado';
            $toPay->save();
        }


        return back()->with([
            'message' => 'Se ha pagado a lote de empleados exitosamente'
        ]);
    }


    public function calPayroll(PayrollStoreRequest $request)
    {
        $employee = $request->employee_id;
        $start_date = $request->start_date;
        $final_date = $request->final_date;

        $salary = $this->calculateSalary($employee, $start_date, $final_date, $request);

        return response()->json([
            'salary_rules' => $salary['salary_rules'], // este es el detalle de la nomina
            'ordinary_salary' => round($salary['ordinary_salary'], 2),
            'net_salary' => round($salary['net_salary'], 2),
            'total_deduc' => round($salary['total_deduc'], 2),
            'total_inc' => round($salary['total_inc'], 2),
            'glasses_to_pay' => $salary['glasses_to_pay'], //
            'loans_to_pay' => $salary['loans_to_pay'], //
            'others_to_pay' => $salary['others_to_pay'], //
            'provisions_to_pay' => $salary['provisions_to_pay'], //
        ]);
    }

    public function callPayrollByPosition(PayrollByPositionStoreRequest $request)
    {
        $employeesToPay = DB::table('employees')
            ->whereRaw('
            position_id = ?
            AND employees.id NOT IN (
            SELECT employee_id FROM payrolls
            WHERE DATE(start_date) BETWEEN ? AND ?
            AND DATE(final_date) BETWEEN ? AND ?
            )
            ', [
                $request->position_id,
                $request->start_date,
                $request->final_date,
                $request->start_date,
                $request->final_date,
            ])
            ->get();

        $start_date = $request->start_date;
        $final_date = $request->final_date;
        $payrolles = [];

        if (sizeof($employeesToPay) != 0) {
            foreach ($employeesToPay as $employeeByP) {
                $employee = $employeeByP->id;

                $salary = $this->calculateSalary($employee, $start_date, $final_date, $request);

                $payroll = Payroll::create([
                    'employee_id' => $employeeByP->id,
                    'salary_structure_id' => $request->salary_structure_id,
                    'start_date' => $request->start_date,
                    'final_date' => $request->final_date,
                    'ordinary_salary' => round($salary['ordinary_salary'], 2),
                    'total_deduc' =>  round($salary['total_deduc'], 2),
                    'total_inc' => round($salary['total_inc'], 2),
                    'total_to_pay' => round($salary['net_salary'], 2),
                    'days_worked' => $request->days_worked,
                    'status' => 'borrador'
                ]);

                foreach ($salary['salary_rules'] as $rule) {
                    $payroll->salary_rules()->attach($rule['id'], ['amount' => $rule['amount']]);
                }

                array_push($payrolles, $payroll->load([
                    'salary_rules:id,concept,type,code',
                    'employee:full_name,id',
                    'salary_structure:name,id'
                ]));
            }

            return response()->json(
                $payrolles
            );
        } else {
            $employeesByPosition = DB::table('employees')
                ->select('id')
                ->where('position_id', $request->position_id)
                ->get();

            foreach ($employeesByPosition as $employee) {
                $payroll =  Payroll::where([
                    ['employee_id', $employee->id],
                    ['status', 'borrador'],
                ])
                    ->whereBetween(
                        'start_date',
                        [$request->start_date, $request->final_date]
                    )
                    ->whereBetween(
                        'final_date',
                        [$request->start_date, $request->final_date]
                    )
                    ->first();

                if (!is_null($payroll)) {
                    array_push($payrolles, $payroll->load([
                        'salary_rules:id,concept,type,code',
                        'employee:full_name,id',
                        'salary_structure:name,id'
                    ]));
                }
            }

            return response()->json(
                $payrolles
            );
        }
    }


    public function calculateSalary($employee, $start_date, $final_date, $request)
    {

        $salary_rules = [];
        $glasses_to_pay = [];
        $others_to_pay = [];
        $loans_to_pay = [];
        $provisions_to_pay = [];
        $total_deduc = 0;
        $total_inc = 0;

        $ordinary_salary =  CalculateOrdinarySalary::ejecutar($employee, $start_date, $final_date);
        $ordinary_salary = $ordinary_salary[0]->ordinary_salary;

        foreach ($request->salary_rules as $key => $value) {
            if ($value['code'] == "propor") {
                $value['amount'] = CalculatePropor::get($ordinary_salary);
                array_push($salary_rules, $value);
                $total_inc += CalculatePropor::get($ordinary_salary);
            }
            if ($value['code'] == "sepmo") {
                $value['amount'] =  CalculateSepmo::get($employee, $start_date, $final_date, $request->days_worked, $ordinary_salary);
                array_push($salary_rules, $value);

                $total_inc +=  CalculateSepmo::get($employee, $start_date, $final_date, $request->days_worked, $ordinary_salary);
            }
            if ($value['code'] == "fer") {
                $value['amount'] = CalculateHolidays::get($employee, $start_date, $final_date);
                array_push($salary_rules, $value);

                $total_inc += CalculateHolidays::get($employee, $start_date, $final_date);
            }
            if ($value['code'] == "ajus") {
                $value['amount'] = $request->ajust;
                array_push($salary_rules, $value);

                $total_inc += $request->ajust;
            }
            if ($value['code'] == "presfab") {
                $data_pres = CalculateLoans::get($employee); //
                $value['amount'] = $data_pres['total_to_pay']; //
                $loans_to_pay = $data_pres['loans_to_pay']; //
                array_push($salary_rules, $value);

                $total_deduc += $data_pres['total_to_pay'];
            }
            if ($value['code'] == "provi") {
                $data_provi = CalculateProv::get($employee); //
                $value['amount'] = $data_provi['total_to_pay']; //
                $provisions_to_pay = $data_provi['provisions_to_pay']; //
                array_push($salary_rules, $value);

                $total_deduc += $data_provi['total_to_pay'];
            }
            if ($value['code'] == "lent") {
                $data_glasses = CalculateGlasses::get($employee); //
                $value['amount'] = $data_glasses['total_to_pay']; //
                $glasses_to_pay = $data_glasses['glasses_to_pay']; //
                array_push($salary_rules, $value);

                $total_deduc += $data_glasses['total_to_pay']; //
            }
            if ($value['code'] == "odeduc") {
                $data_others = CalculateOtherDeduc::get($employee); //
                $value['amount'] = $data_others['total_to_pay']; //
                $others_to_pay = $data_others['others_to_pay']; //
                array_push($salary_rules, $value);

                $total_deduc += $data_others['total_to_pay']; //

            }
            if ($value['code'] == "bons") {
                $value['amount'] =  $request->bonds;
                array_push($salary_rules, $value);

                $total_inc += $request->bonds;
            }
            if ($value['code'] == "incaps") {
                $value['amount'] = CalculateInability::get($employee, $start_date, $final_date, $request->days_worked);
                array_push($salary_rules, $value);

                $total_inc += CalculateInability::get($employee, $start_date, $final_date, $request->days_worked);
            }
        }

        $ihss = array_filter($request->salary_rules, function ($rule) {
            return $rule['code'] == 'ihss';
        });

        foreach ($ihss as $item) {
            $item['amount'] = CalculateIHSS::get($ordinary_salary + $total_inc);
            array_push($salary_rules, $item);
            $total_deduc += CalculateIHSS::get($ordinary_salary + $total_inc);
        }

        $net_salary = round($ordinary_salary + $total_inc - $total_deduc, 2);

        return [
            'ordinary_salary' => $ordinary_salary,
            'net_salary' => $net_salary,
            'salary_rules' => $salary_rules,
            'total_deduc' => $total_deduc,
            'total_inc'  => $total_inc,
            'glasses_to_pay' => $glasses_to_pay,
            'loans_to_pay' => $loans_to_pay,
            'provisions_to_pay' => $provisions_to_pay,
            'others_to_pay' => $others_to_pay,

        ];
    }


    public function show($id)
    {
        $payroll = Payroll::find($id);

        return Inertia::render('Nomina/Payrolls/ShowPayroll', [
            'payroll' => $payroll->load('salary_rules:id,concept,type'),
        ]);
    }


    public function edit($id)
    {
        $payroll = Payroll::find($id);

        return Inertia::render('Nomina/Payrolls/PayrollEmployee', [
            'payroll' => $payroll->load([
                'salary_rules:id,concept,type,code',
                'employee:full_name,id',
                'salary_structure:name,id'
            ]),
        ]);
    }

    public function editOne(HttpRequest $id)
    {

        $payroll = Payroll::find($id->id);

        return response()->json([
            'payroll' => $payroll->load([
                'salary_rules:id,concept,type,code',
                'employee:full_name,id',
                'salary_structure:name,id'
            ])
        ]);
    }


    public function payrollReport()
    {
        return Inertia::render('Nomina/Payrolls/PayrollReport');
    }

    public function getAllPayroll(HttpRequest $request)
    {

        return response()->json([
            Payroll::whereBetween('start_date', [$request->start_date, $request->final_date])
                ->orWhereBetween('final_date', [$request->start_date, $request->final_date])
                ->with(['salary_rules', 'employee'])
                ->get(),
            'message' => 'Nomina generada con éxito'
        ]);
    }


    public function update(PayrollUpdateRequest $request, $id)
    {
        $payroll = Payroll::find($id);
        $payroll->update($request->validated());

        foreach ($request->salary_rules as $rule) {
            $payroll->salary_rules()->updateExistingPivot($rule['id'], ['amount' => $rule['amount']]);
        };

        if ($request->status === "pagado") {
            $this->MakeDeductions($request->loans_to_pay, $request->glasses_to_pay, $request->provisions_to_pay, $request->others_to_pay);
        }

        $weekOfYear = new Carbon($payroll->start_date);

        return back()->with([
            'aditionalData' => [
                'payroll' => $payroll->load([
                    'salary_rules:id,concept,type,code',
                    'employee.department',
                    'employee.position',
                    'employee.company'
                ]),
                'weekOfYear' => $weekOfYear->weekOfYear
            ],
            'message' => 'Nómina Editada con Éxito'
        ]);
    }


    public function payrollEmployee()
    {

        return Inertia::render('Nomina/Payrolls/PayrollEmployee', [
            'payroll' => []
        ]);
    }

    public function payrollManyEmployees()
    {
        return Inertia::render('Nomina/Payrolls/PayrollManyEmployees');
    }


    public function payrollToPrint(HttpRequest $request)
    {


        $payroll = Payroll::find($request->id);

        if (is_array($request->id)) {
            $weekOfYear = new Carbon($payroll[0]->start_date);
        } else {
            $weekOfYear = new Carbon($payroll->start_date);
        }

        return response()->json([
            'payroll' => $payroll->load([
                'salary_rules:id,concept,type,code',
                'employee.department',
                'employee.position',
                'employee.company'
            ]),
            'weekOfYear' => $weekOfYear->weekOfYear
        ]);
    }
}
