<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\LogAccess;

class Audit extends Component
{
    public $logs;

    public function mount()
    {
        $this->logs = LogAccess::orderBy('created_at', 'desc')->paginate(20);
    }

    public function render()
    {
        return view('livewire.admin.audit', [
            'logs' => $this->logs,
        ]);
    }
}
