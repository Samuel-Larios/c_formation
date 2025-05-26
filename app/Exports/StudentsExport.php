<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
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
        return Student::with(['site', 'promotions'])
            ->where('site_id', $this->siteId)
            ->whereHas('promotions', function($query) {
                $query->where('promotions.id', $this->promotionId);
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Prénom',
            'Nom',
            'Sexe',
            'Situation Matrimoniale',
            'Situation Handicapé',
            'Date de Naissance',
            'Contact',
            'Contact Pers1',
            'Contact Pers2',
            'Contact Pers3',
            'Contact Pers4',
            'Contact Pers5',
            'Email',
            'État d\'origine',
            'État de résidence',
            'État',
            'LGA',
            'Communauté',
            'Site',
            'Promotion',
            'Créé le',
            'Mis à jour le',
        ];
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->first_name,
            $student->last_name,
            $student->sexe == 'M' ? 'Masculin' : 'Féminin',
            $student->situation_matrimoniale,
            $student->situation_handicape,
            $student->date_naissance,
            $student->contact,
            $student->contact_pers1,
            $student->contact_pers2,
            $student->contact_pers3,
            $student->contact_pers4,
            $student->contact_pers5,
            $student->email,
            $student->state_of_origin,
            $student->state_of_residence,
            $student->state,
            $student->lga,
            $student->community,
            $student->site->designation ?? '',
            $student->promotions->first()->num_promotion ?? '',
            $student->created_at,
            $student->updated_at,
        ];
    }
}
