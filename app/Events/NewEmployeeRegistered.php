<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Employee_Profile;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NewEmployeeRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $employee;

    public function __construct(Employee_Profile $employee)
    {
        $this->employee = $employee;
    }
}
