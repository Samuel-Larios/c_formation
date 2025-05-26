<?php

namespace App\Exports;

use App\Models\Utilisateur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
     * Retourne la collection des utilisateurs.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Utilisateur::select('name', 'email', 'tel', 'role', 'date_naissance', 'poste', 'site_id')->get();
    }

    /**
     * Retourne les en-têtes du fichier Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nom',
            'Email',
            'Téléphone',
            'Rôle',
            'Date de Naissance',
            'Poste',
            'Site ID',
        ];
    }
}
