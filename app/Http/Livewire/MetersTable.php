<?php

namespace App\Http\Livewire;

use App\Models\Meter;
use Livewire\Component;

class MetersTable extends Component
{
    public function render()
    {
        return view('livewire.meters-table',
        [
            'meters' => Meter::all()
        ]
    );
    }
}
