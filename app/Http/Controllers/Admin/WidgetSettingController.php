<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\WidgetSetting;
use Illuminate\Http\Request;

class WidgetSettingController extends Controller
{
    public function edit(Client $client)
    {
        $settings = $client->settings ?: new WidgetSetting(['client_id' => $client->id]);
        return view('admin.settings.edit', compact('client', 'settings'));
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'primary_color' => ['required', 'string', 'max:20'],
            'secondary_color' => ['required', 'string', 'max:20'],
            'welcome_message' => ['required', 'string', 'max:255'],
            'online_message' => ['required', 'string', 'max:255'],
            'offline_message' => ['required', 'string', 'max:255'],
            'avatar_url' => ['nullable', 'url', 'max:2048'],
            'open_hours' => ['nullable', 'string', 'max:255'], // JSON simples ou texto
        ]);

        $settings = $client->settings ?: new WidgetSetting(['client_id' => $client->id]);

        $settings->fill([
            'primary_color' => $data['primary_color'],
            'secondary_color' => $data['secondary_color'],
            'welcome_message' => $data['welcome_message'],
            'online_message' => $data['online_message'],
            'offline_message' => $data['offline_message'],
            'avatar_url' => $data['avatar_url'] ?? null,
            'open_hours' => $this->parseOpenHours($data['open_hours'] ?? null),
        ])->save();

        return redirect()->route('clients.show', $client)->with('status', 'Configurações atualizadas.');
    }

    private function parseOpenHours(?string $text): ?array
    {
        if (!$text) return null;
        // tenta decodificar JSON, ou faz um parse simples "09:00-18:00"
        $json = json_decode($text, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
            return $json;
        }
        return ['mon_fri' => $text]; // fallback
    }
}