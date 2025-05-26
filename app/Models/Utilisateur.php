<?php

namespace App\Models;

// use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Utiliser Authenticatable

// class Utilisateur extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//         'name', 'date_naissance', 'tel', 'tel2', 'poste', 'role', 'email', 'password', 'site_id',
//     ];

//     protected $hidden = [
//         'password', 'remember_token', // Masquer le mot de passe et le token
//     ];

//     public function site()
//     {
//         return $this->belongsTo(Site::class);
//     }
// }


class Utilisateur extends Authenticatable // Étendre Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name', 'date_naissance', 'tel', 'tel2', 'poste', 'role', 'email', 'password', 'site_id',
    ];

    protected $hidden = [
        'password', 'remember_token', // Masquer le mot de passe et le token
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
