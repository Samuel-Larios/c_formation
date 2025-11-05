<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Site;
use App\Models\Student;
use App\Models\Promotion;
use App\Models\Subvention;
use App\Models\Salary;
use App\Models\JobCreation;
use App\Models\FollowUp;
use Illuminate\Http\Request;
use Illuminate\Container\Attributes;

class StudentStatisticsController extends Controller
{

    //  * Afficher la page des statistiques des étudiants.
    public function index()
    {
        $sites = Site::all();
        return view('studentstatistics.index', compact('sites'));
    }


    //  * Récupérer les promotions d'un site (AJAX).
    // public function getPromotions(Request $request)
    // {
    //     $siteId = $request->input('site_id');
    //     $promotions = Promotion::where('site_id', $siteId)->get();
    //     return response()->json($promotions);
    // }


    // //   Récupérer les étudiants d'une promotion (AJAX).
    // public function getStudents(Request $request)
    // {
    //     $promotionId = $request->input('promotion_id');
    //     $students = Student::whereHas('promotions', function ($query) use ($promotionId) {
    //         $query->where('promotion_id', $promotionId);
    //     })->get();
    //     return response()->json($students);
    // }

    // Dans StudentStatisticsController.php

    public function getPromotions($siteId)
    {
        return response()->json(
            Promotion::where('site_id', $siteId)
                ->select('id', 'num_promotion as text')
                ->get()
        );
    }

    public function getStudents($promotionId)
    {
        return response()->json(
            Student::whereHas('promotions', function ($query) use ($promotionId) {
                $query->where('promotion_id', $promotionId);
            })
                ->select('id', \DB::raw('CONCAT(first_name, " ", last_name) as text'))
                ->get()
        );
    }

    //  * Afficher les détails d'un étudiant.
    public function showStudent($id)
    {
        $student = Student::findOrFail($id);

        $specialites = DB::table('specializations')
            ->join('specialites', 'specializations.specialite_id', '=', 'specialites.id')
            ->where('specializations.student_id', $id)
            ->select('specialites.designation')
            ->get();

        $evaluations = DB::table('evaluations')
            ->join('matiers', 'evaluations.matier_id', '=', 'matiers.id')
            ->where('evaluations.student_id', $id)
            ->select('matiers.designation', 'evaluations.note')
            ->get();

        $jobCreations = DB::table('job_creations')->where('student_id', $id)->get();
        $salaries = DB::table('salaries')->where('student_id', $id)->get();
        $subventions = \Illuminate\Support\Facades\DB::table('subvention')->where('student_id', $id)->get();
        $followUps = DB::table('follow_up')->where('student_id', $id)->get();
        $businessStatuses = DB::table('business_status')->where('student_id', $id)->get();
        $entities = DB::table('entities')->where('student_id', $id)->get();

        return view('studentstatistics.show', compact(
            'student',
            'specialites',
            'evaluations',
            'jobCreations',
            'salaries',
            'subventions',
            'followUps',
            'businessStatuses',
            'entities'
        ));
    }

    //  * Imprimer les détails d'un étudiant.
    public function printStudent($id)
    {
        $student = Student::with([
            'site',
            'promotions',
            'evaluations.matier',
            'specializations.specialite',
            'jobCreations',
            'salaries',
            'subventions',
            'followUps',
            'businessStatuses',
            'entities'
        ])->findOrFail($id);

        return view('studentstatistics.print', compact('student'));
    }

    //  * Exporter les étudiants filtrés en Excel.
    public function exportFilteredStudents(Request $request)
    {
        $query = Student::with(['site', 'promotions', 'specializations.specialite']);

        // Filtrer par site si spécifié
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Filtrer par promotion si spécifiée
        if ($request->filled('promotion_id')) {
            $promotionNum = $request->promotion_id;
            $promotionIds = Promotion::where('num_promotion', $promotionNum)->pluck('id');
            $query->whereHas('promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        // Filtrer par spécialisation si spécifiée
        if ($request->filled('specialite_id')) {
            $specialiteId = $request->specialite_id;
            $studentsInSpecialite = \App\Models\Specialization::where('specialite_id', $specialiteId)
                ->pluck('student_id')
                ->toArray();

            if (!empty($studentsInSpecialite)) {
                $query->whereIn('id', $studentsInSpecialite);
            } else {
                $query->whereRaw('0 = 1'); // Aucun étudiant dans cette spécialité
            }
        }

        // Filtrer par sexe si spécifié
        if ($request->filled('sexe')) {
            $query->where('sexe', $request->sexe);
        }

        $students = $query->get();

        $fileName = 'etudiants_filtres_' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StudentsFilteredExport($students), $fileName);
    }

    //  * Afficher le formulaire de filtrage des subventions.
    public function subventionsForm()
    {
        $sites = Site::all();
        return view('studentstatistics.subventions', compact('sites'));
    }

    //  * Filtrer les subventions.
    public function filterSubventions(Request $request)
    {
        $query = Subvention::with(['student.site', 'student.promotions']);

        // Filtrer par site si spécifié
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Filtrer par promotion si spécifiée (par numéro de promotion)
        if ($request->filled('promotion_id')) {
            $promotionNum = $request->promotion_id;
            $promotionIds = Promotion::where('num_promotion', $promotionNum)->pluck('id');
            $query->whereHas('student.promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        $subventions = $query->paginate(10);

        return response()->json($subventions);
    }

    //  * Exporter les subventions filtrées en Excel.
    public function exportFilteredSubventions(Request $request)
    {
        $query = Subvention::with(['student.site', 'student.promotions']);

        // Filtrer par site si spécifié
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Filtrer par promotion si spécifiée (par numéro de promotion)
        if ($request->filled('promotion_id')) {
            $promotionNum = $request->promotion_id;
            $promotionIds = Promotion::where('num_promotion', $promotionNum)->pluck('id');
            $query->whereHas('student.promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        $subventions = $query->get();

        $fileName = 'subventions_filtrees_' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SubventionsExport($request->site_id, $request->promotion_id), $fileName);
    }

    //  * Afficher le formulaire de filtrage des salaires.
    public function salariesForm()
    {
        $sites = Site::all();
        return view('studentstatistics.salaries', compact('sites'));
    }

    //  * Filtrer les salaires.
    public function filterSalaries(Request $request)
    {
        $query = Salary::with(['student.site', 'student.promotions']);

        // Filtrer par site si spécifié
        if ($request->filled('site_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('site_id', $request->site_id);
            });
        }

        // Filtrer par promotion si spécifiée (par numéro de promotion)
        if ($request->filled('promotion_id')) {
            $promotionNum = $request->promotion_id;
            $promotionIds = Promotion::where('num_promotion', $promotionNum)->pluck('id');
            $query->whereHas('student.promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        $salaries = $query->paginate(10);

        return response()->json($salaries);
    }

    //  * Exporter les salaires filtrés en Excel.
    public function exportFilteredSalaries(Request $request)
    {
        $query = Salary::with(['student.site', 'student.promotions']);

        // Filtrer par site si spécifié
        if ($request->filled('site_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('site_id', $request->site_id);
            });
        }

        // Filtrer par promotion si spécifiée (par numéro de promotion)
        if ($request->filled('promotion_id')) {
            $promotionNum = $request->promotion_id;
            $promotionIds = Promotion::where('num_promotion', $promotionNum)->pluck('id');
            $query->whereHas('student.promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        $salaries = $query->get();

        $fileName = 'salaires_filtres_' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SalariesExport($request->site_id, $request->promotion_id), $fileName);
    }

    //  * Afficher le formulaire de filtrage des jobs créés.
    public function jobCreationsForm()
    {
        $sites = Site::all();
        return view('studentstatistics.job-creations', compact('sites'));
    }

    //  * Filtrer les jobs créés.
    public function filterJobCreations(Request $request)
    {
        $query = JobCreation::with(['student.site', 'student.promotions']);

        // Filtrer par site si spécifié
        if ($request->filled('site_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('site_id', $request->site_id);
            });
        }

        // Filtrer par promotion si spécifiée (par numéro de promotion)
        if ($request->filled('promotion_id')) {
            $promotionNum = $request->promotion_id;
            $promotionIds = Promotion::where('num_promotion', $promotionNum)->pluck('id');
            $query->whereHas('student.promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        $jobCreations = $query->paginate(10);

        return response()->json($jobCreations);
    }

    //  * Exporter les jobs créés filtrés en Excel.
    public function exportFilteredJobCreations(Request $request)
    {
        $query = JobCreation::with(['student.site', 'student.promotions']);

        // Filtrer par site si spécifié
        if ($request->filled('site_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('site_id', $request->site_id);
            });
        }

        // Filtrer par promotion si spécifiée (par numéro de promotion)
        if ($request->filled('promotion_id')) {
            $promotionNum = $request->promotion_id;
            $promotionIds = Promotion::where('num_promotion', $promotionNum)->pluck('id');
            $query->whereHas('student.promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        $jobCreations = $query->get();

        $fileName = 'jobs_crees_filtres_' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\JobCreationsExport($request->site_id, $request->promotion_id), $fileName);
    }

    //  * Afficher le formulaire de filtrage des étudiants par état d'origine.
    public function stateOfOriginForm()
    {
        $sites = Site::all();
        $statesOfOrigin = Student::distinct()->pluck('state_of_origin')->filter()->sort();
        return view('studentstatistics.state-of-origin', compact('sites', 'statesOfOrigin'));
    }

    //  * Filtrer les étudiants par état d'origine.
    public function filterStudentsByStateOfOrigin(Request $request)
    {
        $query = Student::with(['site', 'promotions']);

        // Filtrer par site si spécifié
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Filtrer par promotion si spécifiée (par numéro de promotion)
        if ($request->filled('promotion_id')) {
            $promotionNum = $request->promotion_id;
            $promotionIds = Promotion::where('num_promotion', $promotionNum)->pluck('id');
            $query->whereHas('promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        // Filtrer par état d'origine si spécifié
        if ($request->filled('state_of_origin')) {
            $query->where('state_of_origin', $request->state_of_origin);
        }

        $students = $query->paginate(10);

        return response()->json($students);
    }

    //  * Exporter les étudiants par état d'origine filtrés en Excel.
    public function exportFilteredStudentsByStateOfOrigin(Request $request)
    {
        $query = Student::with(['site', 'promotions']);

        // Filtrer par site si spécifié
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Filtrer par promotion si spécifiée (par numéro de promotion)
        if ($request->filled('promotion_id')) {
            $promotionNum = $request->promotion_id;
            $promotionIds = Promotion::where('num_promotion', $promotionNum)->pluck('id');
            $query->whereHas('promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        // Filtrer par état d'origine si spécifié
        if ($request->filled('state_of_origin')) {
            $query->where('state_of_origin', $request->state_of_origin);
        }

        $students = $query->get();

        $fileName = 'etudiants_par_etat_origine_' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StudentsByStateOfOriginExport($request->site_id, $request->promotion_id, $request->state_of_origin), $fileName);
    }

    //  * Afficher le formulaire de filtrage des follow-ups.
    public function followUpsForm()
    {
        $sites = Site::all();
        return view('studentstatistics.follow-ups', compact('sites'));
    }

    //  * Filtrer les follow-ups.
    public function filterFollowUps(Request $request)
    {
        $query = FollowUp::with(['student.site', 'student.promotions']);

        // Filtrer par site si spécifié
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Filtrer par promotion si spécifiée (par numéro de promotion)
        if ($request->filled('promotion_id')) {
            $promotionNum = $request->promotion_id;
            $promotionIds = Promotion::where('num_promotion', $promotionNum)->pluck('id');
            $query->whereHas('student.promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        $followUps = $query->paginate(10);

        return response()->json($followUps);
    }

    //  * Exporter les follow-ups filtrés en Excel.
    public function exportFilteredFollowUps(Request $request)
    {
        $query = FollowUp::with(['student.site', 'student.promotions']);

        // Filtrer par site si spécifié
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Filtrer par promotion si spécifiée (par numéro de promotion)
        if ($request->filled('promotion_id')) {
            $promotionNum = $request->promotion_id;
            $promotionIds = Promotion::where('num_promotion', $promotionNum)->pluck('id');
            $query->whereHas('student.promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        $followUps = $query->get();

        $fileName = 'follow_ups_filtres_' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\FollowUpsExport($request->site_id, $request->promotion_id), $fileName);
    }

    //  * Afficher le formulaire de filtrage des entrepreneurs.
    public function entrepreneursForm()
    {
        $sites = Site::all();
        return view('studentstatistics.entrepreneurs', compact('sites'));
    }

    //  * Filtrer les entrepreneurs.
    public function filterEntrepreneurs(Request $request)
    {
        // Sélectionner tous les étudiants avec leurs salaires
        $baseQuery = Student::with(['site', 'promotions', 'salaries']);

        // Filtrer par site si spécifié
        if ($request->filled('site_id')) {
            $baseQuery->where('site_id', $request->site_id);
        }

        // Filtrer par promotion si spécifiée (par numéro de promotion)
        if ($request->filled('promotion_id')) {
            $promotionNum = $request->promotion_id;
            $promotionIds = Promotion::where('num_promotion', $promotionNum)->pluck('id');
            $baseQuery->whereHas('promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        // Filtrer par étudiant si spécifié
        if ($request->filled('student_id')) {
            $baseQuery->where('id', $request->student_id);
        }

        $students = $baseQuery->paginate(10);

        // Pour chaque étudiant, déterminer s'il est entrepreneur (pas d'employeur)
        $students->getCollection()->transform(function ($student) {
            $hasEmployer = $student->salaries->contains(function ($salary) {
                return !is_null($salary->employeur) && trim($salary->employeur) !== '';
            });
            $student->is_entrepreneur = !$hasEmployer;
            return $student;
        });

        return response()->json($students);
    }

    //  * Exporter les entrepreneurs filtrés en Excel.
    public function exportFilteredEntrepreneurs(Request $request)
    {
        // Sélectionner tous les étudiants avec leurs salaires
        $baseQuery = Student::with(['site', 'promotions', 'salaries']);

        // Filtrer par site si spécifié
        if ($request->filled('site_id')) {
            $baseQuery->where('site_id', $request->site_id);
        }

        // Filtrer par promotion si spécifiée (par numéro de promotion)
        if ($request->filled('promotion_id')) {
            $promotionNum = $request->promotion_id;
            $promotionIds = Promotion::where('num_promotion', $promotionNum)->pluck('id');
            $baseQuery->whereHas('promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        // Filtrer par étudiant si spécifié
        if ($request->filled('student_id')) {
            $baseQuery->where('id', $request->student_id);
        }

        $students = $baseQuery->get();

        // Filtrer seulement les entrepreneurs (ceux sans employeur)
        $entrepreneurs = $students->filter(function ($student) {
            $hasEmployer = $student->salaries->contains(function ($salary) {
                return !is_null($salary->employeur) && trim($salary->employeur) !== '';
            });
            return !$hasEmployer;
        });

        $fileName = 'entrepreneurs_filtres_' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\EntrepreneursExport($request->site_id, $request->promotion_id, $request->student_id), $fileName);
    }

    //  * Afficher le formulaire de filtrage des situations handicap.
    public function handicapSituationsForm()
    {
        $sites = Site::all();
        return view('studentstatistics.handicap-situations', compact('sites'));
    }

    //  * Filtrer les situations handicap.
    public function filterHandicapSituations(Request $request)
    {
        $query = Student::with(['site', 'promotions']);

        // Filtrer par site si spécifié
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Filtrer par promotion si spécifiée (par numéro de promotion)
        if ($request->filled('promotion_id')) {
            $promotionNum = $request->promotion_id;
            $promotionIds = Promotion::where('num_promotion', $promotionNum)->pluck('id');
            $query->whereHas('promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        // Filtrer par étudiant si spécifié
        if ($request->filled('student_id')) {
            $query->where('id', $request->student_id);
        }

        // Filtrer par situation handicap si spécifiée
        if ($request->filled('situation_handicape')) {
            if ($request->situation_handicape === 'with_handicap') {
                $query->whereNotNull('situation_handicape')->where('situation_handicape', '!=', 'None');
            } else {
                $query->where('situation_handicape', $request->situation_handicape);
            }
        } else {
            // Par défaut, afficher seulement les étudiants avec handicap (pas None)
            $query->whereNotNull('situation_handicape')->where('situation_handicape', '!=', 'None');
        }

        $students = $query->paginate(10);

        return response()->json($students);
    }

    //  * Exporter les situations handicap filtrées en Excel.
    public function exportFilteredHandicapSituations(Request $request)
    {
        $query = Student::with(['site', 'promotions']);

        // Filtrer par site si spécifié
        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Filtrer par promotion si spécifiée (par numéro de promotion)
        if ($request->filled('promotion_id')) {
            $promotionNum = $request->promotion_id;
            $promotionIds = Promotion::where('num_promotion', $promotionNum)->pluck('id');
            $query->whereHas('promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        // Filtrer par étudiant si spécifié
        if ($request->filled('student_id')) {
            $query->where('id', $request->student_id);
        }

        // Filtrer par situation handicap si spécifiée
        if ($request->filled('situation_handicape')) {
            if ($request->situation_handicape === 'with_handicap') {
                $query->whereNotNull('situation_handicape')->where('situation_handicape', '!=', 'None');
            } else {
                $query->where('situation_handicape', $request->situation_handicape);
            }
        } else {
            // Par défaut, afficher seulement les étudiants avec handicap (pas None)
            $query->whereNotNull('situation_handicape')->where('situation_handicape', '!=', 'None');
        }

        $students = $query->get();

        $fileName = 'situations_handicap_filtres_' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\HandicapSituationsExport($request->site_id, $request->promotion_id, $request->student_id, $request->situation_handicape), $fileName);
    }
}
