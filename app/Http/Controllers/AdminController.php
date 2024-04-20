<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Create a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'roles' => 'required|array',
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

        // Create the new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles' => $request->roles,
        ]);

        // Return a success response with the created user data
        return response()->json(
            [
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => $user,
            ],
            201
        );
    }

    /**
     * Get all users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve all users
        $users = User::all();

        // Return a success response with the users data
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Users retrieved successfully',
                'data' => $users,
            ],
            200
        );
    }

    /**
     * Update the details of a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Find the user by id
        $user = User::find($id);

        // If user not found, return an error response
        if (!$user) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'User not found',
                    'data' => null,
                ],
                404
            );
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:6',
            'roles' => 'required|array',
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

        // Update the user details
        $user->name = $request->name;
        $user->email = $request->email;
        $user->roles = $request->roles;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Return a success response with the updated user data
        return response()->json(
            [
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => $user,
            ],
            200
        );
    }

    /**
     * Delete a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the user by id
        $user = User::find($id);

        // If user not found, return an error response
        if (!$user) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'User not found',
                    'data' => null,
                ],
                404
            );
        }

        // Delete the user
        $user->delete();

        // Return a success response
        return response()->json(
            [
                'status' => 'success',
                'message' => 'User deleted successfully',
                'data' => null,
            ],
            200
        );
    }
}
