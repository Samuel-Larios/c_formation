<?php

namespace App\Exports;

use App\Models\FollowUp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FollowUpsExport implements FromCollection, WithHeadings
{
    protected $siteId;
    protected $promotionId;

    public function __construct($siteId = null, $promotionId = null)
    {
        $this->siteId = $siteId;
        $this->promotionId = $promotionId;
    }

    public function collection()
    {
        $query = FollowUp::with(['student.site', 'student.promotions']);

        // Filtrer par site si spécifié
        if ($this->siteId) {
            $query->where('site_id', $this->siteId);
        }

        // Filtrer par promotion si spécifiée (par numéro de promotion)
        if ($this->promotionId) {
            $promotionIds = \App\Models\Promotion::where('num_promotion', $this->promotionId)->pluck('id');
            $query->whereHas('student.promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        // Filtrer par étudiant si spécifié
        if ($this->studentId) {
            $query->where('student_id', $this->studentId);
        }

        return $query->get()->map(function ($followUp) {
            return [
                'Student Name' => $followUp->student ? $followUp->student->first_name . ' ' . $followUp->student->last_name : '',
                'Site' => $followUp->student && $followUp->student->site ? $followUp->student->site->designation : '',
                'Promotion' => $followUp->student && $followUp->student->promotions && $followUp->student->promotions->count() > 0 ? $followUp->student->promotions->first()->num_promotion : '',
                'Farm Visits' => $followUp->farm_visits,
                'Phone Contact' => $followUp->phone_contact,
                'Sharing of Impact Stories' => $followUp->sharing_of_impact_stories,
                'Back Stopping' => $followUp->back_stopping,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Site',
            'Promotion',
            'Farm Visits',
            'Phone Contact',
            'Sharing of Impact Stories',
            'Back Stopping',
        ];
    }
}
