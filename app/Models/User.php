<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Subscription;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke tabel subscriptions
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    // Ambil subscription yang benar-benar aktif (sudah mulai & belum habis)
    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->orderByDesc('ends_at')
            ->first();
    }

    // Cek ada subscription aktif
    public function hasActiveSubscription(): bool
    {
        return (bool) $this->activeSubscription();
    }

    public function activePlan(): ?string
    {
        $sub = $this->activeSubscription();
        return $sub?->plan;
    }

    public function isPro(): bool
    {
        return $this->activePlan() === 'pro';
    }

    public function isBasic(): bool
    {
        return $this->activePlan() === 'basic';
    }
}
