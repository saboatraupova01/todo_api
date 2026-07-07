<?php

namespace App\Jobs;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user,
        public string $password
    ) {}

    public function handle(): void
    {
        Log::info("Sending Welcome Email");
        Mail::to($this->user->email)
            ->send(new WelcomeMail(
                $this->user,
                $this->password
            ));
    }
}
