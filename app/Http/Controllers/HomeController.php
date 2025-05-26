<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Redirige l'utilisateur vers la page de connexion
        return redirect()->route('login');
    }
}
