<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LogisticsCategory extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kategori'];

    public function items(): HasMany
    {
        return $this->hasMany(LogisticsItem::class, 'category_id');
    }
}
