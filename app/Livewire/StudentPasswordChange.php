<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class StudentPasswordChange extends Component
{

    public $studentId;
    public $newPassword;
    public $confirmPassword;
    public $email;

    protected function rules()
    {
        return [
            'email' => 'nullable|email|unique:students,email,' . $this->studentId,
            'newPassword' => 'required|string|min:8|same:confirmPassword',
            'confirmPassword' => 'required|string|min:8',
        ];
    }

    public function mount($studentId)
    {
        $this->studentId = $studentId;
        $student = \App\Models\Student::find($studentId);
        if ($student) {
            $this->email = $student->email;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function changePassword()
    {
        $this->validate();

        $student = Student::findOrFail($this->studentId);
        $student->email = $this->email;
        $student->password = Hash::make($this->newPassword);
        $student->save();

        session()->flash('message', 'Email and password updated successfully.');
    }

    public function render()
    {
        return view('livewire.student-password-change');
    }
}
