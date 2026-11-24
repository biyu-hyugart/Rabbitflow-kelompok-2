<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrowthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'rabbit_id',
        'tgl_pencatatan',
        'berat_badan',
        'panjang_badan',
        'status_kelinci',
        'kondisi_kesehatan',
        'keterangan',
    ];

    protected $casts = [
        'tgl_pencatatan' => 'date',
        'berat_badan' => 'decimal:2',
        'panjang_badan' => 'decimal:1',
    ];

    public function rabbit(): BelongsTo
    {
        return $this->belongsTo(Rabbit::class);
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
