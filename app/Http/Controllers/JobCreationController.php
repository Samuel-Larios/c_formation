<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\JobCreation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobCreationController extends Controller
{
    // Afficher la liste des job creations
    public function index(Request $request)
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Récupérer la promotion sélectionnée pour le filtre
        $promotionId = $request->input('promotion_id');

        // Construire la requête pour récupérer les jobs liés aux étudiants du site de l'utilisateur connecté
        $query = JobCreation::whereHas('student', function ($query) use ($user, $promotionId) {
            $query->where('site_id', $user->site_id);
            if ($promotionId) {
                $query->whereHas('promotions', function ($q) use ($promotionId) {
                    $q->where('promotions.id', $promotionId);
                });
            }
        });

        $jobCreations = $query->latest()->paginate(10);

        // Calculer le nombre d'hommes et de femmes par promotion pour les job creations
        $genderCounts = [];
        if ($promotionId) {
            $genderCounts = (clone $query)->selectRaw('sexe, COUNT(*) as count')
                ->groupBy('sexe')
                ->pluck('count', 'sexe')
                ->toArray();
        }

        // Récupérer toutes les promotions pour le filtre, filtrées par site de l'utilisateur
        $promotions = \App\Models\Promotion::where('site_id', $user->site_id)->get();

        return view('job-creations.index', compact('jobCreations', 'promotions', 'genderCounts', 'promotionId'));
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
            'jobs.*.sexe' => 'required|string|in:Homme,Femme',
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
            'sexe' => 'required|string|in:Homme,Femme',
        ]);

        $jobCreation->update([
            'student_id' => $request->student_id,
            'nom' => $request->nom,
            'tel' => $request->tel,
            'sexe' => $request->sexe,
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
