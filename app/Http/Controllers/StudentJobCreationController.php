<?php

namespace App\Http\Controllers;

use App\Models\JobCreation;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

class StudentJobCreationController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Student $student */
        $student = Auth::guard('student')->user();
        $jobCreations = $student->jobCreations()->latest()->get();

        return view('student.job_creations.index', compact('jobCreations'));
    }

    public function create()
    {
        return view('student.job_creations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'tel' => 'required|string|max:20|regex:/^[0-9+]+$/',
        ]);

        /** @var Student $student */
        $student = Auth::guard('student')->user();

        $student->jobCreations()->create([
            'nom' => $validated['nom'],
            'tel' => $validated['tel'],
            'site_id' => $student->site_id,
        ]);

        return redirect()
               ->route('student.job_creations.index')
               ->with('success', 'Job creation successfully added.');
    }

    public function edit(JobCreation $jobCreation)
    {
        $this->verifyOwnership($jobCreation);
        return view('student.job_creations.edit', compact('jobCreation'));
    }

    public function update(Request $request, JobCreation $jobCreation)
    {
        $this->verifyOwnership($jobCreation);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'tel' => 'required|string|max:20|regex:/^[0-9+]+$/',
        ]);

        $jobCreation->update($validated);

        return redirect()
               ->route('student.job_creations.index')
               ->with('success', 'Job creation successfully updated.');
    }

    public function destroy(JobCreation $jobCreation)
    {
        $this->verifyOwnership($jobCreation);
        $jobCreation->delete();

        return redirect()
               ->route('student.job_creations.index')
               ->with('success', 'Job creation successfully deleted.');
    }

    protected function verifyOwnership(JobCreation $jobCreation)
    {
        if ($jobCreation->student_id !== Auth::guard('student')->id()) {
            abort(403, 'Unauthorized action');
        }
    }
}
