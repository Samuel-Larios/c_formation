<?php

namespace App\Exports;

use App\Models\JobCreation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JobCreationsExport implements FromCollection, WithHeadings
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
        $query = JobCreation::with(['student.site', 'student.promotions']);

        if ($this->siteId) {
            $query->whereHas('student', function ($q) {
                $q->where('site_id', $this->siteId);
            });
        }

        if ($this->promotionNum) {
            $promotionIds = \App\Models\Promotion::where('num_promotion', $this->promotionNum)->pluck('id');
            $query->whereHas('student.promotions', function ($q) use ($promotionIds) {
                $q->whereIn('promotion_id', $promotionIds);
            });
        }

        return $query->get()->map(function ($jobCreation) {
            return [
                'Student Name' => $jobCreation->student->first_name . ' ' . $jobCreation->student->last_name,
                'Site' => $jobCreation->student->site->designation ?? '',
                'Promotion' => $jobCreation->student->promotions->first()->num_promotion ?? '',
                'Job Name' => $jobCreation->nom,
                'Phone' => $jobCreation->tel,
                'Gender' => $jobCreation->sexe,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Site',
            'Promotion',
            'Job Name',
            'Phone',
            'Gender',
        ];
    }
}
