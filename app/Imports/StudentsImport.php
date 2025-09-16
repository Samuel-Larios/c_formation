<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Site;
use App\Models\Promotion;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentsImport implements ToModel
{
    public function model(array $row)
    {
        // Skip header row if it exists
        if ($row[0] == 'ID' || empty($row[1])) {
            return null;
        }

        // Find site by designation
        $site = Site::where('designation', $row[20])->first();
        if (!$site) {
            // If site not found, skip or handle error
            return null;
        }

        // Find promotion by num_promotion if provided
        $promotion = null;
        if (!empty($row[21])) {
            $promotion = Promotion::where('num_promotion', $row[21])->first();
        }

        // Generate password if not provided
        $password = !empty($row[14]) ? Hash::make($row[14]) : Hash::make(Str::random(10));

        $studentData = [
            'first_name' => $row[1],
            'last_name' => $row[2],
            'sexe' => $row[3],
            'situation_matrimoniale' => $row[4],
            'situation_handicape' => $row[5],
            'date_naissance' => $row[6] ? date('Y-m-d', strtotime($row[6])) : null,
            'contact' => $row[7],
            'contact_pers1' => $row[8],
            'contact_pers2' => $row[9],
            'contact_pers3' => $row[10],
            'contact_pers4' => $row[11],
            'contact_pers5' => $row[12],
            'email' => $row[13],
            'password' => $password,
            'state_of_origin' => $row[15],
            'state_of_residence' => $row[16],
            'state' => $row[17],
            'lga' => $row[18],
            'community' => $row[19],
            'site_id' => $site->id,
        ];

        $student = Student::create($studentData);

        // If promotion found, associate student with promotion
        if ($promotion) {
            $student->promotions()->attach($promotion->id);
        }

        return $student;
    }
}
