<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PromotionApprenant extends Model
{
    use HasFactory;

    // Si le nom de la table est différent de "promotion_apprenants"
    protected $table = 'promotion_apprenant'; // Spécifiez le nom de la table ici

    protected $fillable = [
        'promotion_id',
        'student_id',
        'site_id',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
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
