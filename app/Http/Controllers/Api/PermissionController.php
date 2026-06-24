<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permissions\StorePermissionRequest;
use App\Http\Requests\Permissions\UpdatePermissionRequest;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PermissionController extends Controller
{
    /**
        GET: Получить список разрешений
     */
    #[OA\Get(
        path: "/api/permissions",
        summary: "Get all permissions",
        tags: ["Permissions"],
        security: [["passport" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of permissions",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "name", type: "string"),
                            new OA\Property(property: "code", type: "string")
                        ]
                    )
                )
            )
        ]
    )]
    public function index(): JsonResponse
    {
        return response()->json(Permission::all());
    }

    /**
     GET: Получить одно разрешение
     */
    #[OA\Get(
        path: "/api/permissions/{permission}",
        summary: "Get single permission",
        tags: ["Permissions"],
        security: [["passport" => []]],
        parameters: [
            new OA\Parameter(
                name: "permission",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Permission details",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "name", type: "string"),
                        new OA\Property(property: "code", type: "string")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Permission not found"
            )
        ]
    )]

    public function show(Permission $permission): JsonResponse
    {
        return response()->json($permission);
    }

    /**
   POST: Создание разрешения
     */
    #[OA\Post(
        path: "/api/permissions",
        summary: "Create permission",
        tags: ["Permissions"],
        security: [["passport" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "code"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Create users"),
                    new OA\Property(property: "code", type: "string", example: "users.create")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Permission created successfully"
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function store(StorePermissionRequest $request): JsonResponse
    {
        $permission = Permission::create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Permission created successfully',
            'data' => $permission,
        ], 201);
    }

    /**
    PUT: Изменение разрешения
     */
    #[OA\Put(
        path: "/api/permissions/{permission}",
        summary: "Update permission",
        tags: ["Permissions"],
        security: [["passport" => []]],
        parameters: [
            new OA\Parameter(
                name: "permission",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Update users"),
                    new OA\Property(property: "code", type: "string", example: "users.update")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Permission updated successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Permission not found"
            )
        ]
    )]
    public function update(
        UpdatePermissionRequest $request,
        Permission $permission
    ): JsonResponse {
        $permission->update(
            $request->validated()
        );

        return response()->json([
            'message' => 'Permission updated successfully',
            'data' => $permission,
        ]);
    }

    /**
        DELETE: Удаление разрешения
     */
    #[OA\Delete(
        path: "/api/permissions/{permission}",
        summary: "Delete permission",
        tags: ["Permissions"],
        security: [["passport" => []]],
        parameters: [
            new OA\Parameter(
                name: "permission",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Permission deleted successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Permission not found"
            )
        ]
    )]
    public function destroy(
        Permission $permission
    ): JsonResponse {
        $permission->delete();

        return response()->json([
            'message' => 'Permission deleted successfully',
        ]);
    }
}
