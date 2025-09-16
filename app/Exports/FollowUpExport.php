<?php

namespace App\Exports;

use App\Models\FollowUp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FollowUpExport implements FromCollection, WithHeadings, WithMapping
{
    protected $siteId;
    protected $promotionId;

    public function __construct($siteId, $promotionId)
    {
        $this->siteId = $siteId;
        $this->promotionId = $promotionId;
    }

    public function collection()
    {
        $query = FollowUp::with(['student', 'images'])
            ->where('site_id', $this->siteId);

        if ($this->promotionId) {
            $promotionId = $this->promotionId;
            $query->whereHas('student.promotions', function($q) use ($promotionId) {
                $q->where('promotion_id', $promotionId);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Farm Visits',
            'Phone Contact',
            'Sharing of Impact Stories',
            'Back-stopping',
            'Student Last Name',
            'Student First Name',
            'Phone Number',
            'Gender',
            'Images Count',
        ];
    }

    public function map($followUp): array
    {
        $gender = $followUp->student->sexe == 'M' ? 'Male' : ($followUp->student->sexe == 'F' ? 'Female' : 'N/A');

        return [
            $followUp->id,
            $followUp->farm_visits ?? 'N/A',
            $followUp->phone_contact ?? 'N/A',
            $followUp->sharing_of_impact_stories ?? 'N/A',
            $followUp->back_stopping ?? 'N/A',
            $followUp->student->last_name ?? 'N/A',
            $followUp->student->first_name ?? 'N/A',
            $followUp->student->contact ?? 'N/A',
            $gender,
            $followUp->images->count(),
        ];
    }
}
