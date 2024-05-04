<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ];

            if ($status === 1) { // if status is 'closed'
                $ticketData['close_date'] = now()->addDays(rand(1, 10));
                $ticketData['is_solved'] = rand(0, 1);
            } else {
                $ticketData['is_solved'] = 0;
            }

            Ticket::create($ticketData);
        }
    }
}