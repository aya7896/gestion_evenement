<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Atelier extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_atelier';

    protected $fillable = [
        'id_event',
        'titre',
        'visibility',
        'heure_debut',
        'heure_fin',
        'sujet',
        'banniere',
        'date',
        'image',
        'capacite',
        'status',
        'online_link',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'date' => 'date',
    ];

    // 🔹 Relations

    public function evenement()
    {
        return $this->belongsTo(Evenement::class, 'id_event');
    }
}
