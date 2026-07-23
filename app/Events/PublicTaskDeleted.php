<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PublicTaskDeleted implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;


    public function __construct(
        public array $task
    ) {}


    public function broadcastOn(): array
    {
        return [
            new Channel('public-tasks')
        ];
    }
    public function broadcastAs(): string
    {
        return 'public.task.deleted';
    }


    public function broadcastWith(): array
    {
        return [
            'task' => $this->task
        ];
    }
}
