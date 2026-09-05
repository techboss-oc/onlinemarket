<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'slug', 'type', 'parent_id'];

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }
}
