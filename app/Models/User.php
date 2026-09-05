<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username', 'email', 'password', 'role', 'phone',
        'is_verified', 'profile_image', 'bio', 'location',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_verified' => 'boolean',
        'password'    => 'hashed',
    ];

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function savedAds()
    {
        return $this->belongsToMany(Ad::class, 'favorites')->withPivot('created_at');
    }

    public function buyerChats()
    {
        return $this->hasMany(Chat::class, 'buyer_id');
    }

    public function sellerChats()
    {
        return $this->hasMany(Chat::class, 'seller_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function getInitialAttribute(): string
    {
        return strtoupper(substr($this->username, 0, 1));
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }
}
