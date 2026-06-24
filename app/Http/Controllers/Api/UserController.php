<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
class UserController extends Controller
{
    /**
     * GET /api/users
     * Список пользователей
     */

    #[OA\Get(
        path: "/api/users",
        summary: "Get all users with roles and permissions",
        tags: ["Users"],
        security: [["passport" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of users",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer"),
                                    new OA\Property(property: "name", type: "string"),
                                    new OA\Property(property: "email", type: "string"),
                                ]
                            )
                        )
                    ]
                )
            )
        ]
    )]

    public function index()
    {
        $users = User::with('roles.permissions','permissions')->get();

        return response()->json([
            'data' => $users
        ]);
    }

    /**
     * GET /api/users/{id}
     * Один пользователь
     */
    #[OA\Get(
        path: "/api/users/{user}",
        summary: "Get single user with roles and permissions",
        tags: ["Users"],
        security: [["passport" => []]],
        parameters: [
            new OA\Parameter(
                name: "user",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "User details",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "name", type: "string"),
                        new OA\Property(property: "email", type: "string"),
                    ]
                )
            )
        ]
    )]
    public function show(User $user)
    {
        return response()->json([
            'data' => $user->load('roles.permissions')
        ]);
    }

    /**
     * POST /api/users
     * Создание пользователя (если нужно)
     */
    #[OA\Post(
        path: "/api/users",
        summary: "Create new user",
        tags: ["Users"],
        security: [["passport" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "email", "password"],
                properties: [
                    new OA\Property(
                        property: "name",
                        type: "string",
                        example: "John Doe"
                    ),
                    new OA\Property(
                        property: "email",
                        type: "string",
                        format: "email",
                        example: "john@example.com"
                    ),
                    new OA\Property(
                        property: "password",
                        type: "string",
                        format: "password",
                        example: "secret123"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "User created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "message",
                            type: "string",
                            example: "User created successfully"
                        ),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer"),
                                new OA\Property(property: "name", type: "string"),
                                new OA\Property(property: "email", type: "string"),
                                new OA\Property(property: "created_at", type: "string", format: "date-time")
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        return response()->json([
            'message' => 'User created',
            'data' => $user
        ], 201);
    }

    /**
     * PUT /api/users/{id}
     */
    #[OA\Put(
        path: "/api/users/{user}",
        summary: "Update user",
        tags: ["Users"],
        security: [["passport" => []]],
        parameters: [
            new OA\Parameter(
                name: "user",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "email"],
                properties: [
                    new OA\Property(
                        property: "name",
                        type: "string",
                        example: "John Doe"
                    ),
                    new OA\Property(
                        property: "email",
                        type: "string",
                        example: "john@example.com"
                    ),
                    new OA\Property(
                        property: "password",
                        type: "string",
                        example: "newpassword123"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "User updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "User updated successfully"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer"),
                                new OA\Property(property: "name", type: "string"),
                                new OA\Property(property: "email", type: "string")
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "User not found"
            )
        ]
    )]
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $user->id],
        ]);

        $user->update($data);

        return response()->json([
            'message' => 'User updated',
            'data' => $user
        ]);
    }

    /**
     * DELETE /api/users/{id}
     */

    #[OA\Delete(
        path: "/api/users/{user}/permissions",
        summary: "Remove direct permissions from user",
        tags: ["Users"],
        security: [["passport" => []]],
        parameters: [
            new OA\Parameter(
                name: "user",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Permissions removed successfully"
            )
        ]
    )]
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted'
        ]);
    }

    /**
     * POST /api/users/{id}/roles
     * Назначение ролей пользователю
     */
    #[OA\Post(
        path: "/api/users/{user}/roles",
        summary: "Assign roles to user",
        tags: ["Users"],
        security: [["passport" => []]],
        parameters: [
            new OA\Parameter(
                name: "user",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "User ID"
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["role_ids"],
                properties: [
                    new OA\Property(
                        property: "role_ids",
                        type: "array",
                        items: new OA\Items(type: "integer"),
                        example: [1, 2]
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Roles assigned successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "message",
                            type: "string",
                            example: "Roles assigned successfully"
                        ),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer"),
                                new OA\Property(property: "name", type: "string"),
                                new OA\Property(property: "email", type: "string")
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "User not found"
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function assignRoles(Request $request, User $user)
    {
        $data = $request->validate([
            'role_ids' => ['required', 'array'],
            'role_ids.*' => ['exists:roles,id'],
        ]);

        $user->roles()->sync($data['role_ids']);

        return response()->json([
            'message' => 'Roles assigned successfully'
        ]);
    }

    /**
    POST /api/users/{user}/permissions
     * Назначить разрешение пользователю
     */
    #[OA\Post(
        path: "/api/users/{user}/permissions",
        summary: "Assign direct permissions to user",
        tags: ["Users"],
        security: [["passport" => []]],
        parameters: [
            new OA\Parameter(
                name: "user",
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
                        items: new OA\Items(type: "integer")
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Permissions assigned successfully"
            )
        ]
    )]
    public function assignPermissions(Request $request, User $user)
    {
        $request->validate([
            'permission_ids' => ['required', 'array'],
            'permission_ids.*' => ['exists:permissions,id'],
        ]);

        $user->permissions()->sync($request->permission_ids);

        return response()->json([
            'message' => 'Permissions assigned successfully'
        ]);
    }
}
