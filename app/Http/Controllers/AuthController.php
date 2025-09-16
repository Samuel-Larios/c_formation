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
