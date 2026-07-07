<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActivityLog;
use Junges\Kafka\Facades\Kafka;

class ConsumeKafka extends Command
{
    protected $signature = 'kafka:consume';

    protected $description = 'Consume task.created events';

    public function handle()
    {
        $consumer = Kafka::consumer()
            ->subscribe('task.created')
            ->withHandler(function ($message) {

                ActivityLog::create([
                    'event' => 'task.created',
                    'data' => $message->getBody(),
                ]);

                $this->info('Task event saved');
            })
            ->build();

        $consumer->consume();
    }
}
