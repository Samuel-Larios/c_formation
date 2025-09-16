<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalariesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $salaries;

    public function __construct($salaries)
    {
        $this->salaries = $salaries;
    }

    public function collection()
    {
        return $this->salaries;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Company',
            'Location',
            'Employer',
            'Phone',
            'Student Last Name',
            'Student First Name',
            'Gender',
        ];
    }

    public function map($salary): array
    {
        $gender = $salary->student->sexe == 'M' ? 'Male' : ($salary->student->sexe == 'F' ? 'Female' : 'N/A');

        return [
            $salary->id,
            $salary->entreprise ?? 'N/A',
            $salary->localisation ?? 'N/A',
            $salary->employeur ?? 'N/A',
            $salary->tel ?? 'N/A',
            $salary->student->last_name ?? 'N/A',
            $salary->student->first_name ?? 'N/A',
            $gender,
        ];
    }
}
