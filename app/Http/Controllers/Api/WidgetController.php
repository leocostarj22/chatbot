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

        $settings = $client->settings ?: (object) [
            'primary_color' => '#2f80ed',
            'secondary_color' => '#ffffff',
            'welcome_message' => 'Olá! Como posso ajudar?',
            'online_message' => 'Estamos online',
            'offline_message' => 'Estamos offline, deixe sua mensagem',
            'avatar_url' => null,
            'open_hours' => null,
        ];

        return response()->json([
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
                'active' => $client->active,
            ],
            'settings' => $client->settings ?: $settings,
            'is_online' => $this->isOnline($client->settings?->open_hours),
        ]);
    }
    public function showByKey(string $publicKey)
    {
        $client = Client::with('settings')->where('public_key', $publicKey)->firstOrFail();

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
    private function isOnline(?array $openHours): bool
    {
        if (!$openHours) return true;
        $now = now();
        $dow = strtolower($now->format('D')); // mon, tue, ...
        $range = $openHours['mon_fri'] ?? null;

        if ($range) {
            $isWeekday = in_array($dow, ['mon','tue','wed','thu','fri']);
            if (!$isWeekday) return false;
            [$start, $end] = explode('-', $range);
            $startTime = $now->copy()->setTimeFromTimeString($start);
            $endTime = $now->copy()->setTimeFromTimeString($end);
            return $now->between($startTime, $endTime);
        }

        return true;
    }
}