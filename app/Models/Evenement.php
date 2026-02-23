<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evenement extends Model
{
    use HasFactory;

    public const LANDING_TEMPLATES = [
        'template_1' => [
            'name' => 'Template 1 - Corporate',
            'view' => 'landing.Template 1.index',
            'description' => 'Style corporate moderne.',
        ],
        'template_2' => [
            'name' => 'Template 2 - Luxury',
            'view' => 'landing.Template 2.index',
            'description' => 'Style premium et elegant.',
        ],
        'template_3' => [
            'name' => 'Template 3 - Tech',
            'view' => 'landing.Template 3.index',
            'description' => 'Style technologique et dynamique.',
        ],
        'template_4' => [
            'name' => 'Template 4 - Immersive',
            'view' => 'landing.Template 4.index',
            'description' => 'Style immersif et visuel.',
        ],
    ];

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
        'landing_template',
        'landing_content',
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
        'landing_content' => 'array',
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

    public function getLandingTemplateConfigAttribute(): array
    {
        $template = $this->landing_template ?: 'template_1';

        return self::LANDING_TEMPLATES[$template] ?? self::LANDING_TEMPLATES['template_1'];
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

    /**
     * Get the total number of inscriptions for this event.
     */
    public function getInscriptionsTotalAttribute()
    {
        return $this->inscriptions()->count();
    }

    /**
     * Get the number of verified inscriptions for this event.
     */
    public function getInscriptionsValidesAttribute()
    {
        return $this->inscriptions()->whereNotNull('verified_at')->count();
    }

    /**
     * Get the number of workshops for this event.
     */
    public function getAteliersCountAttribute()
    {
        return $this->ateliers()->count();
    }
}
