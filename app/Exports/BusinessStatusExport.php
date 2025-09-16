<?php

namespace App\Exports;

use App\Models\BusinessStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BusinessStatusExport implements FromCollection, WithHeadings, WithMapping
{
    protected $type_of_business;
    protected $status;
    protected $sexe;
    protected $promotion_id;
    protected $language;

    public function __construct($type_of_business, $status, $sexe, $promotion_id, $language = 'en')
    {
        $this->type_of_business = $type_of_business;
        $this->status = $status;
        $this->sexe = $sexe;
        $this->promotion_id = $promotion_id;
        $this->language = $language;
    }

    public function collection()
    {
        return BusinessStatus::with(['student', 'site'])
            ->when($this->type_of_business, function ($q) {
                $q->where('type_of_business', 'like', "%{$this->type_of_business}%");
            })
            ->when($this->status, function ($q) {
                $q->where('status', 'like', "%{$this->status}%");
            })
            ->when($this->sexe, function ($q) {
                $q->whereHas('student', function ($sq) {
                    $sq->where('sexe', 'like', "%{$this->sexe}%");
                });
            })
            ->when($this->promotion_id, function ($q) {
                $q->whereHas('student.promotions', function ($pq) {
                    $pq->where('promotions.id', $this->promotion_id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        if ($this->language === 'en') {
            return [
                'ID',
                'Business Type',
                'Status',
                'Student First Name',
                'Student Last Name',
                'Gender',
                'Training Center',
                'Created At',
                'Updated At',
            ];
        } else {
            return [
                'ID',
                'Type d\'entreprise',
                'Statut',
                'Prénom Étudiant',
                'Nom Étudiant',
                'Sexe',
                'Centre de Formation',
                'Créé le',
                'Mis à jour le',
            ];
        }
    }

    public function map($businessStatus): array
    {
        $gender = $this->language === 'en'
            ? ($businessStatus->student->sexe == 'M' ? 'Male' : 'Female')
            : ($businessStatus->student->sexe == 'M' ? 'Masculin' : 'Féminin');

        return [
            $businessStatus->id,
            $businessStatus->type_of_business,
            $businessStatus->status,
            $businessStatus->student->first_name,
            $businessStatus->student->last_name,
            $gender,
            $businessStatus->site->designation ?? '',
            $businessStatus->created_at,
            $businessStatus->updated_at,
        ];
    }
}
