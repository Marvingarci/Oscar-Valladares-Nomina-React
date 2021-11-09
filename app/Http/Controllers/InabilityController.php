<?php

namespace App\Http\Controllers;

use App\Models\Inability;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Http\Requests\InabilityStoreRequest;
use App\Http\Requests\InabilityUpdateRequest;
use Carbon\Carbon;

class InabilityController extends Controller
{

    public function index()
    {

        return Inertia::render('Inability/InabitilyIndex', [
            'filters' => Request::all('search', 'trashed'),
            'employees' => Employee::filter(Request::only('search', 'trashed'))
                ->with('inabilities')
                ->paginate(5)
                ->appends(Request::all()),
        ]);
    }


    public function create()
    {
        return Inertia::render('Inability/CreateInability', [
            'inability' => []
        ]);
    }


    public function store(InabilityStoreRequest $request)
    {

        $st_inb = new Carbon($request->start_date);
        $ed_inb = new Carbon($request->end_date);

        $periodInab = $st_inb->diffInDaysFiltered(function ($date) {
            return !$date->isSunday();
        }, ($ed_inb)) + 1;

        if ($periodInab > 6) {
            $i = 6 - $st_inb->dayOfWeek + 1;
            $next_monday = new Carbon($st_inb);
            $new_period = 6 - $st_inb->dayOfWeek + 1;

            while ($i <= $periodInab) {

                if ($i <= 6) {
                    Inability::create([
                        'employee_id' => $request->employee_id,
                        "type" => "ec",
                        'caption' => $request->caption,
                        'start_date' => $next_monday->format('Y-m-d'),
                        'end_date' => $next_monday->addDays($new_period - 1)
                    ]);
                } else {
                    Inability::create([
                        'employee_id' => $request->employee_id,
                        "type" => "ecs",
                        'caption' => $request->caption,
                        'start_date' => $next_monday->format('Y-m-d'),
                        'end_date' => $next_monday->addDays($new_period - 1)
                    ]);
                }

                if (($i + 6) > $periodInab && $i != $periodInab) {
                    $new_period = ($periodInab - $i);
                    $i += ($periodInab - $i);
                } else {
                    $i += 6;
                    $new_period = 6;
                }

                $next_monday = new Carbon($next_monday->next("MONDAY")->format('Y-m-d'));
            }
        } else {
            Inability::create($request->validated());
        }

        return redirect()->route('incapacidades.index')->with(['message' => 'Incapacidad Agregada con Éxito']);
    }


    public function show($id)
    {
        return Inertia::render(
            'Inability/ShowInabilityByEmployee',
            [
                'employee' => Employee::where('id', $id)
                    ->with(['inabilities', 'department', 'position'])->first(),
                'departments' => Department::all(),
                'positions' => Position::all(),
            ]
        );
    }


    public function edit($inability)
    {
        $inability = Inability::find($inability);

        return Inertia::render('Inability/CreateInability', [
            'inability' => $inability->load('employee:full_name,id'),
        ]);
    }


    public function update(InabilityUpdateRequest $request, $id)
    {
        $inability = Inability::find($id);
        $inability->update($request->validated());

        return redirect()->route('incapacidades.index')->with(['message' => 'Incapacidad editada con éxito']);
    }


    public function destroy(Inability $inability)
    {
        //
    }
}
