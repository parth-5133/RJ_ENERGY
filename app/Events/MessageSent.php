<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    public $userId;

    public function __construct($message, $userId)
    {
        $this->message = $message;
        $this->userId = (int)$userId;
    }

    public function broadcastOn()
    {
        $channel = new Channel('Notifications.' . $this->userId);

        return $channel;
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}
