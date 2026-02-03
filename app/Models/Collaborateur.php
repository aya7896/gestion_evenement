<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collaborateur extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_Collaborateur';

    protected $fillable = [
        'id_user',
        'id_entreprise',
        'active',
        'role',
    ];

    // ðŸ”¹ Relations

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'id_entreprise');
    }

    public function evenements()
    {
        return $this->hasMany(Evenement::class, 'id_Collaborateur');
    }
}