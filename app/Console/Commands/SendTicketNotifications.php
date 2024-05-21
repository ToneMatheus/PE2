<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use App\Notifications\TicketOpenNotification;

class SendTicketNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendTicketNotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for tickets open for more than 10 minutes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tickets = Ticket::where('created_at', '<=', now()->subMinutes(10))->get();
        $roleId = 2; // ID of the role to notify EMPLOYEE

        foreach ($tickets as $ticket) {
            $ticket->notify(new TicketOpenNotification($ticket, $roleId));
        }

        return Command::SUCCESS;
    }
}
