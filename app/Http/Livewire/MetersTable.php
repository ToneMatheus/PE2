<?php

namespace App\Http\Livewire;

use App\Models\Meter;
use Livewire\Component;
use Livewire\WithPagination;

class MetersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $type = '';
    public $status = '';

    public function render()
    {
        return view('livewire.meters-table',
        [
            'meters' => Meter::search($this->search)
            ->when($this->type !== '', function($query){
                $query->where('type', $this->type);
            })
            ->when($this->status !== '', function($query){
                $query->where('status', $this->status);
            })
            ->paginate($this->perPage)
        ]
    );
    }
}
