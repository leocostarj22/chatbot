<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    public function show($clientId)
    {
        $client = Client::with('settings')->findOrFail($clientId);

        return response()->json([
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
                'active' => $client->active,
            ],
            'settings' => $client->settings ?: [
                'primary_color' => '#2f80ed',
                'secondary_color' => '#ffffff',
                'welcome_message' => 'Olá! Como posso ajudar?',
                'online_message' => 'Estamos online',
                'offline_message' => 'Estamos offline, deixe sua mensagem',
                'avatar_url' => null,
                'open_hours' => null,
            ],
        ]);
    }
}