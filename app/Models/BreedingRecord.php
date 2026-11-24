<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BreedingRecord extends Model
{
    use HasFactory;

    public const STATUS = [
        'Direncanakan',
        'Terlaksana (Bunting)',
        'Berhasil Melahirkan',
        'Gagal Kawin',
    ];

    protected $fillable = [
        'male_rabbit_id',
        'female_rabbit_id',
        'tgl_rencana_kawin',
        'tgl_realisasi_kawin',
        'status_perkawinan',
        'jumlah_anakan',
        'keterangan',
    ];

    protected $casts = [
        'tgl_rencana_kawin' => 'date',
        'tgl_realisasi_kawin' => 'date',
        'jumlah_anakan' => 'integer',
    ];

    public function maleRabbit(): BelongsTo
    {
        return $this->belongsTo(Rabbit::class, 'male_rabbit_id');
    }

    public function femaleRabbit(): BelongsTo
    {
        return $this->belongsTo(Rabbit::class, 'female_rabbit_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status_perkawinan) {
            'Berhasil Melahirkan' => 'rf-badge--success',
            'Terlaksana (Bunting)' => 'rf-badge--forest',
            'Gagal Kawin' => 'rf-badge--danger',
            'Direncanakan' => 'rf-badge--warning',
            default => 'rf-badge--sage',
        };
    }
}
