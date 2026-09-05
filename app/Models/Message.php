<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public $timestamps = false;
    protected $fillable = ['chat_id', 'sender_id', 'message', 'image_url', 'is_read'];
    protected $casts = ['is_read' => 'boolean', 'created_at' => 'datetime'];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
