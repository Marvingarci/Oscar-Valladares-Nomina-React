<?php

namespace App\Http\Controllers;

use App\Models\Vitola;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

use App\Http\Requests\VitolaStoreRequest;
use App\Http\Requests\VitolaUpdateRequest;

class VitolaController extends Controller
{

    public function index()
    {
        return Inertia::render('Vitolas/VitolaIndex', [
            'filters' => Request::all('search', 'trashed'),
            'vitolas' => Vitola::filter(Request::only('search', 'trashed'))
                ->paginate(5)
                ->appends(Request::all()),
        ]);
    }


    public function store(VitolaStoreRequest $request)
    {
        $employee = Vitola::create($request->validated());
        return back()->with(['message' => 'Vitola Guardada con Éxito']);
    }


    public function update(VitolaUpdateRequest $request, Vitola $id)
    {
        $vitola = Vitola::find($request->id);
        $vitola->update($request->validated());
        return back()->with(['message' => 'Vitola Editada con Éxito']);
    }
}
