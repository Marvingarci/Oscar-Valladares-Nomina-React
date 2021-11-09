<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Employee;
use App\Models\Vitola;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Http\Requests\TicketUpdateWeightRequest;
use App\Http\Requests\TicketUpdateDefectsRequest;
use Illuminate\Support\Facades\DB;


class QualityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ticket = DB::table('tickets')->where('id', Request::only('search', 'trashed'))->get();

        return Inertia::render('QualityControl/QualityIndex', [
            'ticket' => DB::table('tickets')->where('id', Request::only('search', 'trashed'))->get(),
            'filters' => Request::all('search', 'trashed'),
            'vitolas' => Vitola::all(),
            'supervisores' => Employee::with('position')->where('position_id', 'like', 3)->get()

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $ticket = Ticket::find($id);

        return Inertia::render('QualityControl/EditPesoTicket', [
            'ticket' =>  $ticket->load(['product', 'employees.position', 'user']),
            'supervisores' => Employee::with('position')->where('position_id', 'like', 3)->get()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketUpdateWeightRequest $request)
    {
        $ticket = Ticket::find($request->id);
        $ticket->update($request->validated());

        return redirect()->route('controlCalidad.index')->with(['message' => 'Ticket editado con Éxito']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::find($id);

        return Inertia::render('QualityControl/EditPesoTicket', [
            'ticket' =>  $ticket->load(['product', 'employees.position', 'user']),
            'vitolas' => Vitola::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ticket = Ticket::find($id);

        return Inertia::render('QualityControl/EditDefectsTicket', [
            'ticket' =>  $ticket->load(['product', 'employees.position', 'user']),
            'vitolas' => Vitola::all(),
            'supervisores' => Employee::with('position')->where('position_id', 'like', 3)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(TicketUpdateDefectsRequest $request, Ticket $ticket)
    {
        $ticket = Ticket::find($request->id);
        $ticket->update($request->validated());

        return redirect()->route('controlCalidad.index')->with(['message' => 'Ticket editado con Éxito']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
