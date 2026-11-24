<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogisticsItem extends Model
{
    use HasFactory;

    public const STOK_MENIPIS = 5;

    protected $fillable = [
        'category_id',
        'nama_item',
        'stok',
        'status_aktif',
    ];

    protected $casts = [
        'stok' => 'integer',
        'status_aktif' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(LogisticsCategory::class, 'category_id');
    }
}
