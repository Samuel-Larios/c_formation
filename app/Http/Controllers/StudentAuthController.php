<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class StudentAuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion des étudiants.
     */
    public function showLoginForm()
    {
        return view('login.login');
    }

    /**
     * Gère la soumission du formulaire de connexion des étudiants.
     */
    public function login(Request $request)
    {
        // Validation des données
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Tentative de connexion avec les informations de l'étudiant
        if (Auth::guard('student')->attempt($credentials)) {
            // Régénération de la session pour éviter les attaques de fixation de session
            $request->session()->regenerate();

            // Redirection vers le tableau de bord des étudiants
            return redirect()->route('student.dashboard');
        }

        // Si l'authentification échoue, rediriger avec une erreur
        return back()->withErrors([
            'email' => 'The login information is incorrect.',
        ]);
    }

    /**
     * Affiche le tableau de bord des étudiants.
     */
    public function dashboard()
    {
        // Récupérer l'étudiant connecté
        $student = Auth::guard('student')->user();

        // Passer les données à la vue
        return view('student.dashboard', compact('student'));
    }

    /**
     * Gère la déconnexion des étudiants.
     */
    public function logout(Request $request)
    {
        Auth::guard('student')->logout(); // Déconnexion de l'étudiant
        $request->session()->invalidate(); // Invalidation de la session
        $request->session()->regenerateToken(); // Régénération du jeton CSRF

        // Redirection vers la page de connexion
        return redirect()->route('student.login');
    }
}
