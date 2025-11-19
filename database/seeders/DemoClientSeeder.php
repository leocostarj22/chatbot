<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoClientSeeder extends Seeder
{
    public function run(): void
    {
        // Cria/atualiza client com ID fixo 1001 para facilitar testes
        DB::table('clients')->updateOrInsert(
            ['id' => 1001],
            [
                'name' => 'Demo Client',
                'slug' => 'demo',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Configurações básicas do widget para o cliente demo
        DB::table('widget_settings')->updateOrInsert(
            ['client_id' => 1001],
            [
                'primary_color' => '#2f80ed',
                'secondary_color' => '#ffffff',
                'welcome_message' => 'Olá! Como posso ajudar?',
                'online_message' => 'Estamos online',
                'offline_message' => 'Estamos offline, deixe sua mensagem',
                'avatar_url' => null,
                'open_hours' => json_encode(['mon_fri' => '09:00-18:00']),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}