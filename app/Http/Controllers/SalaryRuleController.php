<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalaryRuleStoreRequest;
use App\Http\Requests\SalaryRuleUpdateRequest;
use App\Models\SalaryRule;
use Illuminate\Support\Facades\Request;

use Inertia\Inertia;

class SalaryRuleController extends Controller
{

    public function index()
    {
        return Inertia::render('Nomina/Rules/SalaryRulesIndex', [
            'filters' => Request::all('search', 'trashed'),
            'salary_rules' => SalaryRule::filter(Request::only('search', 'trashed'))
                ->paginate(5)
                ->appends(Request::all()),
        ]);
    }


    public function store(SalaryRuleStoreRequest $request)
    {
        $salary_rule = SalaryRule::create($request->validated());

        return back()->with(['message' => 'Regla Salarial Creada con Éxito']);
    }


    public function update(SalaryRuleUpdateRequest $request, $id)
    {
        $salary_rule = SalaryRule::find($id);
        $salary_rule->update($request->validated());

        return back()->with(['message' => 'Regla Salarial Editada con Éxito']);
    }

}
