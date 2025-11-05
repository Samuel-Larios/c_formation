<?php

namespace App\Exports;

use App\Models\Subvention;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubventionsExport implements FromCollection, WithHeadings
{
    protected $siteId;
    protected $promotionNum;

    public function __construct($siteId = null, $promotionNum = null)
    {
        $this->siteId = $siteId;
        $this->promotionNum = $promotionNum;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Subvention::with(['student.site', 'student.promotions']);

        if ($this->siteId) {
            $query->where('site_id', $this->siteId);
        }

        if ($this->promotionNum) {
            $promotionIds = \App\Models\Promotion::where('num_promotion', $this->promotionNum)->pluck('id');
            $query->whereHas('student.promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        return $query->get()->map(function ($subvention) {
            return [
                'Student Name' => $subvention->student->first_name . ' ' . $subvention->student->last_name,
                'Site' => $subvention->student->site->designation ?? '',
                'Promotion' => $subvention->student->promotions->first()->num_promotion ?? '',
                'Start Up Kits' => $subvention->start_up_kits,
                'Grants' => $subvention->grants,
                'Loan' => $subvention->loan,
                'Date' => $subvention->date,
                'Start Up Kits Items Received' => $subvention->start_up_kits_items_received,
                'State of Farm Location' => $subvention->state_of_farm_location,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Site',
            'Promotion',
            'Start Up Kits',
            'Grants',
            'Loan',
            'Date',
            'Start Up Kits Items Received',
            'State of Farm Location',
        ];
    }
}
