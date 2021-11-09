<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Requests\DepartmentStoreRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;


class DepartmentController extends Controller
{
    public function index()
    {
        return Inertia::render('Departments/DepartmentsIndex',
        [
        'filters' => Request::all('search', 'trashed'),
        'departments' => Department::filter(Request::only('search', 'trashed'))
        ->paginate(5)
        ->appends(Request::all())
        ]);
    }

    public function store(DepartmentStoreRequest $request)
    {
        $depto = Department::create($request->validated());

        return back()->with(['message' => 'Departamento Agregada con Éxito']);
    }
    
    public function update(DepartmentUpdateRequest $request, Department $department)
    {
      
        $department = Department::find($request->id);
        $department->update($request->validated());

        return back()->with(['message' => 'Departamento Editada con Éxito']);

    }

    
    public function destroy(Department $departments)
    {
        //
    }
}
