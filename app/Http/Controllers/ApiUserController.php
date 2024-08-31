<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiUserController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/register",
     * summary="Register a new user",
     * tags={"Auth"},
     * @OA\Parameter(
     * name="name",
     * in="query",
     * description="User's name",
     * required=true,
     * @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     * name="email",
     * in="query",
     * description="User's email",
     * required=true,
     * @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     * name="password",
     * in="query",
     * description="User's password",
     * required=true,
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(response="201", description="User registered successfully"),
     * @OA\Response(response="422", description="Validation errors")
     * )
     */

    public function register(Request $request)
    {

        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 201);
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Authenticate user and generate JWT token",
     * tags={"Auth"},
     * @OA\Parameter(
     * name="email",
     * in="query",
     * description="User's email",
     * required=true,
     * @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     * name="password",
     * in="query",
     * description="User's password",
     * required=true,
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(response="200", description="Login successful"),
     * @OA\Response(response="401", description="Invalid credentials")
     * )
     */
    
    public function login(Request $request)
    {

        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="User logout",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="You Logged Out Successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User is not authenticated or token not provided",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You Are Not Authenticated or Token Not Provided")
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function logout(Request $request)
    {
        if ($user = $request->user()) {

            $user->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'You Logged Out Successfully',
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You Are Not Authenticated or Token Not Provided',
            ], 401);
        }
    }
}
