<?php
namespace App\DTO;

use App\Http\Requests\StoreRequest;

class MessageDTO{
    public function __construct(
        public readonly int $userId,
        public readonly string $message,
    ){}
    public static function fromRequest(StoreRequest $request): self{
        return new self(
            userId: auth()->id(),
            message: $request->validated()['message']
        );
    }
}

