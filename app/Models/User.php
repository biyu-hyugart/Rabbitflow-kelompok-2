<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'role_id',
        'nama_lengkap',
        'username',
        'password',
        'status_aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function isOwner(): bool
    {
        return $this->role?->nama_role === Role::OWNER;
    }

    public function isKaryawan(): bool
    {
        return $this->role?->nama_role === Role::KARYAWAN;
    }

    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim((string) $this->nama_lengkap)) ?: [];
        $initials = '';

        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= Str::upper(Str::substr($word, 0, 1));
        }

        return $initials !== '' ? $initials : Str::upper(Str::substr((string) $this->username, 0, 2));
    }

    public function getRoleNameAttribute(): string
    {
        return $this->role?->nama_role ?? '-';
    }
}
