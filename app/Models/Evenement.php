<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evenement extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_event';

    protected $fillable = [
        'id_Collaborateur',
        'id_entreprise',
        'titre',
        'capacite',
        'description',
        'type',
        'localisation',
        'lieu',
        'date_heure_debut',
        'date_heure_fin',
        'mode',
        'plaquette_pdf',
        'validation_superAdmin',
        'status',
        'visibility',
        'event_link',
        'image',
    ];

    /**
     * Attribute casting for proper date handling
     *
     * @var array<string,string>
     */
    protected $casts = [
        'date_heure_debut' => 'datetime',
        'date_heure_fin' => 'datetime',
        'validation_superAdmin' => 'boolean',
    ];

    // ðŸ”¹ Relations

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'id_entreprise');
    }

    public function collaborateur()
    {
        return $this->belongsTo(Collaborateur::class, 'id_Collaborateur');
    }

    public function ateliers()
    {
        return $this->hasMany(Atelier::class, 'id_event');
    }

    public function inscriptions()
    {
        return $this->belongsToMany(
            Inscription::class,
            'inscription_event',
            'id_event',
            'id_inscription'
        )->withTimestamps();
    }
}