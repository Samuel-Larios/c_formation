<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\JobCreation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobCreationController extends Controller
{
    // Afficher la liste des job creations
    public function index()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Récupérer les jobs liés aux étudiants du site de l'utilisateur connecté
        $jobCreations = JobCreation::whereHas('student', function ($query) use ($user) {
            $query->where('site_id', $user->site_id);
        })->latest()->paginate(10);

        return view('job-creations.index', compact('jobCreations'));
    }

    // Afficher le formulaire pour créer un job creation
    public function create()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Récupérer les étudiants du site de l'utilisateur connecté
        $students = Student::where('site_id', $user->site_id)->get();

        return view('job-creations.create', compact('students'));
    }

    // Enregistrer un nouveau job creation
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'jobs' => 'required|array',
            'jobs.*.nom' => 'required|string|max:255',
            'jobs.*.tel' => 'required|string|max:15',
        ]);

        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Vérifier que l'étudiant sélectionné appartient au site de l'utilisateur connecté
        $student = Student::where('id', $request->student_id)
                          ->where('site_id', $user->site_id)
                          ->firstOrFail();

        // Créer les jobs pour l'étudiant sélectionné
        foreach ($request->jobs as $jobData) {
            $job = new JobCreation($jobData);
            $job->student_id = $student->id;
            $job->save();
        }

        return redirect()->route('jobcreations.index')->with('success', 'Successful job creations.');
    }

    // Afficher le formulaire pour modifier un job creation
    public function edit(JobCreation $jobCreation)
    {
        $students = Student::all();
        return view('job-creations.edit', compact('jobCreation', 'students'));
    }

    // Mettre à jour un job creation
    public function update(Request $request, JobCreation $jobCreation)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'nom' => 'required|string|max:255',
            'tel' => 'required|string|max:15',
        ]);

        $jobCreation->update([
            'student_id' => $request->student_id,
            'nom' => $request->nom,
            'tel' => $request->tel,
        ]);

        return redirect()->route('jobcreations.index')->with('success', 'Job creation successfully updated.');
    }

    // Supprimer un job creation
    public function destroy(JobCreation $jobCreation)
    {
        $jobCreation->delete();
        return redirect()->route('jobcreations.index')->with('success', 'Job creation successfully deleted.');
    }
}
