<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\Kafka\TaskEventProducer;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Tasks")]

class TaskController extends Controller
{
    private TaskEventProducer $taskEventProducer;

    public function __construct(TaskEventProducer $taskEventProducer)
    {
        $this->taskEventProducer = $taskEventProducer;
    }
    #[OA\Get(
        path: "/api/tasks",
        tags: ["Tasks"],
        security: [["passport" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of tasks"
            )
        ]
    )]
    public function index(Request $request)
    {
        $tasks = $request->user()
            ->tasks()
            ->with(['category', 'user'])
            ->get();

        return TaskResource::collection($tasks);
    }
    #[OA\Post(
        path: "/api/tasks",
        tags: ["Tasks"],
        security: [["passport" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title", "status"],
                properties: [
                    new OA\Property(property: "title", type: "string"),
                    new OA\Property(property: "description", type: "string"),
                    new OA\Property(property: "status", type: "string", example: "new"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Created"
            )
        ]
    )]


    public function store(StoreTaskRequest $request)
    {
        $task = $request->user()
            ->tasks()
            ->create($request->validated());
        $this->taskEventProducer->taskCreated($task);

        return new TaskResource($task);
    }
    #[OA\Get(
        path: "/api/tasks/{id}",
        tags: ["Tasks"],
        security: [["passport" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "Task ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Task details"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden - user cannot access this task"
            ),
            new OA\Response(
                response: 404,
                description: "Task not found"
            )
        ]
    )]
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return new TaskResource($task);
    }


    #[OA\Put(
        path: "/api/tasks/{id}",
        tags: ["Tasks"],
        security: [["passport" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "title", type: "string"),
                    new OA\Property(property: "description", type: "string"),
                    new OA\Property(property: "status", type: "string"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Updated"
            )
        ]
    )]
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        $this->taskEventProducer->taskUpdated($task);

        return new TaskResource($task);
    }
    #[OA\Delete(
        path: "/api/tasks/{id}",
        tags: ["Tasks"],
        security: [["passport" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Deleted"
            )
        ]
    )]
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $this->taskEventProducer->taskDeleted($task->id);

        $task->delete();

        return response()->json([
            'message' => 'Deleted'
        ]);
    }


}
