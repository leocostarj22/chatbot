<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $conversations = Conversation::where('client_id', $user->client_id)
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('admin.conversations.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        if ($conversation->client_id !== auth()->user()->client_id) {
            abort(403);
        }
        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();
        return view('admin.conversations.show', compact('conversation', 'messages'));
    }
}