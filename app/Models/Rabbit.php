<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Rabbit extends Model
{
    use HasFactory;

    public const JENIS_KELAMIN = ['Jantan', 'Betina'];

    public const STATUS = ['Sehat', 'Bunting', 'Menyusui', 'Karantina', 'Afkir'];

    protected $fillable = [
        'cage_id',
        'nama_kelinci',
        'jenis_kelamin',
        'tgl_lahir',
        'status_kelinci',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    public function cage(): BelongsTo
    {
        return $this->belongsTo(Cage::class);
    }

    public function growthRecords(): HasMany
    {
        return $this->hasMany(GrowthRecord::class);
    }

    public function breedingRecordsAsMale(): HasMany
    {
        return $this->hasMany(BreedingRecord::class, 'male_rabbit_id');
    }

    public function breedingRecordsAsFemale(): HasMany
    {
        return $this->hasMany(BreedingRecord::class, 'female_rabbit_id');
    }

    public function getKodeAttribute(): string
    {
        return 'KL-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim((string) $this->nama_kelinci)) ?: [];
        $initials = '';

        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= Str::upper(Str::substr($word, 0, 1));
        }

        return $initials !== '' ? $initials : 'KL';
    }

    public function getUmurBulanAttribute(): int
    {
        return $this->tgl_lahir ? (int) $this->tgl_lahir->diffInMonths(Carbon::today()) : 0;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status_kelinci) {
            'Sehat', 'Menyusui' => 'rf-badge--success',
            'Bunting' => 'rf-badge--forest',
            'Karantina' => 'rf-badge--warning',
            'Afkir' => 'rf-badge--muted',
            default => 'rf-badge--sage',
        };
    }
}
