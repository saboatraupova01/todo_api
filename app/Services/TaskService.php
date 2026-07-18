<?php

namespace App\Services;

use App\Models\Task;
use App\Events\TaskCreated;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendTaskCreatedEmailJob;


class TaskService
{
    public function createTask(User $user, array $data): Task
    {
        $task = $user->tasks()
            ->create($data);

        Cache::forget("user_{$user->id}_tasks");

        SendTaskCreatedEmailJob::dispatch($task);
        event(new TaskCreated($task));

        return $task;
    }

    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);

        Cache::forget("user_{$task->user_id}_tasks");

        return $task;
    }

    public function deleteTask(Task $task): void
    {
        $userId = $task->user_id;

        $task->delete();

        Cache::forget("user_{$userId}_tasks");
    }



}
