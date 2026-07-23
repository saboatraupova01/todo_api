<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class PublicTaskUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct(
        public array $task
    ) {}


    public function broadcastOn(): array
    {
        return [
            new Channel('public-tasks'),
        ];
    }


    public function broadcastAs(): string
    {
        return 'public.task.updated';
    }


    public function broadcastWith(): array
    {
        return [
            'task' => $this->task
        ];
    }
}
