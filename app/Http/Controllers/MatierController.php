<?php

namespace App\Http\Controllers;

use App\Models\Matier;
use App\Models\Site;
use Illuminate\Http\Request;

class MatierController extends Controller
{
    // Afficher la liste des matières
    public function index()
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = session('site_id');

        // Filtrer les matières en fonction du site_id
        $matieres = Matier::with('site')
            ->where('site_id', $siteId) // Filtrer par site_id
            ->latest()
            ->paginate(10);

        return view('matieres.index', compact('matieres'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = session('site_id');

        // Récupérer uniquement le site de l'utilisateur connecté
        $site = Site::findOrFail($siteId);

        // Passer le site à la vue
        return view('matieres.create', compact('site'));
    }

    // Enregistrer une nouvelle matière
    public function store(Request $request)
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = session('site_id');

        // Validation des données
        $validated = $request->validate([
            'designation' => 'required|string|max:255',
            'coef' => 'required|integer|min:1',
        ]);

        // Ajouter le site_id aux données validées
        $validated['site_id'] = $siteId;

        // Créer la matière
        Matier::create($validated);

        return redirect()->route('matieres.index')->with('success', 'Matter successfully created');
    }

    // Afficher le formulaire d'édition
    public function edit(Matier $matier)
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = session('site_id');

        // Vérifier que la matière appartient au site de l'utilisateur connecté
        if ($matier->site_id != $siteId) {
            abort(403, 'Accès non autorisé.');
        }

        // Récupérer uniquement le site de l'utilisateur connecté
        $site = Site::findOrFail($siteId);

        return view('matieres.edit', compact('matier', 'site'));
    }

    // Mettre à jour une matière
    public function update(Request $request, Matier $matier)
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = session('site_id');

        // Vérifier que la matière appartient au site de l'utilisateur connecté
        if ($matier->site_id != $siteId) {
            abort(403, 'Accès non autorisé.');
        }

        // Validation des données
        $validated = $request->validate([
            'designation' => 'required|string|max:255',
            'coef' => 'required|integer|min:1',
        ]);

        // Mettre à jour la matière
        $matier->update($validated);

        return redirect()->route('matieres.index')->with('success', 'Material successfully updated');
    }

    // Supprimer une matière
    public function destroy(Matier $matier)
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = session('site_id');

        // Vérifier que la matière appartient au site de l'utilisateur connecté
        if ($matier->site_id != $siteId) {
            abort(403, 'Accès non autorisé.');
        }

        // Supprimer la matière
        $matier->delete();

        return redirect()->route('matieres.index')->with('success', 'Material successfully deleted');
    }
}
