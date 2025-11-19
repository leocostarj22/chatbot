<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                'public_key' => 'pub_' . Str::random(24),
                'secret_key' => 'sec_' . Str::random(32),
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

        // Usuários demo vinculados ao cliente 1001
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@demo.test'],
            [
                'name' => 'Demo Admin',
                'email' => 'admin@demo.test',
                'password' => bcrypt('secret123'),
                'client_id' => 1001,
                'role' => 'admin',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'operator@demo.test'],
            [
                'name' => 'Demo Operator',
                'email' => 'operator@demo.test',
                'password' => bcrypt('secret123'),
                'client_id' => 1001,
                'role' => 'operator',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}