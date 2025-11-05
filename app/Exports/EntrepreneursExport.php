<?php

namespace App\Exports;

use App\Models\Salary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EntrepreneursExport implements FromCollection, WithHeadings
{
    protected $siteId;
    protected $promotionNum;
    protected $studentId;

    public function __construct($siteId = null, $promotionNum = null, $studentId = null)
    {
        $this->siteId = $siteId;
        $this->promotionNum = $promotionNum;
        $this->studentId = $studentId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Sélectionner tous les étudiants avec leurs salaires
        $baseQuery = \App\Models\Student::with(['site', 'promotions', 'salaries']);

        if ($this->siteId) {
            $baseQuery->where('site_id', $this->siteId);
        }

        if ($this->promotionNum) {
            $promotionIds = \App\Models\Promotion::where('num_promotion', $this->promotionNum)->pluck('id');
            $baseQuery->whereHas('promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        if ($this->studentId) {
            $baseQuery->where('id', $this->studentId);
        }

        $students = $baseQuery->get();

        // Filtrer seulement les entrepreneurs (ceux sans employeur)
        $entrepreneurs = $students->filter(function ($student) {
            $hasEmployer = $student->salaries->contains(function ($salary) {
                return !is_null($salary->employeur) && trim($salary->employeur) !== '';
            });
            return !$hasEmployer;
        });

        return $entrepreneurs->map(function ($student) {
            return [
                'Student Name' => $student->first_name . ' ' . $student->last_name,
                'Site' => $student->site->designation ?? '',
                'Promotion' => $student->promotions->first()->num_promotion ?? '',
                'Sexe' => $student->sexe,
                'State of Origin' => $student->state_of_origin,
                'Situation Handicape' => $student->situation_handicape,
                'Status' => 'Entrepreneur (No Employer)',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Site',
            'Promotion',
            'Sexe',
            'State of Origin',
            'Situation Handicape',
            'Status',
        ];
    }
}
