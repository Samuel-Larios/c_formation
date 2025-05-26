<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;



class StudentsImport implements ToModel
{
    public function model(array $row)
    {
        return new Student([
            'first_name' => $row[1],
            'last_name' => $row[2],
            'sexe' => $row[3],
            'situation_matrimoniale' => $row[4],
            'situation_handicape' => $row[5],
            'date_naissance' => $row[6],
            'contact' => $row[7],
            'contact_pers1' => $row[8],
            'contact_pers2' => $row[9],
            'contact_pers3' => $row[10],
            'contact_pers4' => $row[11],
            'contact_pers5' => $row[12],
            'email' => $row[13],
            'password' => bcrypt($row[14]),
            'state_of_origin' => $row[15],
            'state_of_residence' => $row[16],
            'state' => $row[17],
            'lga' => $row[18],
            'community' => $row[19],
            'site_id' => $row[20],
        ]);
    }
}
