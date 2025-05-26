<?php

namespace App\Models;

use App\Models\Entity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCreation extends Model
{
    use HasFactory;

    // Spécifie la table associée au modèle
    protected $table = 'job_creations';

    // Définir les champs qui peuvent être remplis
    protected $fillable = [
        'nom',
        'tel',
        'student_id'
    ];

    /**
     * Relation inverse: Un job creation appartient à une entité.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

}
