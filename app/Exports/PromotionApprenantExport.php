<?php

namespace App\Exports;

use App\Models\PromotionApprenant;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PromotionApprenantExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $siteId = Auth::user()->site_id;

        $query = PromotionApprenant::with(['promotion', 'student'])
            ->where('site_id', $siteId);

        // Appliquer les filtres
        if (!empty($this->filters['promotion_id'])) {
            $query->where('promotion_id', $this->filters['promotion_id']);
        }

        if (!empty($this->filters['sexe'])) {
            $query->whereHas('student', function($q) {
                $q->where('sexe', $this->filters['sexe']);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Promotion',
            'Nom',
            'Prénom',
            'Email',
            'Téléphone',
            'Sexe',
            'Date d\'ajout'
        ];
    }

    public function map($promotionApprenant): array
    {
        return [
            $promotionApprenant->promotion->num_promotion ?? '',
            $promotionApprenant->student->last_name ?? '',
            $promotionApprenant->student->first_name ?? '',
            $promotionApprenant->student->email ?? '',
            $promotionApprenant->student->contact ?? '',
            $promotionApprenant->student->sexe ?? '',
            $promotionApprenant->created_at->format('d/m/Y H:i'),
        ];
    }
}
