<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'slug', 'active', 'public_key', 'secret_key'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function settings()
    {
        return $this->hasOne(\App\Models\WidgetSetting::class);
    }

    public function conversations()
    {
        return $this->hasMany(\App\Models\Conversation::class);
    }
}