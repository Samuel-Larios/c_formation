<?php

namespace App\Http\Controllers;

use App\Models\BusinessStatus;
use App\Models\Entity;
use App\Models\Evaluation;
use App\Models\FollowUp;
use App\Models\ImageFollowUp;
use App\Models\JobCreation;
use App\Models\Matier;
use App\Models\Promotion;
use App\Models\PromotionApprenant;
use App\Models\Salary;
use App\Models\Site;
use App\Models\Specialite;
use App\Models\Specialization;
use App\Models\Student;
use App\Models\Subvention;
use App\Models\User;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $siteId = $user->site_id;

        // General statistics for the user's site
        $totalStudents = Student::where('site_id', $siteId)->count();
        $totalSpecializations = Specialization::where('site_id', $siteId)->count();
        $totalPromotions = Promotion::where('site_id', $siteId)->count();
        $totalEntities = Entity::whereHas('student', function ($query) use ($siteId) {
            $query->where('site_id', $siteId);
        })->count();
        $totalJobCreations = JobCreation::whereHas('student', function ($query) use ($siteId) {
            $query->where('site_id', $siteId);
        })->count();
        $totalSalaries = Salary::whereHas('student', function ($query) use ($siteId) {
            $query->where('site_id', $siteId);
        })->count();
        $totalMatiers = Matier::where('site_id', $siteId)->count();
        $totalSubventions = Subvention::whereHas('student', function ($query) use ($siteId) {
            $query->where('site_id', $siteId);
        })->count();
        $totalEvaluations = Evaluation::whereHas('student', function ($query) use ($siteId) {
            $query->where('site_id', $siteId);
        })->count();
        $totalFollowUps = FollowUp::whereHas('student', function ($query) use ($siteId) {
            $query->where('site_id', $siteId);
        })->count();
        $totalBusinessStatuses = BusinessStatus::whereHas('student', function ($query) use ($siteId) {
            $query->where('site_id', $siteId);
        })->count();

        // Retrieve the list of specializations for the connected user's site
        $specialites = Specialite::where('site_id', $siteId)->get();

        // Detailed statistics for the user's site
        $studentsPerSite = DB::table('students')
            ->join('sites', 'students.site_id', '=', 'sites.id')
            ->where('students.site_id', $siteId)
            ->select('sites.designation', DB::raw('count(students.id) as total'))
            ->groupBy('sites.designation')
            ->get();

        $studentsPerPromotion = DB::table('promotion_apprenant')
            ->join('promotions', 'promotion_apprenant.promotion_id', '=', 'promotions.id')
            ->where('promotions.site_id', $siteId)
            ->select('promotions.num_promotion', DB::raw('count(promotion_apprenant.student_id) as total'))
            ->groupBy('promotions.num_promotion')
            ->get();

        $studentsPerSpecialization = DB::table('specializations')
            ->join('students', 'specializations.student_id', '=', 'students.id')
            ->join('specialites', 'specializations.specialite_id', '=', 'specialites.id')
            ->where('specializations.site_id', $siteId)
            ->select('specialites.designation', DB::raw('count(students.id) as total'))
            ->groupBy('specialites.designation')
            ->get();

        $salariesPerLocation = Salary::select('localisation', DB::raw('count(id) as total'))
            ->whereHas('student', function ($query) use ($siteId) {
                $query->where('site_id', $siteId);
            })
            ->groupBy('localisation')
            ->get();

        $matiersPerCoef = Matier::where('site_id', $siteId)
            ->select('coef', DB::raw('count(id) as total'))
            ->groupBy('coef')
            ->get();

        $studentsPerStateOfOrigin = Student::where('site_id', $siteId)
            ->select('state_of_origin', DB::raw('count(id) as total'))
            ->groupBy('state_of_origin')
            ->get();

        $studentsPerStateOfResidence = Student::where('site_id', $siteId)
            ->select('state_of_residence', DB::raw('count(id) as total'))
            ->groupBy('state_of_residence')
            ->get();

        // Nouveaux calculs pour la section supplémentaire
        $studentsBySexe = Student::where('site_id', $siteId)
            ->select('sexe', DB::raw('count(id) as total'))
            ->groupBy('sexe')
            ->get();

        $averageAgeBySite = Student::select('site_id', DB::raw('AVG(TIMESTAMPDIFF(YEAR, date_naissance, CURDATE())) as average_age'))
            ->groupBy('site_id')
            ->get();

        // Préparer les données pour les graphiques
        $statisticsLabels = $studentsPerSite->pluck('designation');
        $statisticsData = $studentsPerSite->pluck('total');

        $studentsBySiteLabels = $studentsPerSite->pluck('designation');
        $studentsBySiteData = $studentsPerSite->pluck('total');

        $studentsByPromotionLabels = $studentsPerPromotion->pluck('num_promotion');
        $studentsByPromotionData = $studentsPerPromotion->pluck('total');

        $studentsBySpecializationLabels = $studentsPerSpecialization->pluck('designation');
        $studentsBySpecializationData = $studentsPerSpecialization->pluck('total');

        $salariesByLocationLabels = $salariesPerLocation->pluck('localisation');
        $salariesByLocationData = $salariesPerLocation->pluck('total');

        $matiersByCoefLabels = $matiersPerCoef->pluck('coef');
        $matiersByCoefData = $matiersPerCoef->pluck('total');

        $studentsByStateOfResidenceLabels = $studentsPerStateOfResidence->pluck('state_of_residence');
        $studentsByStateOfResidenceData = $studentsPerStateOfResidence->pluck('total');

        // Données pour la nouvelle section
        $studentsBySexeLabels = $studentsBySexe->pluck('sexe');
        $studentsBySexeData = $studentsBySexe->pluck('total');

        $averageAgeBySiteLabels = $averageAgeBySite->pluck('site_id');
        $averageAgeBySiteData = $averageAgeBySite->pluck('average_age');

        $siteStatisticsLabels = $studentsPerPromotion->pluck('num_promotion');
        $siteStatisticsData = $studentsPerPromotion->pluck('total');

        return view('dashboard.dashboard', compact(
            'totalStudents',
            'totalSpecializations',
            'specialites',
            'totalPromotions',
            'totalEntities',
            'totalJobCreations',
            'totalSalaries',
            'totalMatiers',
            'totalSubventions',
            'totalEvaluations',
            'totalFollowUps',
            'totalBusinessStatuses',
            'studentsPerSite',
            'studentsPerPromotion',
            'studentsPerSpecialization',
            'salariesPerLocation',
            'matiersPerCoef',
            'studentsPerStateOfOrigin',
            'studentsPerStateOfResidence',
            'statisticsLabels',
            'statisticsData',
            'studentsBySiteLabels',
            'studentsBySiteData',
            'studentsByPromotionLabels',
            'studentsByPromotionData',
            'studentsBySpecializationLabels',
            'studentsBySpecializationData',
            'salariesByLocationLabels',
            'salariesByLocationData',
            'matiersByCoefLabels',
            'matiersByCoefData',
            'studentsByStateOfResidenceLabels',
            'studentsByStateOfResidenceData',
            'studentsBySexeLabels',
            'studentsBySexeData',
            'averageAgeBySiteLabels',
            'averageAgeBySiteData',
            'siteStatisticsLabels',
            'siteStatisticsData'
        ));
    }
}
