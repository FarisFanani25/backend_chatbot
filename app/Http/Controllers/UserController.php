<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   // Proses Login
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login successful',
        'access_token' => $token,
        'user' => $user
    ]);
}

    // Logout
public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out successfully']);
}

    // GET All Users
public function index()
{
    $users = User::all();
    return response()->json([
        'message' => 'Users fetched successfully',
        'users' => $users
    ]);
}


    // GET Specific User
public function show($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    return response()->json([
        'message' => 'User fetched successfully',
        'user' => $user
    ]);
}

    // POST (Create User)
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'role' => 'required|in:admin,user',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            return response()->json(['message' => 'User created successfully', 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create user', 'error' => $e->getMessage()], 500);
        }
    }


    // PUT (Update User)
public function update(Request $request, $id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $user->id,
        'password' => 'sometimes|min:6',
        'role' => 'sometimes|in:admin,user',
    ]);

    $user->update($request->only(['name', 'email', 'role']) + [
        'password' => $request->password ? Hash::make($request->password) : $user->password,
    ]);

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user
    ]);
}


    // DELETE User
public function destroy($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $user->delete();

    return response()->json([
        'message' => 'User deleted successfully'
    ]);
}

}
