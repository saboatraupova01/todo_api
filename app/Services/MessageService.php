<?php
namespace App\Services;

use App\Models\Message;
use App\DTO\MessageDTO;

class MessageService{
    public function create(MessageDTO $messageDTO): Message{
        return Message::create([
            'user_id' => $messageDTO->userId,
            'message' => $messageDTO->message,
        ]);
    }
}
