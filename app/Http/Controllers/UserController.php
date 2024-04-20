<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    /**
 * Update the profile details of the authenticated user.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function updateProfile(Request $request)
{
    // Check if the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthenticated.',
            'data' => null
        ], 401);
    }

    // Validate the request data
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,'.Auth::id(),
        'password' => 'sometimes|required|string|min:6',
    ]);

    // If validation fails, return a validation error response
    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation error',
            'data' => $validator->errors()
        ], 422);
    }

    // Retrieve the authenticated user
    $user = Auth::user();

    // Update the user's profile details
    $user->name = $request->name;
    $user->email = $request->email;
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }
    $user->save();

    // Return a success response with the updated user data
    return response()->json([
        'status' => 'success',
        'message' => 'Profile updated successfully',
        'data' => $user
    ], 200);
}


/**
 * Update the password of the authenticated user.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function updatePassword(Request $request)
{
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'current_password' => 'required|string|min:6',
        'new_password' => 'required|string|min:6|different:current_password',
    ]);

    // If validation fails, return a validation error response
    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation error',
            'data' => $validator->errors()
        ], 422);
    }

    // Retrieve the authenticated user
    $user = Auth::user();

    // Check if the current password matches the user's password
    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Current password is incorrect',
            'data' => null
        ], 400);
    }

    // Update the user's password
    $user->update(['password' => Hash::make($request->new_password)]);

    // Return a success response
    return response()->json([
        'status' => 'success',
        'message' => 'Password updated successfully',
        'data' => null
    ], 200);
}


}
