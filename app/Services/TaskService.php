<?php

namespace App\Services;

use App\Models\Task;
use App\Events\TaskCreated;

class TaskService
{
    public function createTask($user, array $data): Task
    {
        $task = $user->tasks()
            ->create($data);

        event(new TaskCreated($task));

        return $task;
    }
}
