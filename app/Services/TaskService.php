<?php

namespace App\Services;

use App\Events\PublicTaskCreated;
use App\Events\PublicTaskDeleted;
use App\Events\PublicTaskUpdated;
use App\Models\Task;
use App\Events\TaskCreated;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Jobs\SendTaskCreatedEmailJob;
use Illuminate\Auth\Access\AuthorizationException;


class TaskService
{
    public function createTask(User $user, array $data): Task
    {
        if (($data['is_public'] ?? false) && !$user->hasPermission('create-public-tasks')) {
            throw new AuthorizationException(
                'You cannot create public tasks'
            );
        }
        $task = $user->tasks()->create($data);

        Cache::forget("user_{$user->id}_tasks");

        SendTaskCreatedEmailJob::dispatch($task);

        $task->load(['user', 'category']);

        if ($task->is_public) {
            event(new PublicTaskCreated($task));
        } else {
            event(new TaskCreated($task));
        }
        return $task;
    }

    public function updateTask(Task $task, array $data): Task
    {

        $task->update($data);
        $task->load([
            'user',
            'category'
        ]);

        Cache::forget("user_{$task->user_id}_tasks");
        if($task->is_public){
            event(
                new PublicTaskUpdated($task)
            );
        }
        return $task;
    }

    public function deleteTask(Task $task): void
    {
        $userId = $task->user_id;

        if($task->is_public){

            event(
                new PublicTaskDeleted([
                    'id' => $task->id,
                    'title' => $task->title,
                    'user' => $task->user->name,
                ])
            );

        }
       $task->delete();
        Cache::forget(
            "user_{$userId}_tasks"
        );

    }

}
