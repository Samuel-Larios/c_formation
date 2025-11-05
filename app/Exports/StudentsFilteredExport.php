<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsFilteredExport implements FromCollection, WithHeadings, WithMapping
{
    protected $students;

    public function __construct(Collection $students)
    {
        $this->students = $students;
    }

    public function collection()
    {
        return $this->students;
    }

    public function headings(): array
    {
        return [
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
        ];
    }
}
