<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Affiche la liste des sites.
     */
    public function index()
    {
        $sites = Site::all();
        return view('sites.index', compact('sites'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        return view('sites.create');
    }

    /**
     * Enregistre un nouveau site.
     */
    public function store(Request $request)
    {
        $request->validate([
            'designation' => 'required|string|max:255',
            'emplacement' => 'required|string|max:255',
        ]);

        Site::create($request->all());

        return redirect()->route('sites.index')->with('success', 'Site added successfully.');
    }

    //  Affiche un site spécifique.
    public function show(Site $site)
    {
        return view('sites.show', compact('site'));
    }

    //  Affiche le formulaire d'édition.
    public function edit(Site $site)
    {
        return view('sites.edit', compact('site'));
    }

    /**
     * Met à jour un site existant.
     */
    public function update(Request $request, Site $site)
    {
        $request->validate([
            'designation' => 'required|string|max:255',
            'emplacement' => 'required|string|max:255',
        ]);

        $site->update($request->all());

        return redirect()->route('sites.index')->with('success', 'Site successfully updated.');
    }

    //  Supprime un site.

    public function destroy(Site $site)
    {
        $site->delete();
        return redirect()->route('sites.index')->with('success', 'Site deleted successfully.');
    }

}
