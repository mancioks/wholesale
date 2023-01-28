<?php

namespace App\Http\Livewire\Admin;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class EditUser extends Component
{
    public $success = false;
    public $roles;
    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->roles = Role::all();
    }

    public function rules()
    {
        return [
            'user.name' => 'required',
            'user.email' => 'required|email|unique:users,email,' . $this->user->id,
            'user.role_id' => 'required',
            'user.pvm' => 'required',
        ];
    }

    public function submit()
    {
        $this->validate();
        $this->user->save();
        $this->success = true;

        $this->emit('userUpdated');
    }

    public function updated()
    {
        $this->success = false;
    }

    public function render()
    {
        return view('livewire.admin.edit-user');
    }
}
