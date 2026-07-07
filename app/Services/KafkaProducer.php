<?php

namespace App\Services;

use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class KafkaProducer
{
    public function sendTaskCreated(array $task): void
    {
        Kafka::publish('kafka')
            ->onTopic('task.created')
            ->withMessage(
                new Message(
                    body: $task
                )
            )
            ->send();
    }
}
