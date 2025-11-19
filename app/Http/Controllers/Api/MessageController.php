<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageCreated;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $conversationId = (int) $request->query('conversation_id');
        $since = $request->query('since'); // ISO timestamp opcional

        $query = Message::where('conversation_id', $conversationId)->orderBy('created_at', 'asc');

        if ($since) {
            $query->where('created_at', '>', $since);
        }

        return response()->json([
            'messages' => $query->limit(100)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'visitor_id' => 'nullable|string',
            'conversation_id' => 'nullable|integer|exists:conversations,id',
            'sender_type' => 'required|in:visitor,operator,system',
            'content' => 'required|string|max:5000',
        ]);

        $conversation = null;

        if (!empty($data['conversation_id'])) {
            $conversation = Conversation::findOrFail($data['conversation_id']);
        } else {
            // cria/recupera conversa por client_id + visitor_id
            if (empty($data['visitor_id'])) {
                return response()->json(['error' => 'visitor_id requerido quando conversation_id não é informado'], 422);
            }
            $conversation = Conversation::firstOrCreate(
                ['client_id' => $data['client_id'], 'visitor_id' => $data['visitor_id']],
                ['status' => 'open']
            );
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => $data['sender_type'],
            'sender_id' => $data['sender_type'] === 'operator' ? auth()->id() : null,
            'content' => $data['content'],
        ]);

        // Broadcast para painel (operadores em tempo real)
        MessageCreated::dispatch($conversation->id, [
            'id' => $message->id,
            'sender_type' => $message->sender_type,
            'content' => $message->content,
            'created_at' => $message->created_at,
        ]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'message' => $message,
        ], 201);
    }
}