<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = ['specialite_id', 'student_id', 'site_id'];

    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
