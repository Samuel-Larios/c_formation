<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    // Indique que l'on peut remplir ces champs via des requêtes de masse (Mass Assignment)
    protected $fillable = [
        'note',
        'student_id',
        'matier_id',
    ];

    // Relation avec le modèle Student (un étudiant peut avoir plusieurs évaluations)
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relation avec le modèle Matier (une matière peut avoir plusieurs évaluations)
    public function matier()
    {
        return $this->belongsTo(Matier::class);
    }
}
