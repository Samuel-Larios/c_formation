<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matier extends Model
{
    use HasFactory;

    protected $fillable = [
        'designation', 'coef', 'site_id',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

}
