<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Role\AssignPermissionsRequest;
use OpenApi\Attributes as OA;


class RoleController extends Controller
{
    /**
    GET: Получить все роли
     */
    #[OA\Get(
        path: "/api/roles",
        summary: "Get all roles",
        tags: ["Roles"],
        security: [["passport" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of roles",
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
        return response()->json(Role::all());
    }

    /**
    GET: Получить одну роль
     */
    #[OA\Get(
        path: "/api/roles/{role}",
        summary: "Get single role with permissions",
        tags: ["Roles"],
        security: [["passport" => []]],
        parameters: [
            new OA\Parameter(
                name: "role",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Role details",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "name", type: "string"),
                        new OA\Property(property: "code", type: "string"),
                        new OA\Property(
                            property: "permissions",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer"),
                                    new OA\Property(property: "name", type: "string"),
                                    new OA\Property(property: "code", type: "string")
                                ]
                            )
                        )
                    ]
                )
            )
        ]
    )]
    public function show(Role $role): JsonResponse
    {
        return response()->json($role);
    }

    /**
    POST: Создать роль
     */
    #[OA\Post(
        path: "/api/roles",
        summary: "Create role",
        tags: ["Roles"],
        security: [["passport" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "code"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Admin"),
                    new OA\Property(property: "code", type: "string", example: "admin")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Role created successfully"
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = Role::create($request->validated());

        return response()->json([
            'message' => 'Role created successfully',
            'data' => $role,
        ], 201);
    }

    /**
    PUT: Изменить роль
     */
    #[OA\Put(
        path: "/api/roles/{role}",
        summary: "Update role",
        tags: ["Roles"],
        security: [["passport" => []]],
        parameters: [
            new OA\Parameter(
                name: "role",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Admin"),
                    new OA\Property(property: "code", type: "string", example: "admin")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Role updated successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Role not found"
            )
        ]
    )]
    public function update(
        UpdateRoleRequest $request,
        Role $role
    ): JsonResponse {
        $role->update($request->validated());

        return response()->json([
            'message' => 'Role updated successfully',
            'data' => $role,
        ]);
    }

    /**
        DELETE: Удалить роль
     */
    #[OA\Delete(
        path: "/api/roles/{role}",
        summary: "Delete role",
        tags: ["Roles"],
        security: [["passport" => []]],
        parameters: [
            new OA\Parameter(
                name: "role",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Role deleted successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Role not found"
            )
        ]
    )]
    public function destroy(Role $role): JsonResponse
    {
        $role->delete();

        return response()->json([
            'message' => 'Role deleted successfully',
        ]);
    }

    /**
    POST: Назначить разрешение для роля
     */
    #[OA\Post(
        path: "/api/roles/{role}/permissions",
        summary: "Assign permissions to role",
        tags: ["Roles"],
        security: [["passport" => []]],
        parameters: [
            new OA\Parameter(
                name: "role",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["permission_ids"],
                properties: [
                    new OA\Property(
                        property: "permission_ids",
                        type: "array",
                        items: new OA\Items(type: "integer"),
                        example: [1, 2, 3]
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Permissions assigned to role"
            ),
            new OA\Response(
                response: 404,
                description: "Role not found"
            )
        ]
    )]
    public function assignPermissions(
        AssignPermissionsRequest $request,
        Role $role
    )
    {
        $role->permissions()->sync(
            $request->validated()['permission_ids']
        );

        return response()->json([
            'message' => 'Permissions assigned successfully',
        ]);
    }
}
