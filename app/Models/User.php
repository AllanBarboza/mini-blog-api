<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'username',
        'password',
        'biography',
        'banned_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'banned_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isBanned(): bool
    {
        return !is_null($this->banned_at);
    }

    public function ban(): void
    {
        $this->update(['banned_at' => now()]);
    }

    public function unban(): void
    {
        $this->update(['banned_at' => null]);
    }
}
