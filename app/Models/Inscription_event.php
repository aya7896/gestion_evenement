<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription_event extends Model
{
    use HasFactory;

    protected $table = 'inscription_event';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_inscription',
        'id_event',
    ];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class, 'id_inscription', 'id_inscription');
    }

    public function evenement()
    {
        return $this->belongsTo(Evenement::class, 'id_event', 'id_event');
    }
}
