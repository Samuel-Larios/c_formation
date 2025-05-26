<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessStatus extends Model
{
    use HasFactory;

    // Spécifier le nom de la table
    protected $table = 'business_status'; // Nom de la table au singulier

    // Colonnes remplissables
    protected $fillable = [
        'type_of_business',
        'status',
        'student_id',
        'site_id',
    ];

    // Relation avec le modèle Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relation avec le modèle Site
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
