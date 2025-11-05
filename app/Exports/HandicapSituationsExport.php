<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HandicapSituationsExport implements FromCollection, WithHeadings
{
    protected $siteId;
    protected $promotionNum;
    protected $studentId;
    protected $situationHandicape;

    public function __construct($siteId = null, $promotionNum = null, $studentId = null, $situationHandicape = null)
    {
        $this->siteId = $siteId;
        $this->promotionNum = $promotionNum;
        $this->studentId = $studentId;
        $this->situationHandicape = $situationHandicape;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Student::with(['site', 'promotions']);

        if ($this->siteId) {
            $query->where('site_id', $this->siteId);
        }

        if ($this->promotionNum) {
            $promotionIds = \App\Models\Promotion::where('num_promotion', $this->promotionNum)->pluck('id');
            $query->whereHas('promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        if ($this->studentId) {
            $query->where('id', $this->studentId);
        }

        if ($this->situationHandicape) {
            if ($this->situationHandicape === 'with_handicap') {
                $query->whereNotNull('situation_handicape')->where('situation_handicape', '!=', 'None');
            } else {
                $query->where('situation_handicape', $this->situationHandicape);
            }
        } else {
            // Par défaut, exporter seulement les étudiants avec handicap (pas None)
            $query->whereNotNull('situation_handicape')->where('situation_handicape', '!=', 'None');
        }

        return $query->get()->map(function ($student) {
            return [
                'Student Name' => $student->first_name . ' ' . $student->last_name,
                'Site' => $student->site->designation ?? '',
                'Promotion' => $student->promotions->first()->num_promotion ?? '',
                'Sex' => $student->sexe,
                'Handicap Situation' => $student->situation_handicape,
                'Marital Status' => $student->situation_matrimoniale,
                'Date of Birth' => $student->date_naissance ? $student->date_naissance->format('Y-m-d') : '',
                'Contact' => $student->contact,
                'State of Origin' => $student->state_of_origin,
                'State of Residence' => $student->state_of_residence,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Site',
            'Promotion',
            'Sex',
            'Handicap Situation',
            'Marital Status',
            'Date of Birth',
            'Contact',
            'State of Origin',
            'State of Residence',
        ];
    }
}
