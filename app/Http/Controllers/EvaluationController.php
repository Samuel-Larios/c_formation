<?php

namespace App\Http\Controllers;
use App\Models\Evaluation;
use App\Models\Matier;
use App\Models\Promotion;
use App\Models\Student;
use Illuminate\Http\Request;


class EvaluationController extends Controller
{
    // Affiche la liste des évaluations
    public function index(Request $request)
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = session('site_id');

        // Récupérer toutes les promotions du site
        $promotions = Promotion::where('site_id', $siteId)->get();

        // Récupérer la promotion sélectionnée (si elle existe)
        $selectedPromotionId = $request->input('promotion_id');

        // Filtrer les évaluations en fonction de la promotion sélectionnée
        $evaluations = Evaluation::with(['student', 'matier'])
            ->whereHas('student', function ($query) use ($siteId, $selectedPromotionId) {
                $query->where('site_id', $siteId);
                if ($selectedPromotionId) {
                    $query->whereHas('promotions', function ($q) use ($selectedPromotionId) {
                        $q->where('promotion_id', $selectedPromotionId);
                    });
                }
            })
            ->latest()
            ->paginate(10);

        return view('evaluations.index', compact('evaluations', 'promotions', 'selectedPromotionId'));
    }

    // Affiche le formulaire pour ajouter une évaluation
    public function create()
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = session('site_id');

        // Récupérer les promotions, étudiants et matières du site de l'utilisateur connecté
        $promotions = Promotion::where('site_id', $siteId)->get();
        $students = Student::where('site_id', $siteId)->get();
        $matiers = Matier::where('site_id', $siteId)->get();

        return view('evaluations.create', compact('promotions', 'students', 'matiers'));
    }

    public function getStudentsByPromotion($promotionId)
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = session('site_id');

        // Récupérer les étudiants de la promotion et du site de l'utilisateur connecté
        $students = Student::whereHas('promotions', function ($query) use ($promotionId) {
            $query->where('promotion_id', $promotionId);
        })->where('site_id', $siteId)->get();

        return response()->json($students);
    }

    // Enregistre une nouvelle évaluation
    public function store(Request $request)
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = session('site_id');

        // Validation des données
        $validated = $request->validate([
            'note' => 'required|integer|min:0|max:20',
            'student_id' => 'required|exists:students,id',
            'matier_id' => 'required|exists:matiers,id',
        ]);

        // Vérifier que l'étudiant et la matière appartiennent au site de l'utilisateur connecté
        $student = Student::findOrFail($validated['student_id']);
        $matier = Matier::findOrFail($validated['matier_id']);

        if ($student->site_id != $siteId || $matier->site_id != $siteId) {
            abort(403, 'Accès non autorisé.');
        }

        // Créer l'évaluation
        Evaluation::create($validated);

        return redirect()->route('evaluations.index')->with('success', 'Rating added successfully');
    }

    // Affiche le formulaire pour modifier une évaluation existante
    public function edit($id)
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = session('site_id');

        // Récupérer l'évaluation
        $evaluation = Evaluation::findOrFail($id);

        // Vérifier que l'évaluation appartient au site de l'utilisateur connecté
        if ($evaluation->student->site_id != $siteId || $evaluation->matier->site_id != $siteId) {
            abort(403, 'Accès non autorisé.');
        }

        // Récupérer les étudiants et matières du site de l'utilisateur connecté
        $students = Student::where('site_id', $siteId)->get();
        $matiers = Matier::where('site_id', $siteId)->get();

        return view('evaluations.edit', compact('evaluation', 'students', 'matiers'));
    }

    // Met à jour une évaluation existante
    public function update(Request $request, $id)
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = session('site_id');

        // Récupérer l'évaluation
        $evaluation = Evaluation::findOrFail($id);

        // Vérifier que l'évaluation appartient au site de l'utilisateur connecté
        if ($evaluation->student->site_id != $siteId || $evaluation->matier->site_id != $siteId) {
            abort(403, 'Accès non autorisé.');
        }

        // Validation des données
        $validated = $request->validate([
            'note' => 'required|integer|min:0|max:20',
            'student_id' => 'required|exists:students,id',
            'matier_id' => 'required|exists:matiers,id',
        ]);

        // Vérifier que le nouvel étudiant et la nouvelle matière appartiennent au site de l'utilisateur connecté
        $student = Student::findOrFail($validated['student_id']);
        $matier = Matier::findOrFail($validated['matier_id']);

        if ($student->site_id != $siteId || $matier->site_id != $siteId) {
            abort(403, 'Accès non autorisé.');
        }

        // Mettre à jour l'évaluation
        $evaluation->update($validated);

        return redirect()->route('evaluations.index')->with('success', 'Assessment successfully updated');
    }

    // Supprime une évaluation
    public function destroy($id)
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = session('site_id');

        // Récupérer l'évaluation
        $evaluation = Evaluation::findOrFail($id);

        // Vérifier que l'évaluation appartient au site de l'utilisateur connecté
        if ($evaluation->student->site_id != $siteId || $evaluation->matier->site_id != $siteId) {
            abort(403, 'Accès non autorisé.');
        }

        // Supprimer l'évaluation
        $evaluation->delete();

        return redirect()->route('evaluations.index')->with('success', 'Review successfully deleted');
    }
}
