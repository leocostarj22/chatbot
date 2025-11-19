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
        $conversations = Conversation::orderByDesc('created_at')->paginate(20);
        return view('admin.conversations.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();
        return view('admin.conversations.show', compact('conversation', 'messages'));
    }
}