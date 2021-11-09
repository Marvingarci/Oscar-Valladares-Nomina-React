<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionStoreRequest;
use App\Http\Requests\PositionUpdateRequest;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class PositionController extends Controller
{

    public function index()
    {
        return Inertia::render('Positions/PositionsIndex', [
            'filters' => Request::all('search', 'trashed'),
            'departments' => Department::all(),
            'positions' => Position::filter(Request::only('search', 'trashed'))
                ->with('department')
                ->paginate(5)
                ->appends(Request::all()),
        ]);
    }

    public function create()
    {
        //
    }

    public function store(PositionStoreRequest $request)
    {
        $position = Position::create($request->validated());

        return back()->with(['message' => 'Puesto Agregado con Éxito']);
    }

    public function show(Position $position)
    {
        //
    }

    public function edit(Position $position)
    {
        //
    }

    public function update(PositionUpdateRequest $request, Position $position)
    {

        $position = Position::find($request->id);
        $position->update($request->validated());

        return back()->with(['message' => 'Puesto Editado con Éxito']);
    }

    public function destroy(Position $position)
    {
        //
    }
    public function getPositionForName(HttpRequest $request)
    {
        
        $positions = DB::table('positions')->where('name', 'like', '%' . $request->name . '%')->get();

        return response()->json($positions);
    }
}
