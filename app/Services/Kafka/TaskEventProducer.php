<?php

namespace App\Services\Kafka;

use App\Models\Task;
use Junges\Kafka\Facades\Kafka;
use Illuminate\Support\Str;

class TaskEventProducer
{
    public function taskCreated(Task $task): void
    {
        $this->sendEvent(
            'task.created',
            [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'created_at' => $task->created_at,
            ],
            (string)$task->id
        );
            }


    public function taskUpdated(Task $task): void
    {
        $this->sendEvent(
            'task.updated',
            [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'updated_at' => $task->updated_at,
            ],
            (string) $task->id
        );
    }


    public function taskDeleted(int $taskId): void
    {
        $this->sendEvent(
            'task.deleted',
            [
                'id' => $taskId,
            ],
            (string) $taskId
        );
    }


    private function sendEvent(string $event, array $data, string $key): void
    {
        Kafka::publish()
            ->onTopic(config('kafka.task_topic'))
            ->withKafkaKey($key)
            ->withBody([
                'event_id' => Str::uuid()->toString(),
                'event' => $event,
                'occurred_at' => now()->toISOString(),
                'data' => $data,
            ])
            ->send();
    }
}
