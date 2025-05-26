<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

class StudentSalaryController extends Controller
{
    public function index()
    {
        /** @var Student $student */
        $student = Auth::guard('student')->user();
        $salaries = $student->salaries()->latest()->get();

        return view('student.salaries.index', compact('salaries'));
    }

    public function create()
    {
        return view('student.salaries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'entreprise' => 'required|string|max:255',
            'localisation' => 'required|string|max:255',
            'employeur' => 'required|string|max:255',
            'tel' => 'required|string|max:20|regex:/^[0-9+]+$/',
        ]);

        /** @var Student $student */
        $student = Auth::guard('student')->user();

        $student->salaries()->create([
            'entreprise' => $validated['entreprise'],
            'localisation' => $validated['localisation'],
            'employeur' => $validated['employeur'],
            'tel' => $validated['tel'],
            'site_id' => $student->site_id,
        ]);

        return redirect()
               ->route('student.salaries.index')
               ->with('success', 'Employee successfully added.');
    }

    public function edit(Salary $salary)
    {
        $this->verifyOwnership($salary);
        return view('student.salaries.edit', compact('salary'));
    }

    public function update(Request $request, Salary $salary)
    {
        $this->verifyOwnership($salary);

        $validated = $request->validate([
            'entreprise' => 'required|string|max:255',
            'localisation' => 'required|string|max:255',
            'employeur' => 'required|string|max:255',
            'tel' => 'required|string|max:20|regex:/^[0-9+]+$/',
        ]);

        $salary->update($validated);

        return redirect()
               ->route('student.salaries.index')
               ->with('success', 'Employee successfully updated.');
    }

    public function destroy(Salary $salary)
    {
        $this->verifyOwnership($salary);
        $salary->delete();

        return redirect()
               ->route('student.salaries.index')
               ->with('success', 'Employee successfully removed.');
    }

    protected function verifyOwnership(Salary $salary)
    {
        if ($salary->student_id !== Auth::guard('student')->id()) {
            abort(403, 'Unauthorized action');
        }
    }
}
