<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Student;
use App\Models\Promotion;
use App\Models\Specialite;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use App\Mail\StudentPasswordMail;
use App\Models\PromotionApprenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    // Afficher la liste des étudiants
    public function index(Request $request)
    {
        $siteId = session('site_id');

        $studentsQuery = Student::with([
            'site',
            'specializations.specialite',
            'entities',
            'businessStatuses'
        ])->where('site_id', $siteId)
            ->orderBy('first_name');

        // Par défaut, afficher uniquement les étudiants non inscrits dans une promotion
        if (
            !$request->has('search') &&
            !$request->has('promotion_id') &&
            !$request->has('specialite_id') &&
            !$request->has('state_of_origin') &&
            !$request->has('sexe')
        ) {
            $studentsInAnyPromotion = PromotionApprenant::pluck('student_id')->toArray();
            $studentsQuery->whereNotIn('id', $studentsInAnyPromotion);
        }

        // Recherche par nom, email ou téléphone
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $studentsQuery->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'LIKE', "%$searchTerm%")
                    ->orWhere('last_name', 'LIKE', "%$searchTerm%")
                    ->orWhere('email', 'LIKE', "%$searchTerm%")
                    ->orWhere('contact', 'LIKE', "%$searchTerm%");
            });
        }

        // Initialize filter variables
        $selectedPromotionName = null;
        $selectedSpecialiteName = null;
        $selectedState = null;
        $selectedSexe = null;
        $filterByPromotion = false;

        // Filter by gender if provided
        if ($request->has('sexe') && $request->sexe) {
            $selectedSexe = $request->sexe;
            $studentsQuery->where('sexe', $selectedSexe);
        }

        // Filter by promotion if provided
        if ($request->has('promotion_id') && $request->promotion_id) {
            $promotionId = $request->promotion_id;
            $filterByPromotion = true;

            $selectedPromotion = Promotion::find($promotionId);
            $selectedPromotionName = $selectedPromotion ? $selectedPromotion->num_promotion : null;

            $studentsInPromotion = PromotionApprenant::where('promotion_id', $promotionId)
                ->pluck('student_id')
                ->toArray();

            $studentsQuery->whereIn('id', $studentsInPromotion);
        } elseif ($request->has('promotion_id') && $request->promotion_id === '0') {
            // Only show students not in any promotion if explicitly requested
            $studentsInAnyPromotion = PromotionApprenant::pluck('student_id')->toArray();
            $studentsQuery->whereNotIn('id', $studentsInAnyPromotion);
        }

        // Filter by speciality if provided
        if ($request->has('specialite_id') && $request->specialite_id) {
            $specialiteId = $request->specialite_id;
            $selectedSpecialite = Specialite::find($specialiteId);
            $selectedSpecialiteName = $selectedSpecialite ? $selectedSpecialite->designation : null;

            $studentsInSpecialite = Specialization::where('specialite_id', $specialiteId)
                ->pluck('student_id')
                ->toArray();

            $studentsQuery->whereIn('id', $studentsInSpecialite);
        }

        // Filter by state_of_origin if provided
        if ($request->has('state_of_origin') && $request->state_of_origin) {
            $selectedState = $request->state_of_origin;
            $studentsQuery->where('state_of_origin', $selectedState);
        }

        $students = $studentsQuery->paginate(30)->appends(request()->query());

        $user = Auth::user();
        $site = $user->site;

        $promotions = Promotion::where('site_id', $site->id)->get();
        $specialites = Specialite::where('site_id', $site->id)->get();

        // Get unique states of origin for filter dropdown
        $statesOfOrigin = Student::where('site_id', $siteId)
            ->select('state_of_origin')
            ->distinct()
            ->orderBy('state_of_origin')
            ->pluck('state_of_origin');

        // Get gender statistics for all students (without current filters)
        $genderStats = Student::where('site_id', $siteId)
            ->selectRaw('sexe, COUNT(*) as count')
            ->groupBy('sexe')
            ->pluck('count', 'sexe')
            ->toArray();

        $totalAllStudents = array_sum($genderStats);
        $maleCountAll = $genderStats['M'] ?? 0;
        $femaleCountAll = $genderStats['F'] ?? 0;

        return view('students.index', compact(
            'students',
            'promotions',
            'specialites',
            'statesOfOrigin',
            'selectedPromotionName',
            'selectedSpecialiteName',
            'selectedState',
            'selectedSexe',
            'site',
            'filterByPromotion',
            'totalAllStudents',
            'maleCountAll',
            'femaleCountAll'
        ));
    }

    public function search(Request $request)
    {
        $siteId = session('site_id');
        $studentsQuery = Student::with(['site', 'specializations.specialite', 'entities', 'businessStatuses'])
            ->where('site_id', $siteId);

        // Recherche unifiée
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $studentsQuery->where(function ($query) use ($searchTerm) {
                $query->where('first_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('contact', 'like', '%' . $searchTerm . '%');
            });
        }

        $students = $studentsQuery->paginate(30)->appends($request->query());

        $promotions = Promotion::where('site_id', $siteId)->get();
        $specialites = Specialite::where('site_id', $siteId)->get();
        $statesOfOrigin = Student::where('site_id', $siteId)
            ->select('state_of_origin')
            ->distinct()
            ->orderBy('state_of_origin')
            ->pluck('state_of_origin');

        return view('students.search_results', compact('students', 'promotions', 'specialites', 'statesOfOrigin'));
    }




    // Afficher le formulaire de création d'un étudiant
    public function create()
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = session('site_id');

        // Récupérer uniquement le site de l'utilisateur connecté
        $site = Site::findOrFail($siteId);

        // Passer le site à la vue
        return view('students.create', compact('site'));
    }

    // Enregistrer un nouvel étudiant
    public function store(Request $request)
    {
        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = session('site_id');

        // Validation des données envoyées
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'sexe' => 'required|string|max:10',
            'situation_matrimoniale' => 'nullable|string|max:255',
            'situation_handicape' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'contact' => 'nullable|string|max:20',
            'contact_pers1' => 'nullable|string|max:20',
            'contact_pers2' => 'nullable|string|max:20',
            'contact_pers3' => 'nullable|string|max:20',
            'contact_pers4' => 'nullable|string|max:20',
            'contact_pers5' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:students,email',
            'state_of_origin' => 'required|string|max:255',
            'state_of_residence' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'lga' => 'nullable|string|max:255',
            'community' => 'nullable|string|max:255',
        ]);

        // Générer un mot de passe aléatoire
        $randomPassword = Str::random(10);

        // Créer l'étudiant
        $student = Student::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'sexe' => $request->sexe,
            'situation_matrimoniale' => $request->situation_matrimoniale,
            'situation_handicape' => $request->situation_handicape,
            'date_naissance' => $request->date_naissance,
            'contact' => $request->contact,
            'contact_pers1' => $request->contact_pers1,
            'contact_pers2' => $request->contact_pers2,
            'contact_pers3' => $request->contact_pers3,
            'contact_pers4' => $request->contact_pers4,
            'contact_pers5' => $request->contact_pers5,
            'email' => $request->email,
            'password' => Hash::make($randomPassword), // Stocker le mot de passe hashé
            'state_of_origin' => $request->state_of_origin,
            'state_of_residence' => $request->state_of_residence,
            'state' => $request->state,
            'lga' => $request->lga,
            'community' => $request->community,
            'site_id' => $siteId, // Associer automatiquement le site de l'utilisateur connecté
        ]);

        // Envoyer un email avec le mot de passe généré
        if ($student->email) {
            Mail::to($student->email)->send(new StudentPasswordMail($student, $randomPassword));
        }

        // Retourner à la liste des étudiants avec un message de succès
        return redirect()->route('students.index')->with('success', 'Student successfully created and password sent.');
    }






    // Afficher les détails d'un étudiant
    public function show($id)
    {
        // Récupérer l'étudiant par son ID
        $student = Student::with('site')->findOrFail($id);

        // Retourner la vue avec les détails de l'étudiant
        return view('students.show', compact('student'));
    }

    // Afficher le formulaire d'édition d'un étudiant
    public function edit(Student $student)
    {
        $sites = Site::all();
        return view('students.edit', compact('student', 'sites'));
    }

    // Mettre à jour les informations d'un étudiant
    public function update(Request $request, Student $student)
    {
        // Vérifier si l'étudiant appartient au site de l'utilisateur connecté
        if ($student->site_id != session('site_id')) {
            abort(403, 'Accès non autorisé.'); // Retourner une erreur 403 si l'étudiant n'appartient pas au site
        }

        // Validation des données envoyées
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'sexe' => 'required|string|max:10',
            'situation_matrimoniale' => 'nullable|string|max:255',
            'situation_handicape' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'contact' => 'nullable|string|max:20',
            'contact_pers1' => 'nullable|string|max:20',
            'contact_pers2' => 'nullable|string|max:20',
            'contact_pers3' => 'nullable|string|max:20',
            'contact_pers4' => 'nullable|string|max:20',
            'contact_pers5' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:students,email,' . $student->id,
            'state_of_origin' => 'required|string|max:255',
            'state_of_residence' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'lga' => 'nullable|string|max:255',
            'community' => 'nullable|string|max:255',
        ]);

        // Mettre à jour l'étudiant avec les nouvelles données
        $student->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'sexe' => $request->sexe,
            'situation_matrimoniale' => $request->situation_matrimoniale,
            'situation_handicape' => $request->situation_handicape,
            'date_naissance' => $request->date_naissance,
            'contact' => $request->contact,
            'contact_pers1' => $request->contact_pers1,
            'contact_pers2' => $request->contact_pers2,
            'contact_pers3' => $request->contact_pers3,
            'contact_pers4' => $request->contact_pers4,
            'contact_pers5' => $request->contact_pers5,
            'email' => $request->email,
            'state_of_origin' => $request->state_of_origin,
            'state_of_residence' => $request->state_of_residence,
            'state' => $request->state,
            'lga' => $request->lga,
            'community' => $request->community,
        ]);

        // Retourner à la liste des étudiants avec un message de succès
        return redirect()->route('students.index')->with('success', 'Student successfully updated.');
    }

    // Supprimer un étudiant
    public function destroy(Student $student)
    {
        // Supprimer l'étudiant de la base de données
        $student->delete();

        // Retourner à la liste des étudiants avec un message de succès
        return redirect()->route('students.index')->with('success', 'Student successfully deleted.');
    }





    // Méthode pour exporter les étudiants
    public function export(Request $request)
    {
        $request->validate([
            'promotion_id' => 'required|exists:promotions,id',
        ]);

        // Récupérer le site_id de la session plutôt que du formulaire
        $siteId = session('site_id');

        // Vérifier si des étudiants existent pour cette promotion
        $count = PromotionApprenant::where('promotion_id', $request->promotion_id)
            ->whereHas('student', function ($q) use ($siteId) {
                $q->where('site_id', $siteId);
            })
            ->count();

        if ($count === 0) {
            return back()->with('error', 'No students found for this promotion');
        }

        $fileName = 'students_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new StudentsExport($siteId, $request->promotion_id),
            $fileName
        );
    }


    // Importer les étudiants
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new StudentsImport, $request->file('file'));

        return redirect()->route('students.index')->with('success', 'Étudiants importés avec succès.');
    }
}
