<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Entity;
use App\Models\Matier;
use App\Models\Salary;
use App\Models\Student;
use App\Models\FollowUp;
use App\Models\Promotion;
use App\Models\Evaluation;
use App\Models\Specialite;
use App\Models\Subvention;
use App\Models\JobCreation;
use Illuminate\Http\Request;
use App\Models\BusinessStatus;
use App\Models\Specialization;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Afficher le tableau de bord administrateur principal
    public function dashboard()
    {

        // Statistiques générales pour tous les sites
        $totalStudents = Student::count();
        $totalSpecializations = Specialization::count();
        $totalPromotions = Promotion::count();
        $totalEntities = Entity::count();
        $totalJobCreations = JobCreation::count();
        $totalSalaries = Salary::count();
        $totalMatiers = Matier::count();
        $totalSites = Site::count();
        $totalSubventions = Subvention::count();
        $totalEvaluations = Evaluation::count();
        $totalFollowUps = FollowUp::count();
        $totalBusinessStatuses = BusinessStatus::count();

        // Récupérer la liste des spécialités pour tous les sites
        $specialites = Specialite::all();

        // Statistiques détaillées pour tous les sites
        $studentsPerSite = DB::table('students')
            ->join('sites', 'students.site_id', '=', 'sites.id')
            ->select('sites.designation', DB::raw('count(students.id) as total'))
            ->groupBy('sites.designation')
            ->get();

        $studentsPerPromotion = DB::table('promotion_apprenant')
            ->join('promotions', 'promotion_apprenant.promotion_id', '=', 'promotions.id')
            ->select('promotions.num_promotion', DB::raw('count(promotion_apprenant.student_id) as total'))
            ->groupBy('promotions.num_promotion')
            ->get();

        $studentsPerSpecialization = DB::table('specializations')
            ->join('students', 'specializations.student_id', '=', 'students.id')
            ->join('specialites', 'specializations.specialite_id', '=', 'specialites.id')
            ->select('specialites.designation', DB::raw('count(students.id) as total'))
            ->groupBy('specialites.designation')
            ->get();

        $salariesPerLocation = Salary::select('localisation', DB::raw('count(id) as total'))
            ->groupBy('localisation')
            ->get();

        $matiersPerCoef = Matier::select('coef', DB::raw('count(id) as total'))
            ->groupBy('coef')
            ->get();

        $studentsPerStateOfOrigin = Student::select('state_of_origin', DB::raw('count(id) as total'))
            ->groupBy('state_of_origin')
            ->get();

        $studentsPerStateOfResidence = Student::select('state_of_residence', DB::raw('count(id) as total'))
            ->groupBy('state_of_residence')
            ->get();

        // Récupérer le nombre d'étudiants par spécialisation et par site
        $studentsPerSpecializationPerSite = DB::table('students')
            ->join('specializations', 'students.id', '=', 'specializations.student_id')
            ->join('specialites', 'specializations.specialite_id', '=', 'specialites.id')
            ->join('sites', 'students.site_id', '=', 'sites.id')
            ->select(
                'sites.designation as site_name',
                'specialites.designation as specialization_name',
                DB::raw('count(students.id) as total_students')
            )
            ->groupBy('sites.designation', 'specialites.designation')
            ->orderBy('sites.designation')
            ->get()
            ->groupBy('site_name'); // Grouper par nom de site

        // Récupérer le nombre d'étudiants par site et par promotion
        $studentsPerSitePerPromotion = DB::table('students')
            ->join('promotion_apprenant', 'students.id', '=', 'promotion_apprenant.student_id')
            ->join('promotions', 'promotion_apprenant.promotion_id', '=', 'promotions.id')
            ->join('sites', 'students.site_id', '=', 'sites.id')
            ->select(
                'sites.designation as site_name',
                'promotions.num_promotion as promotion_name',
                DB::raw('count(students.id) as total_students')
            )
            ->groupBy('sites.designation', 'promotions.num_promotion')
            ->orderBy('sites.designation')
            ->get()
            ->groupBy('site_name'); // Grouper par nom de site

        // Récupérer le nombre d'utilisateurs par site
        $usersPerSite = DB::table('utilisateurs')
            ->join('sites', 'utilisateurs.site_id', '=', 'sites.id')
            ->select(
                'sites.designation as site_name',
                DB::raw('count(utilisateurs.id) as total_users')
            )
            ->groupBy('sites.designation')
            ->orderBy('sites.designation')
            ->get();


        // Statistiques par site
        $siteStatistics = DB::table('sites')
            ->select(
                'sites.id as site_id',
                'sites.designation as site_name',
                DB::raw('count(distinct students.id) as total_students'),
                DB::raw('count(distinct entities.id) as total_entities'),
                DB::raw('count(distinct job_creations.id) as total_job_creations'),
                DB::raw('count(distinct salaries.id) as total_salaries'),
                DB::raw('count(distinct subvention.id) as total_subventions'),
                DB::raw('count(distinct specializations.id) as total_specializations'),
                DB::raw('count(distinct evaluations.id) as total_evaluations'),
                DB::raw('count(distinct follow_up.id) as total_follow_ups'),
                DB::raw('count(distinct business_status.id) as total_business_statuses')
            )
            ->leftJoin('students', 'sites.id', '=', 'students.site_id')
            ->leftJoin('entities', 'students.id', '=', 'entities.student_id')
            ->leftJoin('job_creations', 'students.id', '=', 'job_creations.student_id')
            ->leftJoin('salaries', 'students.id', '=', 'salaries.student_id')
            ->leftJoin('subvention', 'students.id', '=', 'subvention.student_id')
            ->leftJoin('specializations', 'students.id', '=', 'specializations.student_id')
            ->leftJoin('evaluations', 'students.id', '=', 'evaluations.student_id')
            ->leftJoin('follow_up', 'students.id', '=', 'follow_up.student_id')
            ->leftJoin('business_status', 'students.id', '=', 'business_status.student_id')
            ->groupBy('sites.id', 'sites.designation')
            ->get();

        // Retourner la vue avec les données
        return view('admin.dashboard', compact(
            'totalStudents',
            'totalSpecializations',
            'specialites',
            'totalPromotions',
            'totalEntities',
            'totalJobCreations',
            'totalSalaries',
            'totalMatiers',
            'totalSites',
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
            'siteStatistics',
            'studentsPerSpecializationPerSite',
            'studentsPerSitePerPromotion',
            'usersPerSite'

        ));

        // return view('admin.dashboard',);
    }
}
