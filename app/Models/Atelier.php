<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atelier extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_atelier';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_event',
        'titre',
        'heure_debut',
        'heure_fin',
        'banniere',
        'date',
        'capacite',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
    ];

    /**
     * Get the event that owns the atelier.
     */
    public function evenement()
    {
        return $this->belongsTo(Evenement::class, 'id_event');
    }
}