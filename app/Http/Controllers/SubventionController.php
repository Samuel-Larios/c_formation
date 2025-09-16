<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Promotion;
use App\Models\Subvention;
use App\Exports\SubventionsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SubventionController extends Controller
{
    /**
     * Display the list of subsidies.
     */
    public function index()
    {
        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Récupérer les promotions liées au site de l'utilisateur
        $promotions = Promotion::where('site_id', $siteId)->get();

        // Charger les subventions liées au site de l'utilisateur, avec filtrage par promotion si spécifié
        $subventions = Subvention::with('student')
            ->where('site_id', $siteId)
            ->when(request('promotion'), function($query) {
                $query->whereHas('student.promotions', function($q) {
                    $q->where('promotion_id', request('promotion'));
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('subventions.index', compact('subventions', 'promotions'));
    }

    /**
     * Display the form to create a subsidy.
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
     * Retrieve students from a specific promotion.
     */
    public function getStudentsByPromotion($promotionId)
    {
        // Récupérer les étudiants de la promotion sélectionnée via la table pivot
        $students = Student::whereHas('promotions', function ($query) use ($promotionId) {
            $query->where('promotion_id', $promotionId);
        })->get();

        return response()->json($students);
    }

    /**
     * Save a new subsidy.
     */
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $validated = $request->validate([
            'start_up_kits' => 'nullable|string',
            'grants' => 'nullable|string',
            'loan' => 'nullable|string',
            'date' => 'nullable|date',
            'start_up_kits_items_received' => 'nullable|string',
            'state_of_farm_location' => 'nullable|string',
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
                'start_up_kits_items_received' => $validated['start_up_kits_items_received'],
                'state_of_farm_location' => $validated['state_of_farm_location'],
                'student_id' => $studentId,
                'site_id' => $siteId,
            ]);
        }

        // Rediriger avec un message de succès
        return redirect()->route('subventions.index')->with('success', 'Grant(s) successfully added');
    }

    /**
     * Display the form to edit a subsidy.
     */
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

    /**
     * Update a subsidy.
     */
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
            'start_up_kits_items_received' => 'nullable|string',
            'state_of_farm_location' => 'nullable|string',
            'student_id' => 'required|exists:students,id',
        ]);

        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Ajouter le site_id aux données validées
        $validated['site_id'] = $siteId;

        // Mettre à jour la subvention
        $subvention->update($validated);

        return redirect()->route('subventions.index')->with('success', 'Subsidy updated successfully.');
    }

    /**
     * Delete a subsidy.
     */
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

    /**
     * Display the details of a subsidy.
     */
    public function show($id)
    {
        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Récupérer la subvention liée au site de l'utilisateur
        $subvention = Subvention::with('student')->where('site_id', $siteId)->findOrFail($id);

        return view('subventions.show', compact('subvention'));
    }

    /**
     * Export subsidies to Excel based on selected promotion.
     */
    public function export(Request $request)
    {
        $request->validate([
            'promotion' => 'nullable|exists:promotions,id',
        ]);

        $siteId = Auth::user()->site_id;
        $promotionId = $request->promotion;

        return Excel::download(new SubventionsExport($siteId, $promotionId), 'subventions.xlsx');
    }
}
