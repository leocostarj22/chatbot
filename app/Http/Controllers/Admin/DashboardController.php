<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $clientId = auth()->user()->client_id ?? null;

        $conversations = Conversation::when($clientId, fn($q) => $q->where('client_id', $clientId))
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return view('admin.dashboard', [
            'conversations' => $conversations,
        ]);
    }
}