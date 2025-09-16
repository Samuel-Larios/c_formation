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
use Illuminate\Support\Facades\Storage;
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
            if (!empty($studentsInAnyPromotion)) {
                $studentsQuery->whereNotIn('id', $studentsInAnyPromotion);
            }
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

            if (!empty($studentsInPromotion)) {
                $studentsQuery->whereIn('id', $studentsInPromotion);
            } else {
                // Aucun étudiant dans cette promotion, forcer une requête qui ne retourne rien
                $studentsQuery->whereRaw('0 = 1');
            }
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

            if (!empty($studentsInSpecialite)) {
                $studentsQuery->whereIn('id', $studentsInSpecialite);
            } else {
                // Aucun étudiant dans cette spécialité, forcer une requête qui ne retourne rien
                $studentsQuery->whereRaw('0 = 1');
            }
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
        // $genderStats = Student::where('site_id', $siteId)
        //     ->selectRaw('sexe, COUNT(*) as count')
        //     ->groupBy('sexe')
        //     ->pluck('count', 'sexe')
        //     ->toArray();

        // $totalAllStudents = array_sum($genderStats);
        // $maleCountAll = $genderStats['M'] ?? 0;
        // $femaleCountAll = $genderStats['F'] ?? 0;

        // Dans la méthode index(), remplacez cette partie:
        $genderStats = Student::where('site_id', $siteId)
            ->selectRaw('sexe, COUNT(*) as count')
            ->groupBy('sexe')
            ->pluck('count', 'sexe')
            ->toArray();

        $totalAllStudents = array_sum($genderStats);
        $maleCountAll = $genderStats['M'] ?? 0;
        $femaleCountAll = $genderStats['F'] ?? 0;

        // Par cette partie (utilisez la même query que pour les étudiants filtrés):
        $filteredGenderStats = clone $studentsQuery;
        $genderStats = $filteredGenderStats->selectRaw('sexe, COUNT(*) as count')
            ->groupBy('sexe')
            ->pluck('count', 'sexe')
            ->toArray();

        $totalStudents = array_sum($genderStats);
        $maleCount = $genderStats['M'] ?? 0;
        $femaleCount = $genderStats['F'] ?? 0;


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
            'femaleCountAll',
            'totalStudents',  // Modifié
            'maleCount',      // Modifié
            'femaleCount'     // Modifié
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
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'state_of_origin' => 'required|string|max:255',
            'state_of_residence' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'lga' => 'nullable|string|max:255',
            'community' => 'nullable|string|max:255',
        ]);

        // Générer un mot de passe aléatoire
        $randomPassword = Str::random(10);

        // Gérer l'upload de la photo
        $profilePhotoPath = null;
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $fileName = time() . '_' . $request->first_name . '_' . $request->last_name . '.' . $file->getClientOriginalExtension();
            $profilePhotoPath = $file->storeAs('students/photos', $fileName, 'public');
        }

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
            'profile_photo' => $profilePhotoPath,
            'state_of_origin' => $request->state_of_origin,
            'state_of_residence' => $request->state_of_residence,
            'state' => $request->state,
            'lga' => $request->lga,
            'community' => $request->community,
            'site_id' => $siteId, // Associer automatiquement le site de l'utilisateur connecté
        ]);

        // Envoyer un email avec le mot de passe généré
        if ($student->email) {
            try {
                Mail::to($student->email)->send(new StudentPasswordMail($student, $randomPassword));
                $message = 'Étudiant créé avec succès et mot de passe envoyé par email.';
            } catch (\Exception $e) {
                $message = 'Étudiant créé avec succès. Le mot de passe n\'a pas pu être envoyé par email en raison d\'un problème de configuration.';
            }
        } else {
            $message = 'Étudiant créé avec succès. Aucun email fourni pour l\'envoi du mot de passe.';
        }

        // Retourner à la liste des étudiants avec un message de succès
        return redirect()->route('students.index')->with('success', $message);
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
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'state_of_origin' => 'required|string|max:255',
            'state_of_residence' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'lga' => 'nullable|string|max:255',
            'community' => 'nullable|string|max:255',
        ]);

        // Gérer l'upload de la photo
        $profilePhotoPath = $student->profile_photo; // Garder l'ancienne photo par défaut
        if ($request->hasFile('profile_photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($student->profile_photo && Storage::disk('public')->exists($student->profile_photo)) {
                Storage::disk('public')->delete($student->profile_photo);
            }

            $file = $request->file('profile_photo');
            $fileName = time() . '_' . $request->first_name . '_' . $request->last_name . '.' . $file->getClientOriginalExtension();
            $profilePhotoPath = $file->storeAs('students/photos', $fileName, 'public');
        }

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
            'profile_photo' => $profilePhotoPath,
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
            'format' => 'required|in:excel,word',
            'language' => 'required|in:fr,en',
        ]);

        // Récupérer le site_id de la session
        $siteId = session('site_id');

        // Construire la requête avec les filtres appliqués
        $studentsQuery = Student::with([
            'site',
            'specializations.specialite',
            'entities',
            'businessStatuses'
        ])->where('site_id', $siteId);

        // Appliquer les mêmes filtres que dans la méthode index
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $studentsQuery->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'LIKE', "%$searchTerm%")
                    ->orWhere('last_name', 'LIKE', "%$searchTerm%")
                    ->orWhere('email', 'LIKE', "%$searchTerm%")
                    ->orWhere('contact', 'LIKE', "%$searchTerm%");
            });
        }

        if ($request->has('sexe') && $request->sexe) {
            $studentsQuery->where('sexe', $request->sexe);
        }

        if ($request->has('promotion_id') && $request->promotion_id) {
            $promotionId = $request->promotion_id;
            $studentsInPromotion = PromotionApprenant::where('promotion_id', $promotionId)
                ->pluck('student_id')
                ->toArray();

            if (!empty($studentsInPromotion)) {
                $studentsQuery->whereIn('id', $studentsInPromotion);
            } else {
                $studentsQuery->whereRaw('0 = 1'); // Aucun étudiant dans cette promotion
            }
        } elseif ($request->has('promotion_id') && $request->promotion_id === '0') {
            $studentsInAnyPromotion = PromotionApprenant::pluck('student_id')->toArray();
            $studentsQuery->whereNotIn('id', $studentsInAnyPromotion);
        }

        if ($request->has('specialite_id') && $request->specialite_id) {
            $specialiteId = $request->specialite_id;
            $studentsInSpecialite = \App\Models\Specialization::where('specialite_id', $specialiteId)
                ->pluck('student_id')
                ->toArray();

            if (!empty($studentsInSpecialite)) {
                $studentsQuery->whereIn('id', $studentsInSpecialite);
            } else {
                $studentsQuery->whereRaw('0 = 1'); // Aucun étudiant dans cette spécialité
            }
        }

        if ($request->has('state_of_origin') && $request->state_of_origin) {
            $studentsQuery->where('state_of_origin', $request->state_of_origin);
        }

        // Vérifier si des étudiants existent avec ces filtres
        $count = $studentsQuery->count();

        if ($count === 0) {
            return back()->with('error', 'No students found with the applied filters');
        }

        if ($request->format === 'word') {
            try {
                // Pour l'export Word, nous devons passer les filtres à l'exporter
                $exporter = new \App\Exports\StudentsWordExport($siteId, $request->all(), $request->language);
                return $exporter->export();
            } catch (\Exception $e) {
                return back()->with('error', 'Error during Word export: ' . $e->getMessage() . '. PHP zip extension must be enabled.');
            }
        } else {
            $fileName = 'students_' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(
                new StudentsExport($siteId, $request->all(), $request->language),
                $fileName
            );
        }
    }


    // Importer les étudiants
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        // Vérifier si l'extension zip est disponible pour les fichiers .xlsx
        if ($extension === 'xlsx' && !class_exists('ZipArchive')) {
            return redirect()->route('students.export-import')->with(
                'error',
                'Erreur lors de l\'importation : L\'extension ZipArchive de PHP n\'est pas activée. ' .
                    'Pour importer des fichiers Excel (.xlsx), vous devez activer l\'extension zip dans votre configuration PHP. ' .
                    'Vous pouvez également utiliser le format .xls (Excel 97-2003) qui ne nécessite pas cette extension. ' .
                    'Consultez le fichier check_php_extensions.php pour plus d\'informations.'
            );
        }

        try {
            Excel::import(new StudentsImport, $file);
            return redirect()->route('students.export-import')->with('success', 'Étudiants importés avec succès.');
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            // Gestion spécifique des erreurs courantes
            if (strpos($errorMessage, 'ZipArchive') !== false || strpos($errorMessage, 'zip') !== false) {
                return redirect()->route('students.export-import')->with(
                    'error',
                    'Erreur lors de l\'importation : Problème avec l\'extension ZipArchive. ' .
                        'Utilisez le format .xls (Excel 97-2003) ou activez l\'extension zip dans PHP.'
                );
            }

            if (strpos($errorMessage, 'spreadsheet') !== false || strpos($errorMessage, 'excel') !== false) {
                return redirect()->route('students.export-import')->with(
                    'error',
                    'Erreur lors de l\'importation : Format de fichier non reconnu. ' .
                        'Assurez-vous que le fichier est un fichier Excel valide (.xlsx ou .xls).'
                );
            }

            return redirect()->route('students.export-import')->with('error', 'Erreur lors de l\'importation : ' . $errorMessage);
        }
    }

    // Importer les étudiants depuis un fichier CSV (méthode alternative)
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $data = array_map('str_getcsv', file($file->getRealPath()));

        $imported = 0;
        $errors = [];

        foreach ($data as $key => $row) {
            // Skip header row
            if ($key === 0 || $row[0] == 'ID' || empty($row[1])) {
                continue;
            }

            try {
                // Find site by designation
                $site = Site::where('designation', $row[20])->first();
                if (!$site) {
                    $errors[] = "Ligne " . ($key + 1) . ": Site '{$row[20]}' non trouvé";
                    continue;
                }

                // Find promotion by num_promotion if provided
                $promotion = null;
                if (!empty($row[21])) {
                    $promotion = Promotion::where('num_promotion', $row[21])->first();
                }

                // Generate password if not provided
                $password = !empty($row[14]) ? Hash::make($row[14]) : Hash::make(Str::random(10));

                $studentData = [
                    'first_name' => $row[1],
                    'last_name' => $row[2],
                    'sexe' => $row[3],
                    'situation_matrimoniale' => $row[4],
                    'situation_handicape' => $row[5],
                    'date_naissance' => $row[6] ? date('Y-m-d', strtotime($row[6])) : null,
                    'contact' => $row[7],
                    'contact_pers1' => $row[8],
                    'contact_pers2' => $row[9],
                    'contact_pers3' => $row[10],
                    'contact_pers4' => $row[11],
                    'contact_pers5' => $row[12],
                    'email' => $row[13],
                    'password' => $password,
                    'state_of_origin' => $row[15],
                    'state_of_residence' => $row[16],
                    'state' => $row[17],
                    'lga' => $row[18],
                    'community' => $row[19],
                    'site_id' => $site->id,
                ];

                $student = Student::create($studentData);

                // If promotion found, associate student with promotion
                if ($promotion) {
                    $student->promotions()->attach($promotion->id);
                }

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Ligne " . ($key + 1) . ": " . $e->getMessage();
            }
        }

        $message = "Import terminé : $imported étudiants importés.";
        if (!empty($errors)) {
            $message .= " Erreurs : " . implode(', ', $errors);
        }

        return redirect()->route('students.export-import')->with('success', $message);
    }

    // Afficher la page d'export/import
    public function exportImport()
    {
        $siteId = session('site_id');
        $promotions = Promotion::where('site_id', $siteId)->get();

        return view('students.export-import', compact('promotions'));
    }

    // Afficher la page de gestion des mots de passe
    public function passwords()
    {
        return view('students.passwords');
    }



    // Méthode de test pour vérifier les données
    public function testData()
    {
        $siteId = session('site_id');

        $data = [
            'sites_count' => \App\Models\Site::count(),
            'promotions_count' => \App\Models\Promotion::count(),
            'students_count' => \App\Models\Student::count(),
            'sites' => \App\Models\Site::take(3)->get(['id', 'designation']),
            'promotions' => \App\Models\Promotion::where('site_id', $siteId)->take(3)->get(['id', 'num_promotion']),
            'students' => \App\Models\Student::with(['site', 'promotions'])->where('site_id', $siteId)->take(3)->get(['id', 'first_name', 'last_name', 'site_id'])
        ];

        return response()->json($data);
    }
}
