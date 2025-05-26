<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use HasFactory;
    // Nom de la table
    protected $table = 'follow_up';

    protected $fillable = [
        'farm_visits',
        'phone_contact',
        'sharing_of_impact_stories',
        'back_stopping',
        'student_id',
        'site_id',
    ];

    // Relations
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
