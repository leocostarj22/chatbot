<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['client_id', 'visitor_id', 'status'];

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class);
    }

    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class);
    }
}