<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = ['designation', 'emplacement'];

    public function utilisateurs()
    {
        return $this->hasMany(Utilisateur::class);
    }
    // Dans le modèle Entity
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    // Relation avec les promotions
    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }


}
