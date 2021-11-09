<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Nomina/Deductions/DeductionsIndex',
        [
         'filters' => Request::all('search', 'trashed'),
         'employees' => Employee::filter(Request::only('search', 'trashed'))
         ->with(['loans', 'provisions', 'otherDeductions', 'glasses'])
         ->paginate(5)
         ->appends(Request::all())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Nomina/Deductions/DeductionsCreate', [
            // 'Nuevo' => 'si',
             'employees' => Employee::all(),
            // 'positions' => Position::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        return Inertia::render('Nomina/Deductions/ShowDeductionByEmployee',
            [
                'employee' => Employee::where('id', $id)
                ->with(['loans', 'provisions', 'otherDeductions', 'glasses', 'department', 'position'])->first(),
                'departments' => Department::all(),
                'positions' => Position::all(),
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function edit($deduction)
    {
        $deduction = Deduction::find($deduction);

        return Inertia::render('Nomina/Deductions/EditDeduction', [
             'deduction' => $deduction,
             'employees' => Employee::all(),
       ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deduction $deduction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deduction $deduction)
    {
        //
    }
}
