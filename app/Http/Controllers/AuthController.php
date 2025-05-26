<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Matier;
use App\Models\Salary;
use App\Models\Student;
use App\Models\Promotion;
use App\Models\Specialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Afficher le formulaire de connexion
    public function showLoginForm()
    {
        return view('login.login'); // login/login.blade.php
    }

    // Connexion de l'utilisateur
    public function loginUser(Request $request)
    {
        // Validation des données
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Vérifier si l'utilisateur existe
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            // L'utilisateur n'existe pas
            return back()->withErrors([
                'login' => 'The user does not exist.',
            ]);
        }

        // Tentative de connexion avec les informations de l'utilisateur
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            // Récupérer l'utilisateur connecté
            $user = Auth::user();

            // Récupérer l'id du site associé à l'utilisateur et le stocker dans la session
            session(['site_id' => $user->site_id]);

            // Vérifier le rôle de l'utilisateur
            if ($user->role === 'admin_principal') {
                // Rediriger l'admin_principal vers le tableau de bord admin
                return redirect()->route('admin.dashboard');
            } else {
                // Rediriger les autres utilisateurs vers le tableau de bord utilisateur
                return redirect()->route('dashboard');
            }
        }

        // Si l'authentification échoue (mot de passe incorrect)
        return back()->withErrors([
            'password' => 'The password is incorrect.',
        ]);
    }

    // Afficher le tableau de bord
    public function dashboard()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Récupérer l'ID du site de l'utilisateur connecté
        $siteId = $user->site_id;

        // Statistiques générales pour le site de l'utilisateur
        $totalStudents = Student::where('site_id', $siteId)->count();
        $totalSpecializations = DB::table('specializations')->where('site_id', $siteId)->count();
        $totalPromotions = Promotion::where('site_id', $siteId)->count();
        // $totalEntities = DB::table('entities')->count();
        $totalJobCreations = DB::table('job_creations')->count();
        $totalSalaries = Salary::count();
        $totalMatiers = Matier::where('site_id', $siteId)->count();

        // Récupérer la liste des spécialités pour le site de l'utilisateur connecté
        $specialites = Specialite::where('site_id', $siteId)->get();

        $totalEntities = DB::table('entities')
                ->join('students', 'entities.student_id', '=', 'students.id')
                ->where('students.site_id', $siteId)
                ->count();

        // Statistiques détaillées pour le site de l'utilisateur
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

        // Retourner la vue avec les données
        return view('dashboard.dashboard', compact(
            'totalStudents',
            'totalSpecializations',
            'specialites',
            'totalPromotions',
            'totalEntities',
            'totalJobCreations',
            'totalSalaries',
            'totalMatiers',
            'studentsPerSite',
            'studentsPerPromotion',
            'studentsPerSpecialization',
            'salariesPerLocation',
            'matiersPerCoef',
            'studentsPerStateOfOrigin',
            'studentsPerStateOfResidence'
        ));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login'); // Vérifie que cette route existe bien
    }

    // Connexion de l'étudiant
    public function loginStudent(Request $request)
    {
        // Validation des données
        $request->validate([
            'password' => 'required|string',
        ]);

        // Tentative de connexion de l'étudiant
        if (Auth::guard('student')->attempt(['password' => $request->password])) {
            // Rediriger vers le dashboard de l'étudiant
            return redirect()->route('admin.dashboard');
        }

        // Si l'authentification échoue
        return back()->withErrors([
            'password' => 'The password is incorrect.',
        ]);
    }

}
