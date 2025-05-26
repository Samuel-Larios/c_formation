<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UtilisateurCreated;

class UtilisateurController extends Controller
{
    // Affiche la liste des utilisateurs
    public function index()
    {
        $utilisateurs = Utilisateur::with('site')->latest()->paginate(10);
        return view('utilisateurs.index', compact('utilisateurs'));
    }

    // Affiche le formulaire pour créer un nouvel utilisateur
    public function create()
    {
        $sites = Site::all();
        return view('utilisateurs.create', compact('sites'));
    }

    // Enregistre un nouvel utilisateur
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'tel' => 'required|string|max:15',
            'role' => 'required|string|in:admin_principal,user',
            'email' => 'required|email|unique:utilisateurs,email',
            'password' => 'required|string|min:8',
            'site_id' => 'required|exists:sites,id',
        ]);

        // Créer l'utilisateur
        $utilisateur = Utilisateur::create([
            'name' => $validated['name'],
            'date_naissance' => $validated['date_naissance'],
            'tel' => $validated['tel'],
            'tel2' => $request->input('tel2'),
            'poste' => $request->input('poste'),
            'role' => $validated['role'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Hasher le mot de passe
            'site_id' => $validated['site_id'],
        ]);

        // Envoyer un email avec les informations de l'utilisateur
        Mail::to($utilisateur->email)->send(new UtilisateurCreated($utilisateur, $validated['password']));

        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur créé avec succès');
    }

    // Affiche le formulaire pour modifier un utilisateur existant
    public function edit($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        $sites = Site::all();
        return view('utilisateurs.edit', compact('utilisateur', 'sites'));
    }

    // Met à jour un utilisateur existant
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'tel' => 'required|string|max:15',
            'role' => 'required|string|in:admin_principal,user',
            'email' => 'required|email|unique:utilisateurs,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'site_id' => 'required|exists:sites,id',
        ]);

        $utilisateur = Utilisateur::findOrFail($id);

        $utilisateur->update([
            'name' => $validated['name'],
            'date_naissance' => $validated['date_naissance'],
            'tel' => $validated['tel'],
            'tel2' => $request->input('tel2'),
            'poste' => $request->input('poste'),
            'role' => $validated['role'],
            'email' => $validated['email'],
            'site_id' => $validated['site_id'],
        ]);

        if ($request->filled('password')) {
            $utilisateur->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return redirect()->route('utilisateurs.index')->with('success', 'User successfully updated');
    }

    // Supprime un utilisateur
    public function destroy($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        $utilisateur->delete();

        return redirect()->route('utilisateurs.index')->with('success', 'User successfully deleted');
    }
}
