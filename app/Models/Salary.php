<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'entreprise', 'localisation', 'employeur', 'tel', 'suivit', 'suivit_observation',
        'visite', 'visite_observation', 'student_id'
    ];

    /**
     * Définir la relation avec l'étudiant.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
}
