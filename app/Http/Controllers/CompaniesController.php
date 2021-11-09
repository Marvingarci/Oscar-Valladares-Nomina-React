<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Models\Companies;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request as Request2;


class CompaniesController extends Controller
{

    public function index()
    {
        return Inertia::render('Companies/CompaniesIndex',
            [
            'filters' => Request2::all('search', 'trashed'),
            'companies' => Companies::filter(Request2::only('search', 'trashed'))
            ->paginate(5)
            ->appends(Request2::all())
            ]);

    }

  
    

    public function store(CompanyStoreRequest $request)
    {
        $company = Companies::create($request->validated());

        return back()->with(['message' => 'Compañía Agregada con Éxito']);
    }

    public function show(Companies $companies)
    {

    }

    public function update(CompanyUpdateRequest $request)
    {
        $company = Companies::find($request->id);
        $company->update($request->validated());

        return back()->with(['message' => 'Compañía Editada con Éxito']);

    }

    public function destroy(Companies $companies)
    {
        $companies->delete();

    }
}
