<?php

namespace App\Http\Controllers;

use App\Models\Specialite;
use App\Models\Specialization;
use App\Models\Student; // Assurez-vous d'importer le modèle Student
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecialiteController extends Controller
{
    // Afficher la liste des spécialités pour le site de l'utilisateur
    public function index()
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = Auth::user()->site_id;

        // Récupérer les spécialités du site
        $specialites = Specialite::where('site_id', $siteId)->get();

        return view('specialites.index', compact('specialites'));
    }

    // Afficher le formulaire de création d'une spécialité
    public function create()
    {
        return view('specialites.create');
    }

    // Enregistrer une nouvelle spécialité
    public function store(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'designation' => 'required|string|max:255',
        ]);

        // Créer la spécialité en la liant au site de l'utilisateur
        Specialite::create([
            'designation' => $request->designation,
            'site_id' => Auth::user()->site_id, // Lier au site de l'utilisateur
        ]);

        return redirect()->route('specialites.index')->with('success', 'Specialty successfully created.');
    }

    // Afficher le formulaire d'édition d'une spécialité
    public function edit(Specialite $specialite)
    {
        // Vérifier que l'utilisateur a le droit de modifier cette spécialité
        $this->authorizeAccess($specialite);

        return view('specialites.edit', compact('specialite'));
    }

    // Mettre à jour une spécialité
    public function update(Request $request, Specialite $specialite)
    {
        // Vérifier que l'utilisateur a le droit de modifier cette spécialité
        $this->authorizeAccess($specialite);

        // Valider les données du formulaire
        $request->validate([
            'designation' => 'required|string|max:255',
        ]);

        // Mettre à jour la spécialité
        $specialite->update([
            'designation' => $request->designation,
        ]);

        return redirect()->route('specialites.index')->with('success', 'Specialty successfully updated.');
    }

    // Supprimer une spécialité
    public function destroy(Specialite $specialite)
    {
        // Vérifier que l'utilisateur a le droit de supprimer cette spécialité
        $this->authorizeAccess($specialite);

        // Supprimer la spécialité
        $specialite->delete();

        return redirect()->route('specialites.index')->with('success', 'Specialty successfully removed.');
    }

    // Vérifier que l'utilisateur a accès à la spécialité
    private function authorizeAccess(Specialite $specialite)
    {
        if ($specialite->site_id !== Auth::user()->site_id) {
            abort(403, 'Accès interdit.');
        }
    }
}
