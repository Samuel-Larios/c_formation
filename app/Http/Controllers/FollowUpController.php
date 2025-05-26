<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\Student;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowUpController extends Controller
{
    /**
     * Afficher la liste des suivis.
     */
    public function index()
    {
        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Charger les suivis liés au site de l'utilisateur
        $followUps = FollowUp::with('student')
            ->where('site_id', $siteId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('follow_ups.index', compact('followUps'));
    }

    /**
     * Afficher le formulaire de création d'un suivi.
     */
    public function create()
    {
        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Récupérer les promotions liées au site de l'utilisateur
        $promotions = Promotion::where('site_id', $siteId)->get();

        return view('follow_ups.create', compact('promotions'));
    }

    /**
     * Récupérer les étudiants d'une promotion spécifique.
     */
    public function getStudentsByPromotion($promotionId)
    {
        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Récupérer les étudiants de la promotion sélectionnée et du site de l'utilisateur
        $students = Student::whereHas('promotions', function ($query) use ($promotionId) {
            $query->where('promotion_id', $promotionId);
        })->where('site_id', $siteId)->get();

        return response()->json($students);
    }

    /**
     * Enregistrer un nouveau suivi.
     */
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $validated = $request->validate([
            'farm_visits' => 'required|string',
            'phone_contact' => 'required|string',
            'sharing_of_impact_stories' => 'required|string',
            'back_stopping' => 'required|string',
            'student_id' => 'required|exists:students,id',
        ]);

        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Ajouter le site_id aux données validées
        $validated['site_id'] = $siteId;

        // Créer le suivi
        FollowUp::create($validated);

        // Rediriger avec un message de succès
        return redirect()->route('follow_ups.index')->with('success', 'Tracking added successfully.');
    }

    /**
     * Afficher le formulaire de modification d'un suivi.
     */
    public function edit($id)
    {
        // Récupérer le suivi
        $followUp = FollowUp::findOrFail($id);

        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Récupérer les promotions et les étudiants liés au site de l'utilisateur
        $promotions = Promotion::where('site_id', $siteId)->get();
        $students = Student::where('site_id', $siteId)->get();

        return view('follow_ups.edit', compact('followUp', 'promotions', 'students'));
    }

    /**
     * Mettre à jour un suivi.
     */
    public function update(Request $request, $id)
    {
        // Récupérer le suivi
        $followUp = FollowUp::findOrFail($id);

        // Validation des données du formulaire
        $validated = $request->validate([
            'farm_visits' => 'required|string',
            'phone_contact' => 'required|string',
            'sharing_of_impact_stories' => 'required|string',
            'back_stopping' => 'required|string',
            'student_id' => 'required|exists:students,id',
        ]);

        // Mettre à jour le suivi
        $followUp->update($validated);

        return redirect()->route('follow_ups.index')->with('success', 'Tracking successfully updated.');
    }

    /**
     * Supprimer un suivi.
     */
    public function destroy($id)
    {
        // Récupérer le suivi
        $followUp = FollowUp::findOrFail($id);

        // Supprimer le suivi
        $followUp->delete();

        return redirect()->route('follow_ups.index')->with('success', 'Tracking successfully removed.');
    }
}
