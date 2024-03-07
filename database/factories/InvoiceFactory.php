<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => rand(0,9),
            'type' => rand(0,1)==0?'Annual':'Monthly',
            'price' => (float)rand(100, 1000),
            'status' => rand(0,1)==0?'Unpaid':'Paid',
            'due_date' => now(),
        ];
    }
}
