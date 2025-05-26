<?php

namespace App\Models;

namespace App\Http\Controllers;



use App\Models\Student;
use App\Models\Promotion;
use App\Models\Subvention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubventionController extends Controller
{
    /**
     * Afficher la liste des subventions.
     */
    public function index()
    {
        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Charger les subventions liées au site de l'utilisateur
        $subventions = Subvention::with('student')
            ->where('site_id', $siteId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('subventions.index', compact('subventions'));
    }

    /**
     * Afficher le formulaire de création d'une subvention.
     */
    public function create()
    {
        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Récupérer les promotions liées au site de l'utilisateur
        $promotions = Promotion::where('site_id', $siteId)->get();

        return view('subventions.create', compact('promotions'));
    }

    /**
     * Récupérer les étudiants d'une promotion spécifique.
     */
    public function getStudentsByPromotion($promotionId)
    {
        // Récupérer les étudiants de la promotion sélectionnée via la table pivot
        $students = Student::whereHas('promotions', function ($query) use ($promotionId) {
            $query->where('promotion_id', $promotionId);
        })->get();

        return response()->json($students);
    }


    //  Enregistrer une nouvelle subvention.
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $validated = $request->validate([
            'start_up_kits' => 'nullable|string',
            'grants' => 'nullable|string',
            'loan' => 'nullable|string',
            'date' => 'nullable|date',
            'student_id' => 'required|array', // Valider que student_id est un tableau
            'student_id.*' => 'exists:students,id', // Valider chaque élément du tableau
        ]);

        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Ajouter le site_id aux données validées
        $validated['site_id'] = $siteId;

        // Créer une subvention pour chaque étudiant sélectionné
        foreach ($validated['student_id'] as $studentId) {
            Subvention::create([
                'start_up_kits' => $validated['start_up_kits'],
                'grants' => $validated['grants'],
                'loan' => $validated['loan'],
                'date' => $validated['date'],
                'student_id' => $studentId,
                'site_id' => $siteId,
            ]);
        }

        // Rediriger avec un message de succès
        return redirect()->route('subventions.index')->with('success', 'Grant(s) successfully added');
    }

    // Afficher le formulaire de modification d'une subvention.

    public function edit($id)
    {
        // Récupérer la subvention
        $subvention = Subvention::findOrFail($id);

        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Récupérer les promotions et les étudiants liés au site de l'utilisateur
        $promotions = Promotion::where('site_id', $siteId)->get();
        $students = Student::where('site_id', $siteId)->get();

        return view('subventions.edit', compact('subvention', 'promotions', 'students'));
    }


    //   Mettre à jour une subvention.
    public function update(Request $request, $id)
    {
        // Récupérer la subvention
        $subvention = Subvention::findOrFail($id);

        // Validation des données du formulaire
        $validated = $request->validate([
            'start_up_kits' => 'nullable|string',
            'grants' => 'nullable|string',
            'loan' => 'nullable|string',
            'date' => 'nullable|date',
            'student_id' => 'required|exists:students,id',
        ]);

        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Ajouter le site_id aux données validées
        $validated['site_id'] = $siteId;

        // Mettre à jour la subvention
        $subvention->update($validated);

        return redirect()->route('subventions.index')->with('success', 'Subvention mise à jour avec succès.');
    }

    //  Supprimer une subvention.
    public function destroy($id)
    {
        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Récupérer la subvention liée au site de l'utilisateur
        $subvention = Subvention::where('site_id', $siteId)->findOrFail($id);

        // Supprimer la subvention
        $subvention->delete();

        return redirect()->route('subventions.index')->with('success', 'Subsidy successfully removed.');
    }
}
