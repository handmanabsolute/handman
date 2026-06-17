<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RealtimeTugasEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $departemenId;
    public $action;
    public $title;
    public $message;
    public $userId;
    public $tugasId;

    public function __construct($departemenId, $action, $title, $message, $userId = null, $tugasId = null)
    {
        $this->departemenId = $departemenId;
        $this->action = $action;
        $this->title = $title;
        $this->message = $message;
        $this->userId = $userId;
        $this->tugasId = $tugasId;
    }

    public function broadcastOn()
    {
        return new Channel('departemen-' . $this->departemenId);
    }

    public function broadcastAs()
    {
        return 'RealtimeTugasEvent';
    }
}
