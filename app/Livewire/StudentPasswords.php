<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class StudentPasswords extends Component
{
    public $students = [];
    public $selectedStudent = null;
    public $newPassword = '';
    public $confirmPassword = '';

    public function mount()
    {
        $this->loadStudents();
    }

    public function loadStudents()
    {
        $this->students = Student::all();
    }

    public function selectStudent($studentId)
    {
        $this->selectedStudent = Student::find($studentId);
        $this->newPassword = '';
        $this->confirmPassword = '';
    }

    public function updatePassword()
    {
        $this->validate([
            'newPassword' => 'required|min:8',
            'confirmPassword' => 'required|same:newPassword',
        ]);

        if ($this->selectedStudent) {
            $this->selectedStudent->password = Hash::make($this->newPassword);
            $this->selectedStudent->save();

            Session::flash('message', 'Mot de passe mis à jour avec succès.');
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->selectedStudent = null;
        $this->newPassword = '';
        $this->confirmPassword = '';
    }

    public function render()
    {
        return view('livewire.student-passwords');
    }
}
