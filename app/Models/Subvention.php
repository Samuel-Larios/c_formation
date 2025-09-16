<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subvention extends Model
{
    use HasFactory;

    // Specify the table name explicitly if it's not the default
    protected $table = 'subvention'; // Corresponds to the table name in the database

    // Specify the fields allowed for mass insertion
    protected $fillable = [
        'start_up_kits',
        'grants',
        'loan',
        'date',
        'start_up_kits_items_received',
        'state_of_farm_location',
        'student_id',
        'site_id',
    ];

    // Relationship with the Student model (a student can have multiple subsidies)
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
