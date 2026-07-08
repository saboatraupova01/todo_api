<?php

namespace App\Services\Kafka;

use App\Models\User;
use Illuminate\Support\Str;
use Junges\Kafka\Facades\Kafka;

class UserEventProducer
{
    public function userCreated(User $user): void
    {
        $this->sendEvent(
            'user.created',
            [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
            (string) $user->id
        );
    }
    public function userUpdated(User $user): void
    {
        $this->sendEvent(
            'user.updated',
            [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'updated_at' => $user->updated_at,
            ],
            (string) $user->id
        );
    }
    public function roleAssigned(User $user, $role): void
    {
        $this->sendEvent(
            'user.role.assigned',
            [
                'user_id' => $user->id,
                'username' => $user->username,
                'role_id' => $role->id,
                'role_name' => $role->name,
            ],
            (string) $user->id
        );
    }
    public function permissionAssigned(User $user, $permission): void
    {
        $this->sendEvent(
            'user.permission.assigned',
            [
                'user_id' => $user->id,
                'username' => $user->username,
                'permission_id' => $permission->id,
                'permission_name' => $permission->name,
            ],
            (string) $user->id
        );
    }
    private function sendEvent(string $event, array $data, string $key): void
    {
        Kafka::publish()
            ->onTopic(config('kafka.user_topic'))
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
