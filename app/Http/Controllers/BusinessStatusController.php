<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\BusinessStatus;
use Illuminate\Support\Facades\Auth;


class BusinessStatusController extends Controller
{
    // Afficher la liste des statuts d'entreprise
    public function index()
    {
        $businessStatuses = BusinessStatus::with(['student', 'site'])
                                         ->orderBy('created_at', 'desc')
                                         ->paginate(10);
        return view('business_status.index', compact('businessStatuses'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Récupérer les étudiants du site de l'utilisateur connecté
        $students = Student::where('site_id', $user->site_id)->get();

        // Récupérer tous les sites (si nécessaire)
        $sites = Site::all();

        return view('business_status.create', compact('students', 'sites'));
    }

    // Enregistrer un nouveau statut d'entreprise
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'type_of_business' => 'required|string',
            'status' => 'required|in:Registered,Non registered,Cooperative',
            'student_id' => 'required|exists:students,id',
        ]);


        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Ajouter le site_id de l'utilisateur connecté aux données
        $data = $request->all();
        $data['site_id'] = $user->site_id;

        // Créer un nouveau statut d'entreprise
        BusinessStatus::create($data);

        return redirect()->route('business_status.index')->with('success', 'Company status successfully created.');
    }

    // Afficher les détails d'un statut d'entreprise
    public function show($id)
    {
        $businessStatus = BusinessStatus::findOrFail($id);
        return view('business_status.show', compact('businessStatus'));
    }

    // Afficher le formulaire de modification
    public function edit($id)
    {
        $businessStatus = BusinessStatus::findOrFail($id);
        $students = Student::all();
        $sites = Site::all();
        return view('business_status.edit', compact('businessStatus', 'students', 'sites'));
    }

    // Mettre à jour un statut d'entreprise
    public function update(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'type_of_business' => 'required|string',
            'status' => 'required|in:Registered,Non registered,Cooperative',
            'student_id' => 'required|exists:students,id',
        ]);


        // Mettre à jour le statut d'entreprise
        $businessStatus = BusinessStatus::findOrFail($id);
        $businessStatus->update($request->all());

        return redirect()->route('business_status.index')->with('success', 'Company status successfully updated.');
    }

    // Supprimer un statut d'entreprise
    public function destroy($id)
    {
        $businessStatus = BusinessStatus::findOrFail($id);
        $businessStatus->delete();

        return redirect()->route('business_status.index')->with('success', 'Company status successfully deleted.');
    }
}
