<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entreprise extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_entreprise';

    protected $fillable = [
        'nom',
        'logo',
        'site_web',
        'email',
        'tel',
        'description',
        'adresse',
        'ville',
        'secteur_activite',
        'taille_entreprise',
        'status',
    ];

    // 🔹 Relations

    public function collaborateurs()
    {
        return $this->hasMany(Collaborateur::class, 'id_entreprise');
    }

    public function evenements()
    {
        return $this->hasMany(Evenement::class, 'id_entreprise');
    }
}
