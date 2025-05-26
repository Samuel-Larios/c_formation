<?php

namespace App\Imports;

use App\Models\Utilisateur;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * Convertit une ligne du fichier Excel en modèle Utilisateur.
     *
     * @param array $row
     * @return \App\Models\Utilisateur|null
     */
    public function model(array $row)
    {
        return new Utilisateur([
            'name' => $row['nom'],
            'email' => $row['email'],
            'tel' => $row['telephone'],
            'role' => $row['role'],
            'date_naissance' => $row['date_de_naissance'],
            'poste' => $row['poste'],
            'site_id' => $row['site_id'],
            'password' => bcrypt('password'), // Mot de passe par défaut
        ]);
    }
}
