<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PublicTaskCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct(
        public Task $task
    ) {}


    public function broadcastOn(): array
    {
        return [
            new Channel('public-tasks'),
        ];
    }


    public function broadcastWith(): array
    {
        return [
            'task' => [
                'id' => $this->task->id,
                'title' => $this->task->title,
                'description' => $this->task->description,
                'status' => $this->task->status,
                'category' => $this->task->category?->name,
                'user' => $this->task->user->name,
            ]
        ];
    }

    public function broadcastAs(): string
    {
        return 'public.task.created';
    }
}
