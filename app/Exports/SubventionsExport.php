<?php

namespace App\Exports;

use App\Models\Subvention;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SubventionsExport implements FromCollection, WithHeadings, WithMapping
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
        $query = Subvention::with(['student'])
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
            'Start-up Kits',
            'Grants',
            'Loan',
            'Date',
            'Kits Items Received',
            'Farm State / Location',
            'Student Last Name',
            'Student First Name',
            'Phone Number',
            'Gender',
        ];
    }

    public function map($subvention): array
    {
        $gender = $subvention->student->sexe == 'M' ? 'Male' : ($subvention->student->sexe == 'F' ? 'Female' : 'N/A');

        return [
            $subvention->id,
            $subvention->start_up_kits ?? 'N/A',
            $subvention->grants ?? 'N/A',
            $subvention->loan ?? 'N/A',
            $subvention->date ?? 'N/A',
            $subvention->start_up_kits_items_received ?? 'N/A',
            $subvention->state_of_farm_location ?? 'N/A',
            $subvention->student->last_name ?? 'N/A',
            $subvention->student->first_name ?? 'N/A',
            $subvention->student->contact ?? 'N/A',
            $gender,
        ];
    }
}
