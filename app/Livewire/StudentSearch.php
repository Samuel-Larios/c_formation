<?php

namespace App\Livewire;

use App\Models\Student;
use Livewire\Component;

class StudentSearch extends Component
{
    public $search = '';

    public function render()
    {
        $students = collect(); // Toujours une collection

        if (strlen($this->search) > 2) {
            $students = Student::where('first_name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                ->orWhere('contact', 'like', '%' . $this->search . '%')
                ->limit(10)
                ->get();
        }

        return view('livewire.student-search', [
            'students' => $students
        ]);
    }
}
