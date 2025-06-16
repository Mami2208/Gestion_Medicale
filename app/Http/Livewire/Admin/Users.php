<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Utilisateur;

class Users extends Component
{
    public $users;

    public function mount()
    {
        $this->users = Utilisateur::all();
    }

    public function render()
    {
        return view('livewire.admin.users', [
            'users' => $this->users,
        ]);
    }
}
