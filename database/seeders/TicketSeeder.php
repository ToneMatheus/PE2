<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 50; $i++) {
            $status = rand(0, 1) ? 1 : 0; // 1 for 'closed', 0 for 'open'
            $ticketData = [
                'role' => 'user',
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'issue' => 'Issue ' . $i,
                'description' => 'Description ' . $i,
                'active' => 1,
                'user_id' => rand(1, 9),
                'status' => $status,
                'employee_id' => null, 
                'line' => rand(1, 3), 
                'urgency' => rand(0, 2), 
                'resolution' => 'Resolution ' . $i,
            ];

            if ($status === 1) { // if status is 'closed'
                $ticketData['close_date'] = now()->addDays(rand(1, 10));
                $ticketData['is_solved'] = rand(0, 1);
                $ticketData['resolution'] = 'Resolution ' . $i; // set resolution only when status is 'closed'
            } else {
                $ticketData['is_solved'] = 0;
                $ticketData['resolution'] = ''; // set resolution to empty when status is not 'closed'
            }

            Ticket::create($ticketData);
        }

        // Additional ticket
        $ticketData = [
            'role' => 'user',
            'name' => 'User 50',
            'email' => 'user50@example.com',
            'issue' => 'Issue 50',
            'description' => 'Description 50',
            'active' => 1,
            'user_id' => rand(1, 9),
            'status' => 0,
            'employee_id' => null,
            'line' => 1,
            'urgency' => 1,
            'resolution' => 'Resolution 50',
            'created_at' => now()->subMonths(3), // created 3 months ago
            'updated_at' => now()->subMonths(3), // updated 3 months ago
        ];

        if ($ticketData['status'] === 1) { // if status is 'closed'
            $ticketData['close_date'] = now()->subMonths(3)->addDays(rand(1, 10));
            $ticketData['is_solved'] = rand(0, 1);
            $ticketData['resolution'] = 'Resolution 50'; // set resolution only when status is 'closed'
        } else {
            $ticketData['is_solved'] = 0;
            $ticketData['resolution'] = ''; // set resolution to empty when status is not 'closed'
        }

        Ticket::create($ticketData);
    }
}