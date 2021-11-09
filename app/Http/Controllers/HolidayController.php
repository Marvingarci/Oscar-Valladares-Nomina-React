<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Http\Requests\HolidayStoreRequest;
use App\Http\Requests\HolidayUpdateRequest;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Holidays/HolidayIndex', [
        'filters' => Request::all('search', 'trashed'),
         'feriados' => Holiday::filter(Request::only('search', 'trashed'))
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
        return Inertia::render('Holidays/CreateHoliday', [
            'holiday' => []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HolidayStoreRequest $request)
    {
        $inability = Holiday::create($request->validated());
        
        return redirect()->route('feriados.index')->with(['message' => 'Feriado agregado con éxito']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $holiday = Holiday::find($id);

        return Inertia::render('Holidays/CreateHoliday', [
            'holiday' => $holiday
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function update(HolidayUpdateRequest $request, $id)
    {
        $inability = Holiday::find($id);
        $inability->update($request->validated());

        return redirect()->route('feriados.index')->with(['message' => 'Fertiado editado con éxito']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        //
    }
}
