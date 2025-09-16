<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\ImageFollowUp;
use App\Models\Student;
use App\Models\Promotion;
use App\Exports\FollowUpExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class FollowUpController extends Controller
{
    /**
     * Afficher la liste des suivis.
     */
    public function index(Request $request)
    {
        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Récupérer les promotions liées au site de l'utilisateur
        $promotions = Promotion::where('site_id', $siteId)->get();

        // Charger les suivis liés au site de l'utilisateur avec filtres
        $query = FollowUp::with('student', 'images')
            ->where('site_id', $siteId);

        // Appliquer les filtres
        if ($request->filled('farm_visits')) {
            $query->where('farm_visits', 'like', '%' . $request->farm_visits . '%');
        }
        if ($request->filled('phone_contact')) {
            $query->where('phone_contact', 'like', '%' . $request->phone_contact . '%');
        }
        if ($request->filled('sharing_of_impact_stories')) {
            $query->where('sharing_of_impact_stories', 'like', '%' . $request->sharing_of_impact_stories . '%');
        }
        if ($request->filled('back_stopping')) {
            $query->where('back_stopping', 'like', '%' . $request->back_stopping . '%');
        }
        if ($request->filled('promotion')) {
            $promotionId = $request->promotion;
            $query->whereHas('student.promotions', function($q) use ($promotionId) {
                $q->where('promotion_id', $promotionId);
            });
        }

        $followUps = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('follow_ups.index', compact('followUps', 'promotions'));
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
            'images' => 'required|array|max:15',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Ajouter le site_id aux données validées
        $validated['site_id'] = $siteId;

        // Créer le suivi
        $followUp = FollowUp::create($validated);

        // Gérer l'upload des images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images/follow_ups', 'public');
                ImageFollowUp::create([
                    'follow_up_id' => $followUp->id,
                    'image_path' => $path,
                ]);
            }
        }

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
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Mettre à jour le suivi
        $followUp->update($validated);

        // Gérer l'upload des nouvelles images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images/follow_ups', 'public');
                ImageFollowUp::create([
                    'follow_up_id' => $followUp->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('follow_ups.index')->with('success', 'Tracking successfully updated.');
    }

    /**
     * Afficher les détails d'un suivi.
     */
    public function show($id)
    {
        // Récupérer le suivi avec les images
        $followUp = FollowUp::with('student', 'images')->findOrFail($id);

        return view('follow_ups.show', compact('followUp'));
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

    /**
     * Exporter les suivis en Excel.
     */
    public function export(Request $request)
    {
        $siteId = Auth::user()->site_id;
        $promotionId = $request->promotion;

        return Excel::download(new FollowUpExport($siteId, $promotionId), 'follow_ups.xlsx');
    }
}
