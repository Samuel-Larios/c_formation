<?php

namespace App\Exports;

use App\Models\Student;

class StudentsWordExport
{
    protected $siteId;
    protected $promotionId;
    protected $language;

    public function __construct($siteId, $promotionId, $language = 'fr')
    {
        $this->siteId = $siteId;
        $this->promotionId = $promotionId;
        $this->language = $language;
    }

    public function export()
    {
        $students = Student::with(['site', 'promotions'])
            ->where('site_id', $this->siteId)
            ->whereHas('promotions', function($query) {
                $query->where('promotions.id', $this->promotionId);
            })
            ->get();

        // Generate HTML content that can be opened as Word document
        $html = $this->generateHtmlContent($students);

        $fileName = 'students_' . now()->format('Ymd_His') . '.doc';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);

        file_put_contents($tempFile, $html);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    private function generateHtmlContent($students)
    {
        $title = $this->language === 'en' ? 'Students List' : 'Liste des étudiants';
        $promotionLabel = $this->language === 'en' ? 'Promotion ID' : 'Promotion ID';

        $headers = $this->language === 'en' ? [
            'ID',
            'First Name',
            'Last Name',
            'Gender',
            'Marital Status',
            'Disability Status',
            'Date of Birth',
            'Contact',
            'Email',
            'State of Origin',
            'State of Residence',
            'Site',
            'Promotion',
        ] : [
            'ID',
            'Prénom',
            'Nom',
            'Sexe',
            'Situation Matrimoniale',
            'Situation Handicapé',
            'Date de Naissance',
            'Contact',
            'Email',
            'État d\'origine',
            'État de résidence',
            'Site',
            'Promotion',
        ];

        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . $title . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <h1>' . $title . ' - ' . $promotionLabel . ': ' . $this->promotionId . '</h1>
    <table>
        <thead>
            <tr>';

        foreach ($headers as $header) {
            $html .= '<th>' . $header . '</th>';
        }

        $html .= '</tr>
        </thead>
        <tbody>';

        foreach ($students as $student) {
            $gender = $this->language === 'en'
                ? ($student->sexe == 'M' ? 'Male' : 'Female')
                : ($student->sexe == 'M' ? 'Masculin' : 'Féminin');

            $html .= '<tr>';
            $html .= '<td>' . $student->id . '</td>';
            $html .= '<td>' . htmlspecialchars($student->first_name) . '</td>';
            $html .= '<td>' . htmlspecialchars($student->last_name) . '</td>';
            $html .= '<td>' . $gender . '</td>';
            $html .= '<td>' . htmlspecialchars($student->situation_matrimoniale) . '</td>';
            $html .= '<td>' . htmlspecialchars($student->situation_handicape) . '</td>';
            $html .= '<td>' . ($student->date_naissance ? $student->date_naissance->format('Y-m-d') : '') . '</td>';
            $html .= '<td>' . htmlspecialchars($student->contact) . '</td>';
            $html .= '<td>' . htmlspecialchars($student->email) . '</td>';
            $html .= '<td>' . htmlspecialchars($student->state_of_origin) . '</td>';
            $html .= '<td>' . htmlspecialchars($student->state_of_residence) . '</td>';
            $html .= '<td>' . htmlspecialchars($student->site->designation ?? '') . '</td>';
            $html .= '<td>' . htmlspecialchars($student->promotions->first()->num_promotion ?? '') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>
    </table>
</body>
</html>';

        return $html;
    }
}
