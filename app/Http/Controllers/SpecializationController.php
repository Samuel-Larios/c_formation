<?php

namespace App\Http\Controllers;

use App\Models\Specialite;
use App\Models\Specialization;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecializationController extends Controller
{
    public function index()
    {
        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Charger les spécialisations liées au site de l'utilisateur
        $specializations = Specialization::with('student', 'specialite')
            ->where('site_id', $siteId)
            ->latest()
            ->paginate(10);

        return view('specializations.index', compact('specializations'));
    }

    public function create()
    {
        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Récupérer les étudiants qui n'ont pas encore de spécialité
        $assignedStudentIds = Specialization::where('site_id', $siteId)->pluck('student_id');

        $students = Student::where('site_id', $siteId)
                            ->whereNotIn('id', $assignedStudentIds) // Exclure les étudiants déjà spécialisés
                            ->get();

        // Récupérer toutes les spécialités, mais sélectionner la dernière enregistrée par défaut
        $specialites = Specialite::where('site_id', $siteId)->orderByDesc('created_at')->get();

        return view('specializations.create', compact('students', 'specialites'));
    }


    public function store(Request $request)
    {
        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Validation des données du formulaire
        $request->validate([
            'specialite_id' => 'required|exists:specialites,id',
            'student_ids' => 'required|array', // Accepter un tableau d'étudiants
            'student_ids.*' => 'exists:students,id', // Valider chaque étudiant dans le tableau
        ]);

        // Ajouter chaque étudiant sélectionné à la spécialisation
        foreach ($request->student_ids as $studentId) {
            Specialization::create([
                'specialite_id' => $request->specialite_id,
                'student_id' => $studentId,
                'site_id' => $siteId, // Associer le site_id de l'utilisateur connecté
            ]);
        }

        return redirect()->route('specializations.index')->with('success', 'Students successfully added to the specialization.');
    }


    public function getStudentsBySpecialization(Request $request)
    {
        $specialiteId = $request->input('specialite_id');
        $siteId = Auth::user()->site_id;

        $assignedStudentIds = Specialization::where('specialite_id', $specialiteId)
                                            ->where('site_id', $siteId)
                                            ->pluck('student_id');
        dd($assignedStudentIds); // Vérifiez les IDs des étudiants déjà associés

        $students = Student::where('site_id', $siteId)
                            ->whereNotIn('id', $assignedStudentIds)
                            ->get(['id', 'first_name', 'last_name']);
        dd($students); // Vérifiez les étudiants disponibles pour cette spécialisation

        return response()->json($students);
    }
    public function show(Specialization $specialization)
    {
        // Vérifier que la spécialisation appartient au site de l'utilisateur connecté
        $siteId = Auth::user()->site_id;
        if ($specialization->site_id !== $siteId) {
            abort(403, 'Accès non autorisé.');
        }

        return view('specializations.show', compact('specialization'));
    }

    public function edit(Specialization $specialization)
    {
        // Vérifier que la spécialisation appartient au site de l'utilisateur connecté
        $siteId = Auth::user()->site_id;
        if ($specialization->site_id !== $siteId) {
            abort(403, 'Accès non autorisé.');
        }

        // Récupérer les étudiants et les spécialités liés au site de l'utilisateur
        $students = Student::where('site_id', $siteId)->get();
        $specialites = Specialite::where('site_id', $siteId)->get();

        return view('specializations.edit', compact('specialization', 'students', 'specialites'));
    }

    public function update(Request $request, Specialization $specialization)
    {
        // Vérifier que la spécialisation appartient au site de l'utilisateur connecté
        $siteId = Auth::user()->site_id;
        if ($specialization->site_id !== $siteId) {
            abort(403, 'Accès non autorisé.');
        }

        // Validation des données du formulaire
        $request->validate([
            'specialite_id' => 'required|exists:specialites,id',
            'student_id' => 'required|exists:students,id',
        ]);

        // Mettre à jour la spécialisation
        $specialization->update($request->all());

        return redirect()->route('specializations.index')->with('success', 'Specialization updated.');
    }

    public function destroy(Specialization $specialization)
    {
        // Vérifier que la spécialisation appartient au site de l'utilisateur connecté
        $siteId = Auth::user()->site_id;
        if ($specialization->site_id !== $siteId) {
            abort(403, 'Accès non autorisé.');
        }

        // Supprimer la spécialisation
        $specialization->delete();

        return redirect()->route('specializations.index')->with('success', 'Specialization removed.');
    }
}
