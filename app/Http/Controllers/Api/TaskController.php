<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Tasks")]
class TaskController extends Controller
{
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
    public function index()
    {
        $tasks = Task::paginate(10);

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
        $task = Task::create($request->validated());

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
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
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "description", type: "string"),
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
        $task->update($request->validated());

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
        $task->delete();

        return response()->json([
            'message' => 'Deleted'
        ]);
    }
}
