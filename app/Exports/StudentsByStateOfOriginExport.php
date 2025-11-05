<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsByStateOfOriginExport implements FromCollection, WithHeadings
{
    protected $siteId;
    protected $promotionNum;
    protected $stateOfOrigin;

    public function __construct($siteId = null, $promotionNum = null, $stateOfOrigin = null)
    {
        $this->siteId = $siteId;
        $this->promotionNum = $promotionNum;
        $this->stateOfOrigin = $stateOfOrigin;
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

        if ($this->stateOfOrigin) {
            $query->where('state_of_origin', $this->stateOfOrigin);
        }

        return $query->get()->map(function ($student) {
            return [
                'First Name' => $student->first_name,
                'Last Name' => $student->last_name,
                'Sex' => $student->sexe,
                'Site' => $student->site->designation ?? '',
                'Promotion' => $student->promotions->first()->num_promotion ?? '',
                'State of Origin' => $student->state_of_origin,
                'State of Residence' => $student->state_of_residence,
                'LGA' => $student->lga,
                'Community' => $student->community,
                'Contact' => $student->contact,
                'Email' => $student->email,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Sex',
            'Site',
            'Promotion',
            'State of Origin',
            'State of Residence',
            'LGA',
            'Community',
            'Contact',
            'Email',
        ];
    }
}
