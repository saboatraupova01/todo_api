<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\Kafka\TaskEventProducer;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Events\PublicTaskUpdated;
use App\Events\PublicTaskDeleted;


class TaskController extends Controller
{

    private TaskService $taskService;

    private TaskEventProducer $taskEventProducer;


    public function __construct(
        TaskService $taskService,
        TaskEventProducer $taskEventProducer
    ) {

        $this->taskService = $taskService;
        $this->taskEventProducer = $taskEventProducer;

    }

    public function index(Request $request)
    {

        $user = $request->user();

        $tasks = $user->tasks()
            ->where('is_public', false)
            ->with([
                'category',
                'user'
            ])
            ->latest()
            ->get();



        return TaskResource::collection($tasks);

    }

    public function store(StoreTaskRequest $request)
    {

        $task = $this->taskService->createTask(
            $request->user(),
            $request->validated()
        );

       $this->taskEventProducer
            ->taskCreated($task);

        return new TaskResource($task);

    }

    public function show(Task $task)
    {
        $this->authorize(
            'view',
            $task
        );

        return new TaskResource($task);

    }

    public function update(
        UpdateTaskRequest $request,
        Task $task
    ) {
        $this->authorize(
            'update',
            $task
        );
        $task->update(
            $request->validated()
        );

        Cache::forget(
            "user_{$task->user_id}_tasks"
        );

        $this->taskEventProducer
            ->taskUpdated($task);

        return new TaskResource($task);

    }
    public function destroy(Task $task)
    {
        $this->authorize(
            'delete',
            $task
        );

        $userId = $task->user_id;

        $this->taskEventProducer
            ->taskDeleted($task->id);

        $task->delete();

        Cache::forget(
            "user_{$userId}_tasks"
        );

        return response()->json([
            'message'=>'Deleted'
        ]);

    }

    public function updatePublic(
        UpdateTaskRequest $request,
        Task $task
    ) {
        $user = $request->user();

        if(
            !$user->hasPermission('public-tasks.update')
        ){

            return response()->json([
                'message'=>'Forbidden'
            ],403);

        }


        $task->update($request->validated());

        $task->load([
            'user',
            'category'
        ]);


        if($task->is_public){

            event(new PublicTaskUpdated([
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'category' => $task->category?->name,
                'user' => $task->user->name,
            ]));

        }

        return new TaskResource($task);

    }




    public function destroyPublic(Task $task)
    {
        $user = request()->user();

        if(!$user->hasPermission('public-tasks.delete')){

            return response()->json([
                'message'=>'Forbidden'
            ],403);

        }

        if(!$task->is_public) {

            return response()->json([
                'message' => 'Not public task'
            ], 403);

        }
        $task->load('user');

        event(new PublicTaskDeleted([
            'id' => $task->id,
            'title' => $task->title,
            'user' => $task->user->name,
        ]));

        $task->delete();
        return response()->json([
            'message'=>'Public task deleted'
        ]);

    }


}
