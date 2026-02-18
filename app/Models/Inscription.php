<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_inscription';

    protected $fillable = [
        'id_user',
        'date_ins',
        'company',
        'photo',
        'presentation',
        'poste',
        'lien_linkedin',
        'objectif',
        'statut',
        'verification_code',
        'verification_expires_at',
        'verification_sent_at',
        'verification_method',
        'verification_attempts',
        'verified_at',
    ];

    protected $casts = [
        'date_ins' => 'datetime',
        'verification_expires_at' => 'datetime',
        'verification_sent_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public $timestamps = false;

    // 🔹 Relations

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function evenements()
    {
        return $this->belongsToMany(
            Evenement::class,
            'inscription_event',
            'id_inscription',
            'id_event'
        )->withTimestamps();
    }

    public function ateliers()
    {
        return $this->belongsToMany(
            Atelier::class,
            'inscription_atelier',
            'id_inscription',
            'id_atelier'
        )->withTimestamps();
    }
}
