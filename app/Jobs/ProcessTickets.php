<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;

class ProcessTickets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tickets;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tickets)
    {
        $this->tickets = $tickets;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->tickets->map(function ($ticket) {
            for ($i = 0; $i < $ticket['amount_of_tickets']; $i++) {
                $newTicket = Ticket::create([
                    'user_id' => $ticket['user_id'],
                    'product_id' => $ticket['product_id'],
                    'amount_of_cigars' => $ticket['amount_of_cigars'],
                    'status' => $ticket['status'],
                ]);
                $newTicket->employees()->attach([$ticket['rolero_id'], $ticket['bonchero_id']]);
            }
        });
    }
}
