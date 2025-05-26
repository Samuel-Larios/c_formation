<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    public function index()
    {
        // Récupérer le site_id de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Charger les promotions liées au site de l'utilisateur connecté
        $promotions = Promotion::where('site_id', $siteId)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('promotions.index', compact('promotions'));
    }


    public function create()
    {
        return view('promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'num_promotion' => 'required|string|max:255',
        ]);

        // Ajouter le site_id de l'utilisateur connecté
        $request->merge(['site_id' => Auth::user()->site_id]);

        Promotion::create($request->all());

        return redirect()->route('promotions.index')->with('success', 'Promotion added successfully.');
    }


    // Afficher les étudiants d'une promotion
    public function showStudents($id)
    {
        // Récupérer la promotion avec les étudiants inscrits
        $promotion = Promotion::with('students')->findOrFail($id);

        // Retourner la vue avec les étudiants de la promotion
        return view('promotions.students', compact('promotion'));
    }

    public function edit(Promotion $promotion)
    {
        // Vérifier que la promotion appartient au site de l'utilisateur connecté
        if ($promotion->site_id !== Auth::user()->site_id) {
            abort(403, 'Accès non autorisé.');
        }

        return view('promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        // Vérifier que la promotion appartient au site de l'utilisateur connecté
        if ($promotion->site_id !== Auth::user()->site_id) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'num_promotion' => 'required|string|max:255',
        ]);

        $promotion->update($request->all());

        return redirect()->route('promotions.index')->with('success', 'Promotion successfully updated.');
    }

    public function destroy(Promotion $promotion)
    {
        // Vérifier que la promotion appartient au site de l'utilisateur connecté
        if ($promotion->site_id !== Auth::user()->site_id) {
            abort(403, 'Accès non autorisé.');
        }

        $promotion->delete();
        return redirect()->route('promotions.index')->with('success', 'Promotion successfully removed.');
    }
}
