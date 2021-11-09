<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Companies;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;

use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Employees/EmployeesIndex',
            [
                'filters' => Request::all('search', 'trashed'),
                'employees' => Employee::filter(Request::only('search', 'trashed'))
                ->with('position')
                ->paginate(10)
                ->appends(Request::all()),
            ]);
    }

    public function create()
    {
        return Inertia::render('Employees/CreateEmployee', [
            'Nuevo' => 'si',
            'departments' => Department::all(),
            'positions' => Position::all(),
            'companies' => Companies::all(['id', 'name']),
        ]);
    }

    public function store(EmployeeStoreRequest $request)
    {        
        $employee = Employee::create($request->validated());

        return back()->with(['message' => 'Empleado editado con Éxito']);

    }

    public function show($id)
    {
        $emp = Employee::findOrFail($id);
        $emp->position;
        $emp->department;
        $emp->company;
        return Inertia::render('Employees/ShowEmployee',
            [
                'employee' => $emp,
                'departments' => Department::all(),
                'positions' => Position::all(),
                'companies'=> Companies::all()
            ]);
    }

    public function update(EmployeeUpdateRequest $request, Employee $employee)
    {
        $employee = Employee::find($request->id);
        $employee->update($request->validated());

        return back()->with(['message' => 'Empleado editado con Éxito']);
    }

    public function getEmployeesForName(HttpRequest $request)
    {
        
        $employees = DB::table('employees')->where('full_name', 'like', '%' . $request->full_name . '%')->get();

        return response()->json($employees);
    }
}
