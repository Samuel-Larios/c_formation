<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Site;
use App\Models\Student;
use App\Models\Promotion;
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
            Student::whereHas('promotions', function($query) use ($promotionId) {
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
            'student', 'specialites', 'evaluations', 'jobCreations',
            'salaries', 'subventions', 'followUps', 'businessStatuses', 'entities'
        ));
    }

    //  * Imprimer les détails d'un étudiant.
    public function printStudent($id)
    {
        $student = Student::with([
            'site', 'promotions', 'evaluations.matier', 'specializations.specialite',
            'jobCreations', 'salaries', 'subventions', 'followUps',
            'businessStatuses', 'entities'
        ])->findOrFail($id);

        return view('studentstatistics.print', compact('student'));
    }
}
