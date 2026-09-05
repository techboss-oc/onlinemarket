<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'location_id', 'title', 'description',
        'price', 'currency', 'condition_state', 'brand', 'status',
        'is_featured', 'is_top_ad', 'last_boosted_at', 'is_urgent', 'views_count',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_top_ad'   => 'boolean',
        'last_boosted_at' => 'datetime',
        'is_urgent'   => 'boolean',
        'price'       => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function images()
    {
        return $this->hasMany(AdImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(AdImage::class)->where('is_primary', true);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function getFormattedPriceAttribute(): string
    {
        return '₦' . number_format($this->price);
    }
}
