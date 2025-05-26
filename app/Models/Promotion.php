<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'num_promotion',
        'site_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    // public function students()
    // {
    //     return $this->belongsToMany(Student::class, 'promotion_apprenant', 'promotion_id', 'student_id');
    // }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'promotion_apprenant', 'promotion_id', 'student_id');
    }
    // Relation many-to-many avec Promotion
    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_apprenant', 'student_id', 'promotion_id');
    }

     // Relation avec la table pivot
     public function promotionApprenants(): HasMany
     {
         return $this->hasMany(PromotionApprenant::class);
     }

     // Relation many-to-many avec les étudiants
    //  public function students()
    //  {
    //      return $this->belongsToMany(Student::class, 'promotion_apprenants')
    //                 ->withPivot('site_id')
    //                 ->using(PromotionApprenant::class);
    //  }


}
