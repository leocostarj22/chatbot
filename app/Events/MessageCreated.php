<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageCreated implements ShouldBroadcastNow
{
    public function __construct(
        public int $conversationId,
        public array $payload
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('conversation.' . $this->conversationId)];
    }

    public function broadcastAs(): string
    {
        return 'message.created';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}