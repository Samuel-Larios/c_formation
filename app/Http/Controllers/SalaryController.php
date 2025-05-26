<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Student;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    // Afficher la liste des salaires
    public function index()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Récupérer les salaires avec les étudiants du site de l'utilisateur connecté
        $salaries = Salary::whereHas('student', function ($query) use ($user) {
            $query->where('site_id', $user->site_id);
        })
        ->with('student')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('salaries.index', compact('salaries'));
    }

    // Récupérer les étudiants par promotion (AJAX)
    public function getStudentsByPromotion(Request $request)
    {
        $promotionId = $request->get('promotion_id');
        $user = Auth::user();

        // Vérifier si la promotion existe
        $promotion = Promotion::find($promotionId);
        if (!$promotion) {
            return response()->json(['error' => 'Promotion non trouvée'], 404);
        }

        // Récupérer les étudiants de la promotion et du site de l'utilisateur connecté
        $students = $promotion->students()
                              ->where('site_id', $user->site_id)
                              ->get(['id', 'last_name', 'first_name']);

        return response()->json($students);
    }

    // Afficher le formulaire de création d'un salaire
    public function create()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Récupérer toutes les promotions
        $promotions = Promotion::all();

        // Récupérer les étudiants du site de l'utilisateur connecté
        $students = Student::where('site_id', $user->site_id)->get();

        return view('salaries.create', compact('promotions', 'students'));
    }

    // Enregistrer un nouveau salaire
    public function store(Request $request)
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Valider les données
        $request->validate([
            'entreprise' => 'required|string',
            'localisation' => 'required|string',
            'employeur' => 'required|string',
            'tel' => 'required|string',
            'student_id' => [
                'required',
                'exists:students,id',
                function ($attribute, $value, $fail) use ($user) {
                    $student = Student::find($value);
                    if (!$student || $student->site_id !== $user->site_id) {
                        $fail('L\'étudiant sélectionné n\'appartient pas à votre site.');
                    }
                },
            ],
        ]);

        // Créer un nouveau salaire
        Salary::create($request->all());

        return redirect()->route('salaries.index')->with('success', 'Salaire créé avec succès.');
    }

    // Afficher le formulaire de modification d'un salaire
    public function edit(Salary $salary)
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Récupérer toutes les promotions
        $promotions = Promotion::all();

        // Récupérer les étudiants du site de l'utilisateur connecté
        $students = Student::where('site_id', $user->site_id)->get();

        return view('salaries.edit', compact('salary', 'promotions', 'students'));
    }

    // Mettre à jour un salaire
    public function update(Request $request, Salary $salary)
    {
        // Valider les données
        $request->validate([
            'entreprise' => 'required|string',
            'localisation' => 'required|string',
            'employeur' => 'required|string',
            'tel' => 'required|string',
        ]);

        // Mettre à jour le salaire
        $salary->update($request->only([
            'entreprise',
            'localisation',
            'employeur',
            'tel',
        ]));

        return redirect()->route('salaries.index')->with('success', 'Salary updated successfully.');
    }

    // Afficher les détails d'un salaire
    public function show($id)
    {
        // Récupérer le salaire avec l'étudiant associé
        $salary = Salary::with('student')->findOrFail($id);

        return view('salaries.show', compact('salary'));
    }

    // Supprimer un salaire
    public function destroy(Salary $salary)
    {
        $salary->delete();
        return redirect()->route('salaries.index')->with('success', 'Salary successfully deleted.');
    }
}
