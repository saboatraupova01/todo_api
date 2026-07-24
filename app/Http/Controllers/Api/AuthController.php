<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Jobs\SendWelcomeEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;
use App\Models\Role;
use App\Services\Kafka\UserEventProducer;


class AuthController extends Controller
{

    private UserEventProducer $userEventProducer;


    public function __construct(UserEventProducer $userEventProducer)
    {
        $this->userEventProducer = $userEventProducer;
    }
    #[OA\Post(
        path: "/api/register",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "email"],
                properties: [
                    new OA\Property(property: "name", type: "string"),
                    new OA\Property(property: "email", type: "string"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success"
            )
        ]
    )]
    public function register(RegisterRequest $request)
    {
        $username = Str::lower(Str::random(6));
        $passwordPlain = Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $username,
            'password' => Hash::make($passwordPlain),
        ]);
        $userRole = Role::where('code', 'user')->first();

        $user->roles()->attach($userRole->id);

        $this->userEventProducer->userCreated($user);
        SendWelcomeEmailJob::dispatch($user, $passwordPlain);

        return response()->json([
            'data' => [
               // 'user' => $user,
            ],
            'message' => 'User registered successfully',
        ]);
    }

    #[OA\Post(
        path: "/api/login",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["username", "password"],
                properties: [
                    new OA\Property(property: "username", type: "string"),
                    new OA\Property(property: "password", type: "string"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success"
            )
        ]
    )]
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
        ])) {
            return response()->json([
                'data' => null,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => 'Login successful',
        ]);
    }
}
