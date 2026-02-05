<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription_atelier extends Model
{
    use HasFactory;

    protected $table = 'inscription_atelier';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_inscription',
        'id_atelier',
    ];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class, 'id_inscription', 'id_inscription');
    }

    public function atelier()
    {
        return $this->belongsTo(Atelier::class, 'id_atelier', 'id_atelier');
    }
}
