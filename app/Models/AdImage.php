<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdImage extends Model
{
    public $timestamps = false;
    protected $fillable = ['ad_id', 'image_url', 'is_primary'];
    protected $casts = ['is_primary' => 'boolean'];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}
