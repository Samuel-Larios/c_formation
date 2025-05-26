<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subvention extends Model
{
    use HasFactory;

    // Spécifier explicitement le nom de la table si ce n'est pas le nom par défaut
    protected $table = 'subvention'; // Correspond au nom de la table dans la base de données

    // Spécifier les champs autorisés pour l'insertion en masse
    protected $fillable = [
        'start_up_kits',
        'grants',
        'loan',
        'date',
        'student_id',
        'site_id',
    ];

    // Relation avec le modèle Student (un étudiant peut avoir plusieurs subventions)
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

}
