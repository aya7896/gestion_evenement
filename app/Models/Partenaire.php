<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_partenaire';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'ordre',
        'email',
        'telephone',
        'contrat',
        'logo',
        'description',
        'site_web',
        'type',
        'actif',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'actif' => 'boolean',
        'ordre' => 'integer',
    ];

    /**
     * Types de partenaires disponibles.
     */
    const TYPES = [
        'gold' => 'Gold',
        'silver' => 'Silver',
        'bronze' => 'Bronze',
        'media' => 'Média',
        'institutionnel' => 'Institutionnel',
        'autre' => 'Autre',
    ];

    /**
     * Get the events associated with the partenaire.
     */
    public function evenements()
    {
        return $this->belongsToMany(Evenement::class, 'event_partenaire', 'id_partenaire', 'id_event')
                    ->withPivot(['contribution', 'montant'])
                    ->withTimestamps();
    }

    /**
     * Get the logo URL.
     */
    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return null;
        }

        $logo = (string) $this->logo;

        if (filter_var($logo, FILTER_VALIDATE_URL)) {
            return $logo;
        }

        $normalized = preg_replace('#^(/)?(storage/|public/|storage/app/public/)#', '', str_replace('\\', '/', $logo));

        if ($normalized && \Storage::disk('public')->exists($normalized)) {
            return \Storage::url($normalized);
        }

        if (file_exists($logo)) {
            return asset($logo);
        }

        return null;
    }

    /**
     * Scope for active partenaires.
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope ordered by ordre field.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre', 'asc');
    }
}
