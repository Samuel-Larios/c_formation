<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsFullExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Student::with([
            'site',
            'promotions',
            'specializations.specialite',
            'evaluations.matier',
            'jobCreations',
            'salaries',
            'subventions',
            'followUps',
            'businessStatuses',
            'entities'
        ])->get();
    }

    public function headings(): array
    {
        return [
            // Informations personnelles
            'Prénom',
            'Nom',
            'Sexe',
            'Situation Matrimoniale',
            'Situation Handicapé',
            'Date de Naissance',
            'Âge',
            'Contact',
            'Email',
            'État d\'origine',
            'État de résidence',
            'État',
            'LGA',
            'Communauté',
            'Site',
            'Promotions',
            'Spécialisations',
            'Créé le',
            'Mis à jour le',
        ];
    }

    public function map($student): array
    {
        $gender = $student->sexe == 'M' ? 'Masculin' : 'Féminin';

        $age = null;
        if ($student->date_naissance) {
            $dob = \Carbon\Carbon::parse($student->date_naissance);
            $age = $dob->age;
        }

        // Promotions
        $promotions = $student->promotions->pluck('num_promotion')->join(', ');

        // Spécialisations
        $specializations = $student->specializations->pluck('specialite.designation')->join(', ');

        // Évaluations
        $evaluations = $student->evaluations->map(function ($eval) {
            return $eval->matier->designation . ': ' . $eval->note;
        })->join('; ');

        // Emplois créés
        $jobCreations = $student->jobCreations->map(function ($job) {
            return $job->nom . ' (' . $job->tel . ')';
        })->join('; ');

        // Salaires
        $salaries = $student->salaries->map(function ($salary) {
            return $salary->entreprise . ' - ' . $salary->localisation;
        })->join('; ');

        // Subventions
        $subventions = $student->subventions->map(function ($subvention) {
            return 'Start-Up Kits: ' . $subvention->start_up_kits . ', Grants: ' . $subvention->grants . ', Loan: ' . $subvention->loan;
        })->join('; ');

        // Suivis
        $followUps = $student->followUps->map(function ($followUp) {
            return 'Farm Visits: ' . $followUp->farm_visits . ', Phone Contact: ' . $followUp->phone_contact;
        })->join('; ');

        // Statuts d'entreprises
        $businessStatuses = $student->businessStatuses->map(function ($status) {
            return $status->type_of_business . ' - ' . $status->status;
        })->join('; ');

        // Entités
        $entities = $student->entities->pluck('activity')->join(', ');

        return [
            $student->first_name,
            $student->last_name,
            $gender,
            $student->situation_matrimoniale,
            $student->situation_handicape,
            $student->date_naissance,
            $age,
            $student->contact,
            $student->email,
            $student->state_of_origin,
            $student->state_of_residence,
            $student->state,
            $student->lga,
            $student->community,
            $student->site->designation ?? '',
            $promotions,
            $specializations,
            $student->created_at,
            $student->updated_at,
        ];
    }
}
