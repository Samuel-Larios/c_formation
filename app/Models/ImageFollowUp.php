<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageFollowUp extends Model
{
    use HasFactory;

    protected $table = 'image_follow_up';

    protected $fillable = [
        'follow_up_id',
        'image_path',
    ];

    public function followUp()
    {
        return $this->belongsTo(FollowUp::class);
    }
}
