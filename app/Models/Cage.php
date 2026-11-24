<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cage extends Model
{
    use HasFactory;

    protected $fillable = [
        'lokasi_blok',
        'kapasitas',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
    ];

    public function rabbits(): HasMany
    {
        return $this->hasMany(Rabbit::class);
    }

    public function getKodeAttribute(): string
    {
        return 'KDG-' . str_pad((string) $this->id, 3, '0', STR_PAD_LEFT);
    }
}
