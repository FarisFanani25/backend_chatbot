<?php

namespace App\Http\Controllers;

use App\Mail\VerificationEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        if ($user && $user->verification_token !== null) {
            return response()->json(['message' => 'Email belum diverifikasi. Silakan verifikasi email Anda.'], 403);
        }    

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

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
    
        // Generate 6-digit numeric token
        $token = random_int(100000, 999999);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_token' => $token,
            'role' => 'user',
        ]);
    
        // Send verification email
        Mail::to($request->email)->send(new VerificationEmail($token));
    
        return response()->json(['message' => 'User registered successfully. Check your email for the verification token.']);
    }

    public function verifyToken(Request $request)
    {
        $request->validate(['token' => 'required']);

        $user = User::where('verification_token', $request->token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        $user->verification_token = null;
        $user->save();

        return response()->json(['message' => 'Email verified successfully']);
    }

    // Logout
    // public function logout(Request $request)
    // {
    //     $request->user()->currentAccessToken()->delete();
    //     return response()->json(['message' => 'Logged out successfully']);
    // }

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

        // Validasi data yang akan diperbarui
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:6',
            'role' => 'sometimes|in:admin,user',
        ]);

        // Menyiapkan data untuk update
        $updateData = $request->only(['name', 'email', 'role']);
        
        // Update password jika ada perubahan
        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    public function checkEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $isAvailable = !User::where('email', $request->email)->exists();

    return response()->json(['isAvailable' => $isAvailable]);
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

    // Logout
    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan oleh user yang saat ini login
        $request->user()->currentAccessToken()->delete();
        
        // Memberikan respons sukses logout
        return response()->json(['message' => 'Logged out successfully']);
    }

}
