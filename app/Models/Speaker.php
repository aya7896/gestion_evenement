<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_speaker';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'bio',
        'photo',
        'company',
        'poste',
        'social_links',
        'adresse',
        'telephone',
        'actif',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'social_links' => 'array',
        'actif' => 'boolean',
    ];

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }

    /**
     * Get the ateliers associated with the speaker.
     */
    public function ateliers()
    {
        return $this->belongsToMany(Atelier::class, 'atelier_speaker', 'id_speaker', 'id_atelier')
                    ->withPivot(['role', 'ordre'])
                    ->withTimestamps();
    }

    /**
     * Get the evenements through ateliers.
     */
    public function evenements()
    {
        return $this->hasManyThrough(
            Evenement::class,
            Atelier::class,
            'id_atelier', // Foreign key on ateliers table
            'id_event',   // Foreign key on evenements table
            'id_speaker', // Local key on speakers table
            'id_event'    // Local key on ateliers table
        );
    }

    /**
     * Get the photo URL.
     */
    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            return null;
        }

        $photo = (string) $this->photo;

        if (filter_var($photo, FILTER_VALIDATE_URL)) {
            return $photo;
        }

        $normalized = preg_replace('#^(/)?(storage/|public/|storage/app/public/)#', '', str_replace('\\', '/', $photo));

        if ($normalized && \Storage::disk('public')->exists($normalized)) {
            return \Storage::url($normalized);
        }

        if (file_exists($photo)) {
            return asset($photo);
        }

        return null;
    }

    /**
     * Get initials for avatar.
     */
    public function getInitialsAttribute()
    {
        return strtoupper(substr($this->prenom, 0, 1) . substr($this->nom, 0, 1));
    }

    /**
     * Scope for active speakers.
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Get LinkedIn URL from social_links.
     */
    public function getLinkedinUrlAttribute()
    {
        return $this->social_links['linkedin'] ?? null;
    }

    /**
     * Get Twitter URL from social_links.
     */
    public function getTwitterUrlAttribute()
    {
        return $this->social_links['twitter'] ?? null;
    }
}
