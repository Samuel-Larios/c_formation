<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Student;

class StudentSearch extends Component
{
    public $search = '';
    public $students = [];

    public function updatedSearch()
    {
        if (strlen($this->search) > 2) {
            $this->students = Student::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->limit(10)
                ->get();
        } else {
            $this->students = [];
        }
    }

    public function render()
    {
        return view('livewire.student-search');
    }
}
