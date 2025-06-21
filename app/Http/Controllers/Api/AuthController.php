<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


/**
 * @OA\Info(title="API Documentation", version="1.0")
 * @OA\Server(url="http://localhost:8000/api")
 */
class AuthController extends Controller
{
    
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="User Login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful login"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = auth('api')->attempt($credentials);

        return response()->json(['token' => $token]);
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     summary="User Registration",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *         )
     *     ),
     *     @OA\Response(response=201, description="User created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        return response()->json(['user' => $user], 201);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="User Logout",
     *     @OA\Response(response=200, description="Successfully logged out"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @OA\Get(
     *     path="/profile",
     *     summary="Get User Profile",
     *     @OA\Response(response=200, description="User profile retrieved successfully"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function profile()
    {
        return response()->json(auth()->user());
    }
}
