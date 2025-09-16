<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Site;
use App\Models\Promotion;
use App\Models\Evaluation;
use App\Models\Matier;
use App\Models\Utilisateur;

class StatisticsController extends Controller
{
    // Afficher la page de statistiques avec les formulaires
    public function index()
    {
        $sites = Site::all();
        $matiers = Matier::all();
        return view('statistics.index', compact('sites', 'matiers'));
    }

    // Méthode dans le contrôleur
    public function getPromotions(Request $request)
    {
        $siteId = $request->get('site_id');
        $promotions = Promotion::where('site_id', $siteId)->get();
        $options = '<option value="">Sélectionner une promotion</option>';
        foreach ($promotions as $promotion) {
            $options .= '<option value="' . $promotion->id . '">' . $promotion->num_promotion . '</option>';
        }
        return $options;
    }



    // Filtrer les étudiants par site et promotion
    public function filterStudents(Request $request)
    {
        $data = $request->all();
        $data = $request->all();
        $matiers = Matier::query();
        $students = Student::query();

        if ($request->has('matier_id')) {
            $matiers->where('id', $data['matier_id']);
        }

        if ($request->has('site_id')) {
            $students->where('site_id', $data['site_id']);
        }

        if ($request->has('promotion_id')) {
            $students->whereHas('promotions', function($q) use ($data) {
                $q->where('promotion_id', $data['promotion_id']);
            });
        }

        $students = $students->get();
        $sites = Site::all();
        $promotions = Promotion::where('site_id', $data['site_id'] ?? null)->get();

        $matiers = $matiers->get();
        $sites = Site::all();

        return view('statistics.index', compact('matiers', 'students', 'sites', 'promotions'));
    }

    // Filtrer les matières par site
    public function filterMatiers(Request $request)
    {
        $data = $request->all();
        $matiers = Matier::query();

        if ($request->has('site_id')) {
            $matiers->where('site_id', $data['site_id']);
        }

        $matiers = $matiers->get();
        $sites = Site::all();

        return view('statistics.index', compact('matiers', 'sites'));
    }

    // Filtrer les utilisateurs par site
    public function filterUsers(Request $request)
    {
        $data = $request->all();
        $users = Utilisateur::query();

        if ($request->has('site_id')) {
            $users->where('site_id', $data['site_id']);
        }

        $users = $users->get();
        $sites = Site::all();

        return view('statistics.index', compact('users', 'sites'));
    }

    // Imprimer les statistiques
    public function print(Request $request)
    {
        // Récupérer les paramètres de filtre
        $siteId = $request->get('site_id');
        $promotionId = $request->get('promotion_id');

        // Filtrer les étudiants si des paramètres sont fournis
        $students = Student::query();
        if ($siteId) {
            $students->where('site_id', $siteId);
        }
        if ($promotionId) {
            $students->whereHas('promotions', function($q) use ($promotionId) {
                $q->where('promotion_id', $promotionId);
            });
        }
        $students = $students->get();

        // Filtrer les matières si un site est fourni
        $matiers = Matier::query();
        if ($siteId) {
            $matiers->where('site_id', $siteId);
        }
        $matiers = $matiers->get();

        // Filtrer les utilisateurs si un site est fourni
        $users = Utilisateur::query();
        if ($siteId) {
            $users->where('site_id', $siteId);
        }
        $users = $users->get();

        // Passer les données à la vue d'impression
        return view('statistics.print', compact('students', 'matiers', 'users', 'siteId', 'promotionId'));
    }



    // Récupérer les promotions d'un site
    public function getPromotions2($siteId)
    {
        $promotions = Promotion::where('site_id', $siteId)->get();
        $options = '<option value="">Sélectionner une promotion</option>';
        foreach ($promotions as $promotion) {
            $options .= '<option value="' . $promotion->id . '">' . $promotion->num_promotion . '</option>';
        }
        return $options;
    }

    // Récupérer les étudiants d'une promotion
    public function getStudents($promotionId)
    {
        $students = Student::whereHas('promotions', function($q) use ($promotionId) {
            $q->where('promotion_id', $promotionId);
        })->get();
        $options = '<option value="">Sélectionner un étudiant</option>';
        foreach ($students as $student) {
            $options .= '<option value="' . $student->id . '">' . $student->first_name . ' ' . $student->last_name . '</option>';
        }
        return $options;
    }

    public function showStudent($id)
    {
        // Récupérer l'étudiant avec ses relations
        $student = Student::with([
            'promotions',
            'evaluations.matier',
            'specializations.specialite',
            'entities',
            'jobCreations',
            'salaries',
            'subventions',
            'followUps',
            'businessStatuses'
        ])->findOrFail($id);

        return view('statistics.student', compact('student'));
    }

    public function promotionStatistics(Request $request)
    {
        $promotions = Promotion::orderBy('num_promotion', 'desc')->get();
        $lastFivePromotions = $promotions->take(5);
        $lastFiveData = [];
        foreach ($lastFivePromotions as $promotion) {
            $count = Student::whereHas('promotions', function ($query) use ($promotion) {
                $query->where('promotion_id', $promotion->id);
            })->count();
            $lastFiveData[] = [
                'promotion' => $promotion->num_promotion,
                'student_count' => $count,
            ];
        }

        $selectedPromotionId = $request->get('promotion');
        $expectedStudents = $request->get('expected', 0);
        $currentStudentsCount = 0;
        if ($selectedPromotionId) {
            $currentStudentsCount = Student::whereHas('promotions', function ($query) use ($selectedPromotionId) {
                $query->where('promotion_id', $selectedPromotionId);
            })->count();
        }

        return view('statistics.promotion-statistics', compact('promotions', 'lastFiveData', 'selectedPromotionId', 'expectedStudents', 'currentStudentsCount'));
    }

    public function getPromotionGenderDistribution(Request $request)
    {
        $promotionId = $request->get('promotion_id');

        if ($promotionId) {
            $maleCount = Student::whereHas('promotions', function ($query) use ($promotionId) {
                $query->where('promotion_id', $promotionId);
            })->where('sexe', 'M')->count();

            $femaleCount = Student::whereHas('promotions', function ($query) use ($promotionId) {
                $query->where('promotion_id', $promotionId);
            })->where('sexe', 'F')->count();
        } else {
            $maleCount = Student::where('sexe', 'M')->count();
            $femaleCount = Student::where('sexe', 'F')->count();
        }

        return response()->json([
            'male' => $maleCount,
            'female' => $femaleCount,
        ]);
    }
}
