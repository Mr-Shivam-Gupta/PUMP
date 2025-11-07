<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Hash;

class SuperAdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:super_admins,email',
            'password' => 'required|string|min:6',
        ]);

        $superAdmin = SuperAdmin::where('email', $request->email)->first();

        if (!$superAdmin || !Hash::check($request->password, $superAdmin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($superAdmin->status != 1) {
            return response()->json(['message' => 'Account inactive'], 403);
        }

        // Generate a token (if using Sanctum or Passport)
        $token = $superAdmin->createToken('SuperAdminToken')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'super_admin' => $superAdmin,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
