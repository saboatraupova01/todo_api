<?php

namespace App\Providers;

use App\Events\TaskCreated;
use App\Listeners\LogTaskCreated;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TaskCreated::class => [
        LogTaskCreated::class,
        ],
    ];
}
