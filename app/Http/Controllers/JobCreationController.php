<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\JobCreation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobCreationController extends Controller
{
    // Afficher la liste des job creations
    public function index(Request $request)
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Récupérer la promotion sélectionnée pour le filtre
        $promotionId = $request->input('promotion');

        // Agréger les étudiants de la promotion sur tous les sites si une promotion est sélectionnée
        $aggregateAcrossSites = $promotionId ? true : false;

        // Construire la requête pour récupérer les jobs liés aux étudiants
        $query = JobCreation::query();
        if ($promotionId) {
            // Lorsque une promotion est sélectionnée, inclure les jobs des étudiants de cette promotion sur tous les sites
            $query->whereHas('student', function ($q) use ($promotionId) {
                $q->whereHas('promotions', function ($qq) use ($promotionId) {
                    $qq->where('num_promotion', $promotionId);
                });
            });
        } else {
            // Sinon, filtrer par site de l'utilisateur
            $query->whereHas('student', function ($query) use ($user) {
                $query->where('site_id', $user->site_id);
            });
        }

        $jobCreations = $query->latest()->paginate(10);

        // Calculer la distribution par genre des étudiants de la promotion sélectionnée
        $studentGenderCounts = [];
        $totalStudents = 0;
        $totalStudentsInPromotion = 0;
        $studentsWithJobs = 0;
        $studentsWithoutJobs = 0;
        $totalJobCreations = 0;
        $expectedStudentsWithJobs = 0;
        $actualPercentage = 0;
        $difference = 0;
        $isReached = false;
        $histogramData = ['students_with_jobs' => 0, 'students_without_jobs' => 0];

        if ($promotionId) {
            // Distribution par genre des étudiants de la promotion sélectionnée
            $genderQuery = DB::table('promotion_apprenant')
                ->join('students', 'promotion_apprenant.student_id', '=', 'students.id')
                ->join('promotions', 'promotion_apprenant.promotion_id', '=', 'promotions.id')
                ->where('promotions.num_promotion', $promotionId);
            if (!$aggregateAcrossSites) {
                $genderQuery->where('promotion_apprenant.site_id', $user->site_id);
            }
            $studentGenderCounts = $genderQuery
                ->selectRaw('students.sexe, COUNT(DISTINCT students.id) as count')
                ->groupBy('students.sexe')
                ->pluck('count', 'sexe')
                ->toArray();

            // Calculer le total des étudiants
            $totalStudents = array_sum($studentGenderCounts);

            // Total des étudiants dans la promotion
            $totalQuery = DB::table('promotion_apprenant')
                ->join('promotions', 'promotion_apprenant.promotion_id', '=', 'promotions.id')
                ->where('promotions.num_promotion', $promotionId);
            if (!$aggregateAcrossSites) {
                $totalQuery->where('promotion_apprenant.site_id', $user->site_id);
            }
            $totalStudentsInPromotion = $totalQuery->distinct('promotion_apprenant.student_id')->count('promotion_apprenant.student_id');

            // Étudiants avec des jobs
            $jobsQuery = DB::table('job_creations')
                ->join('students', 'job_creations.student_id', '=', 'students.id')
                ->join('promotion_apprenant', 'students.id', '=', 'promotion_apprenant.student_id')
                ->join('promotions', 'promotion_apprenant.promotion_id', '=', 'promotions.id')
                ->where('promotions.num_promotion', $promotionId);
            if (!$aggregateAcrossSites) {
                $jobsQuery->where('promotion_apprenant.site_id', $user->site_id);
            }
            $studentsWithJobs = $jobsQuery->distinct('students.id')->count('students.id');

            // Étudiants sans jobs
            $studentsWithoutJobs = $totalStudentsInPromotion - $studentsWithJobs;

            // Total des créations de jobs
            $totalJobsQuery = DB::table('job_creations')
                ->join('students', 'job_creations.student_id', '=', 'students.id')
                ->join('promotion_apprenant', 'students.id', '=', 'promotion_apprenant.student_id')
                ->join('promotions', 'promotion_apprenant.promotion_id', '=', 'promotions.id')
                ->where('promotions.num_promotion', $promotionId);
            if (!$aggregateAcrossSites) {
                $totalJobsQuery->where('promotion_apprenant.site_id', $user->site_id);
            }
            $totalJobCreations = $totalJobsQuery->count();

            // Calculs pour l'objectif de 70%
            $expectedStudentsWithJobs = round($totalStudentsInPromotion * 0.7);
            $actualPercentage = $totalStudentsInPromotion > 0 ? round(($studentsWithJobs / $totalStudentsInPromotion) * 100, 2) : 0;
            $difference = $actualPercentage - 70;
            $isReached = $actualPercentage >= 70;

            // Données pour l'histogramme
            $histogramData = [
                'students_with_jobs' => $studentsWithJobs,
                'students_without_jobs' => $studentsWithoutJobs
            ];
        }

        // Calculer le résumé par étudiant : afficher l'étudiant une fois et lister les employeurs et le nombre de personnes qui travaillent avec lui
        $studentJobCounts = (clone $query)->select('student_id', \Illuminate\Support\Facades\DB::raw('GROUP_CONCAT(CONCAT(id, ":", nom, " (", tel, ")")) as employers, COUNT(*) as job_count'))
            ->groupBy('student_id')
            ->with('student')
            ->paginate(10);

        // Récupérer toutes les promotions pour le filtre, filtrées par site de l'utilisateur
        // et qui ont des étudiants inscrits
        $promotions = \App\Models\Promotion::select('num_promotion')->distinct()->get();

        return view('job-creations.index', compact('jobCreations', 'promotions', 'studentGenderCounts', 'promotionId', 'studentJobCounts', 'totalStudents', 'totalStudentsInPromotion', 'studentsWithJobs', 'studentsWithoutJobs', 'totalJobCreations', 'expectedStudentsWithJobs', 'actualPercentage', 'difference', 'isReached', 'histogramData', 'aggregateAcrossSites'));
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
