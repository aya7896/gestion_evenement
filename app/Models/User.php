<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\SuperAdmin;
use App\Models\Collaborateur;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 🔹 Relations

    public function superAdmin()
    {
        return $this->hasOne(SuperAdmin::class, 'id_user');
    }

    public function collaborateurs()
    {
        return $this->hasMany(Collaborateur::class, 'id_user');
    }

    // 🔹 Helpers

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isCollaborateur()
    {
        return $this->role === 'collaborateur';
    }
}
