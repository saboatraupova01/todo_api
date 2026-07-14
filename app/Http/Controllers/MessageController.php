<?php

namespace App\Http\Controllers;

use App\DTO\MessageDTO;
use App\Http\Requests\StoreRequest;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Events\MessageDeleted;
use App\Events\MessageUpdated;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::with('user')
            ->latest('id')
            ->limit(20)
            ->get()
            ->reverse();

        return response()->json([
            'data' => $messages,
        ]);
    }


    public function store(
        StoreRequest $request,
        MessageService $service
    )
    {
        $dto = MessageDTO::fromRequest($request);

        $message = $service->create($dto);

        event(new MessageSent($message->load('user')));

        return redirect('/chat');
    }

    public function chat()
    {
        $messages = Message::with('user')
            ->latest()
            ->limit(20)
            ->get()
            ->reverse();

        return view('chat.index', compact('messages'));
    }

    public function destroy(Message $message)
    {
        if ($message->user_id !== auth()->id()) {
            abort(403);
        }

        $id = $message->id;

        $message->delete();

        event(new MessageDeleted($id));

        return redirect('/chat');
    }

    public function edit(Message $message)
    {
        if ($message->user_id !== auth()->id()) {
            abort(403);
        }

        return view('chat.edit', compact('message'));
    }


    public function update(Request $request, Message $message)
    {
        if ($message->user_id !== auth()->id()) {
            abort(403);
        }


        $data = $request->validate([
            'message'=>'required|string|max:1000'
        ]);


        $message->update($data);
        event(new MessageUpdated($message->load('user')));
        return redirect('/chat');
    }
}
