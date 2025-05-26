<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'sexe',
        'situation_matrimoniale',
        'situation_handicape',
        'date_naissance',
        'contact',
        'contact_pers1',
        'contact_pers2',
        'contact_pers3',
        'contact_pers4',
        'contact_pers5',
        'email',
        'password',
        'state_of_origin',
        'state_of_residence',
        'state',
        'lga',
        'community',
        'site_id',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function entities()
    {
        return $this->hasMany(Entity::class);
    }


    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
    // Relation many-to-many avec Promotion
    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_apprenant', 'student_id', 'promotion_id');
    }
    public function activites()
    {
        return $this->hasMany(Entity::class, 'student_id');
    }


    // Relation avec la table job_creations
    public function jobCreations(): HasMany
    {
        return $this->hasMany(JobCreation::class);
    }
    // Modèle Student.php
    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }
    // Modèle Student.php
    public function subventions(): HasMany
    {
        return $this->hasMany(Subvention::class);
    }
    // Modèle Student.php
    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }
    // Modèle Student.php
    public function businessStatuses(): HasMany
    {
        return $this->hasMany(BusinessStatus::class);
    }

    public function specialites()
    {
        return $this->belongsToMany(Specialite::class, 'specializations', 'student_id', 'specialite_id')
                    ->withPivot('site_id')
                    ->withTimestamps();
    }

    public function specializations()
    {
        return $this->hasMany(Specialization::class);
    }

}
