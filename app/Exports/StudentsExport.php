<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $siteId;
    protected $filters;
    protected $language;

    public function __construct($siteId, $filters, $language = 'fr')
    {
        $this->siteId = $siteId;
        $this->filters = $filters;
        $this->language = $language;
    }

    public function collection()
    {
        $query = Student::with(['site', 'promotions'])
            ->where('site_id', $this->siteId);

        if (!empty($this->filters['search'])) {
            $searchTerm = $this->filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'LIKE', "%$searchTerm%")
                    ->orWhere('last_name', 'LIKE', "%$searchTerm%")
                    ->orWhere('email', 'LIKE', "%$searchTerm%")
                    ->orWhere('contact', 'LIKE', "%$searchTerm%");
            });
        }

        if (!empty($this->filters['sexe'])) {
            $query->where('sexe', $this->filters['sexe']);
        }

        if (!empty($this->filters['promotion_id'])) {
            $promotionId = $this->filters['promotion_id'];
            $studentsInPromotion = \App\Models\PromotionApprenant::where('promotion_id', $promotionId)
                ->pluck('student_id')
                ->toArray();

            if (!empty($studentsInPromotion)) {
                $query->whereIn('id', $studentsInPromotion);
            } else {
                $query->whereRaw('0 = 1'); // Aucun étudiant dans cette promotion
            }
        }

        if (!empty($this->filters['specialite_id'])) {
            $specialiteId = $this->filters['specialite_id'];
            $studentsInSpecialite = \App\Models\Specialization::where('specialite_id', $specialiteId)
                ->pluck('student_id')
                ->toArray();

            if (!empty($studentsInSpecialite)) {
                $query->whereIn('id', $studentsInSpecialite);
            } else {
                $query->whereRaw('0 = 1'); // Aucun étudiant dans cette spécialité
            }
        }

        if (!empty($this->filters['state_of_origin'])) {
            $query->where('state_of_origin', $this->filters['state_of_origin']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        if ($this->language === 'en') {
            return [
                'ID',
                'First Name',
                'Last Name',
                'Gender',
                'Marital Status',
                'Disability Status',
                'Date of Birth',
                'Age',
                'Contact',
                'Contact Pers1',
                'Contact Pers2',
                'Contact Pers3',
                'Contact Pers4',
                'Contact Pers5',
                'Email',
                'State of Origin',
                'State of Residence',
                'State',
                'LGA',
                'Community',
                'Site',
                'Promotion',
                'Created At',
                'Updated At',
            ];
        } else {
            return [
                'ID',
                'Prénom',
                'Nom',
                'Sexe',
                'Situation Matrimoniale',
                'Situation Handicapé',
                'Date de Naissance',
                'Âge',
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
    }

    public function map($student): array
    {
        $gender = $this->language === 'en'
            ? ($student->sexe == 'M' ? 'Male' : 'Female')
            : ($student->sexe == 'M' ? 'Masculin' : 'Féminin');

        $age = null;
        if ($student->date_naissance) {
            $dob = \Carbon\Carbon::parse($student->date_naissance);
            $age = $dob->age;
        }

        return [
            $student->id,
            $student->first_name,
            $student->last_name,
            $gender,
            $student->situation_matrimoniale,
            $student->situation_handicape,
            $student->date_naissance,
            $age,
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
