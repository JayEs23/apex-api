<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // If validation fails, return a validation error response
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Validation error',
                    'data' => $validator->errors(),
                ],
                422
            );
        }

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles' => ['user'],
        ]);

        // Return a success response with the created user's data
        return response()->json(
            [
                'status' => 'success',
                'message' => 'User registered successfully',
                'data' => $user,
            ],
            201
        );
    }

    /**
     * Authenticate the user and generate a token for login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        // If validation fails, return a validation error response
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Validation error',
                    'data' => $validator->errors(),
                ],
                422
            );
        }

        // Attempt to authenticate the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // If authentication succeeds, generate an access token
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->accessToken;

            // Return a success response with user data and access token
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Login successful',
                    'data' => [
                        'user' => $user,
                        'token' => $token,
                    ],
                ],
                200
            );
        } else {
            // If authentication fails, return an unauthorized error response
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'data' => null,
                ],
                401
            );
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request
            ->user()
            ->token()
            ->revoke();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Logged out successfully',
                'data' => null,
            ],
            200
        );
    }


    /**
 * Fetch the profile details of the authenticated user.
 *
 * @return \Illuminate\Http\JsonResponse
 */
    public function profile()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Return a success response with the user's profile data
        return response()->json([
            'status' => 'success',
            'message' => 'User profile retrieved successfully',
            'data' => $user
        ], 200);
    }

}
