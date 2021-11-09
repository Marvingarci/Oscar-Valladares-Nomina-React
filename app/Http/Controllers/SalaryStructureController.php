<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalaryStructureStoreRequest;
use App\Http\Requests\SalaryStructureUpdateRequest;
use App\Models\SalaryRule;
use App\Models\SalaryStructure;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use Inertia\Inertia;

class SalaryStructureController extends Controller
{

    public function index()
    {
        return Inertia::render('Nomina/Structures/SalaryStructuresIndex', [
            'filters' => Request::all('search', 'trashed'),
            'salary_structures' => SalaryStructure::filter(Request::only('search', 'trashed'))
                ->paginate(5)
                ->appends(Request::all()),
        ]);
    }


    public function create()
    {
        return Inertia::render('Nomina/Structures/CreateSalaryStructure', [
            'salary_structure' => [],
            'salary_rules' => SalaryRule::all('id', 'concept'),
        ]);
    }


    public function store(SalaryStructureStoreRequest $request)
    {
        $salary_estructure = SalaryStructure::create($request->validated());
        $salary_rules_id = collect($request->salary_rules)->map(function ($item) {
            return $item['id'];
        });

        $salary_estructure->salary_rules()->sync($salary_rules_id->all());

        return back()->with(['message' => 'Estructura Salarial Agregada con Éxito']);
    }


    public function show($id)
    {
        $salary_structure = SalaryStructure::find($id);
        $salary_structure->salary_rules;

        return Inertia::render('Nomina/Structures/ShowSalaryStructure', [
            'salary_structure' => $salary_structure,
        ]);
    }


    public function edit($id)
    {
        $salary_structure = SalaryStructure::find($id);
        $salary_structure->salary_rules;

        return Inertia::render('Nomina/Structures/CreateSalaryStructure', [
            'salary_structure' => $salary_structure,
            'salary_rules' => SalaryRule::all('id', 'concept'),
        ]);
    }


    public function update(SalaryStructureUpdateRequest $request)
    {
        $salary_estructure = SalaryStructure::find($request->id);
        $salary_estructure->update($request->validated());

        $salary_rules_id = collect($request->salary_rules)->map(function ($item) {
            return $item['id'];
        });

        $salary_estructure->salary_rules()->sync($salary_rules_id->all());

        return back()->with(['message' => 'Estructura Salarial Editada con Éxito']);
    }


    public function getStructuresForName(HttpRequest $request)
    {

        $salary_estructures = DB::table('salary_structures')->where('name', 'like', '%' . $request->name . '%')->get();

        return response()->json($salary_estructures);
    }


    public function getSalaryRules(HttpRequest $request)
    {

        $salary_rules = DB::table('salary_structure_detail')
            ->select('salary_rules.*')
            ->join('salary_rules', 'salary_rule_id', '=', 'id')
            ->where('salary_structure_id', $request->id)
            ->get();


        return response()->json($salary_rules);
    }
}
