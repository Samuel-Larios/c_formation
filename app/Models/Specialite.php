<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Specialite extends Model
{
    use HasFactory;

    protected $fillable = ['designation', 'site_id'];

    public function specializations()
    {
        return $this->hasMany(Specialization::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    // Modèle Specialite.php
    // public function students() 
    // {
    //     return $this->belongsToMany(Student::class, 'specializations', 'specialite_id', 'student_id');
    // }
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'specializations', 'specialite_id', 'student_id');
    }
}
