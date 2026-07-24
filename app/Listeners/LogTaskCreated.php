<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use Illuminate\Support\Facades\Log;

class LogTaskCreated
{
    public function handle(TaskCreated $event): void
    {
        Log::info('Task created', [
            'task_id' => $event->task->id,
            'title' => $event->task->title,
            'user_id' => $event->task->user_id,
        ]);
    }
}
