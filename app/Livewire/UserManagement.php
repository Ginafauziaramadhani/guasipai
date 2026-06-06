<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagement extends Component
{
    public $users;
    public $id_user, $name, $email, $password, $role;
    public $isOpen = false;

    public function render()
    {
        $this->users = User::all();
        return view('livewire.user-management')->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->id_user = '';
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'admin'; // default
    }

    public function store()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->id_user)],
            'role' => 'required|in:admin,pimpinan',
        ];

        // Jika user baru, password wajib. Jika edit, password opsional
        if (empty($this->id_user)) {
            $rules['password'] = 'required|min:8';
        } else {
            $rules['password'] = 'nullable|min:8';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->id_user], $data);

        session()->flash('success', $this->id_user ? 'User berhasil diperbarui.' : 'User berhasil ditambahkan.');
        
        $this->closeModal();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->id_user = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = ''; // Kosongkan password saat diedit
        
        $this->openModal();
    }

    public function delete($id)
    {
        // Cegah menghapus diri sendiri
        if ($id == auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            return;
        }

        User::findOrFail($id)->delete();
        session()->flash('success', 'User berhasil dihapus.');
    }
}
