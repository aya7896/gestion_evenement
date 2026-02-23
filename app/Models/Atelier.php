<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atelier extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_atelier';

    protected $fillable = [
        'id_event',
        'titre',
        'heure_debut',
        'heure_fin',
        'banniere',
        'date',
        'capacite',
        'sujet',        // ← seul champ texte long existant en BDD (pas "description")
        'visibility',
        'status',
        'online_link',
        'image',
    ];

    protected $casts = [
        'date'       => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin'  => 'datetime:H:i',
    ];

    
    public function evenement()
    {
        return $this->belongsTo(Evenement::class, 'id_event');
    }

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class, 'atelier_speaker', 'id_atelier', 'id_speaker')
            ->withPivot(['role', 'ordre'])
            ->withTimestamps();
    }
}
