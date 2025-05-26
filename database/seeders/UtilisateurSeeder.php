<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UtilisateurSeeder extends Seeder
{
    public function run()
    {
        $utilisateurs = [
            [
                'name' => 'Larios KIKI',
                'date_naissance' => '1990-01-01',
                'tel' => '12345678',
                'tel2' => '87654321',
                'poste' => 'Développeur',
                'role' => 'admin_principal',
                'email' => 'larioss383@gmail.com',
                'password' => Hash::make('motdepasse'),
                'site_id' => 1, // Remplace par un ID valide d'un site existant
            ],
            [
                'name' => 'Samuel Larios',
                'date_naissance' => '1995-02-02',
                'tel' => '23456789',
                'tel2' => '98765432',
                'poste' => 'Manager',
                'role' => 'user',
                'email' => 'samuellarios76@gmail.com',
                'password' => Hash::make('motdepasse'),
                'site_id' => 1, // Remplace par un ID valide d'un site existant
            ],
        ];

        foreach ($utilisateurs as $utilisateur) {
            Utilisateur::updateOrCreate(
                ['email' => $utilisateur['email']], // Vérifie si l'email existe déjà
                $utilisateur
            );
        }
    }
}
