<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Employee;
use App\Models\Ticket;
use App\Models\GenerateTickets;
use App\Http\Requests\TicketStoreRequest;
use App\Http\Requests\TicketUpdateRequest;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class TicketController extends Controller
{

    protected $generate_ticket_id;

    public function index()
    {
        return Inertia::render('Tickets/TicketIndex', [
            'filters' => Request::all('search', 'trashed'),
            'tickets' => Ticket::filter(Request::only('search', 'trashed'))
                ->with(['product', 'employees:full_name'])
                ->paginate(10)
                ->appends(Request::all()),
        ]);
    }

    public function create()
    {
        return Inertia::render('Tickets/CreateTicket', [
            'products' => Product::all(),
            'employees' => Employee::with('position')->orderBy('full_name')->get(),
        ]);
    }


    function ean13_checksum($message)
    {
        $checksum = 0;
        foreach (str_split(strrev($message)) as $pos => $val) {
            $checksum += $val * (3 - 2 * ($pos % 2));
        }
        return ((10 - ($checksum % 10)) % 10);
    }

    public function saveTicket($tickets, $generate_ticket_id)
    {
        $this->generate_ticket_id = $generate_ticket_id;

        $tickets->map(function ($ticket) {


            for ($i = 0; $i < $ticket['amount_of_tickets']; $i++) {

                $newTicket = Ticket::create([
                    'user_id' => $ticket['user_id'],
                    'product_id' => $ticket['product_id'],
                    'amount_of_cigars' => $ticket['amount_of_cigars'],
                    'status' => $ticket['status'],
                    'generate_tickets_id' => $this->generate_ticket_id,
                ]);

                $newTicket->employees()->attach([$ticket['rolero_id'], $ticket['bonchero_id']]);
            }
        });
    }

    public function store(HttpRequest $request)
    {

        $generate_ticket = GenerateTickets::create([
            'user_id' => auth()->user()->id
        ]);

        $tickets = collect($request);
        $this->saveTicket($tickets, $generate_ticket->id);

        $ticketsToPrint = Ticket::where('generate_tickets_id', $generate_ticket->id)->get();

        return  back()->with([
            'aditionalData' => $ticketsToPrint->load(['product.vitola', 'employees.position', 'user']),
            'message' => 'Tickets Generados con Éxito',

        ]);
    }

    public function show($id)
    {
        $ticket = Ticket::find($id);

        return Inertia::render('Tickets/ShowTicket', [
            'ticket' =>  $ticket->load(['product.vitola', 'employees.position', 'user']),
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
        return Inertia::render('Tickets/EditTicket', [
            'ticket' =>  $ticket->load(['product', 'employees.position', 'user']),
            'products' => Product::all(),
            'employees' => Employee::with('position')->orderBy('full_name')->get(),
        ]);
    }

    public function update(TicketUpdateRequest $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->validated());

        return redirect()->route('tickets.show', $ticket->id)->with(['message' => "Ticket Editado con Éxito"]);
    }

    public function destroy(Ticket $ticket)
    {
        //
    }

    public function validateForm(TicketStoreRequest $request)
    {
        $request->validated();

        return back();
    }

    public function cancelTicket($id)
    {
        ticket::find($id)->update([
            'status' => 'cancelado'
        ]);

        return back()->with(['message' => 'Ticket cancelado con Éxito']);
    }
}
