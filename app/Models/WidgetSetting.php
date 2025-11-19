<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetSetting extends Model
{
    protected $fillable = [
        'client_id',
        'primary_color',
        'secondary_color',
        'welcome_message',
        'online_message',
        'offline_message',
        'avatar_url',
        'open_hours',
    ];

    protected $casts = [
        'open_hours' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class);
    }
}