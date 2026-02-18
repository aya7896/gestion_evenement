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
        'color_template',
        'hero_appearance',
        'plaquette_pdf',
        'validation_superAdmin',
        'status',
        'visibility',
        'event_link',
        'image',
        'slug',
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

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug) && !empty($model->titre)) {
                $model->slug = \Illuminate\Support\Str::slug($model->titre);
            }
        });

        static::saving(function ($model) {
            if (empty($model->slug) && !empty($model->titre)) {
                $model->slug = \Illuminate\Support\Str::slug($model->titre);
            }
        });
    }

    /** Use slug for route model binding */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getRouteKey()
    {
        return $this->slug ?: (string) $this->getKey();
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?: $this->getRouteKeyName();

        if ($field === 'slug') {
            return $this->where('slug', $value)
                ->orWhere('id_event', $value)
                ->firstOrFail();
        }

        return parent::resolveRouteBinding($value, $field);
    }

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

    public function partenaires()
    {
        return $this->belongsToMany(Partenaire::class, 'event_partenaire', 'id_event', 'id_partenaire')
            ->withPivot(['contribution', 'montant'])
            ->withTimestamps();
    }
}
