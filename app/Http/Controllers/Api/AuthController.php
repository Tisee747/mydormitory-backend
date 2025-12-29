<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Role sederhana: kalau ada relasi penghuni => penghuni, selain itu admin
            $role = $user->penghuni ? 'penghuni' : 'admin';

            return response()->json([
                'success' => true,
                'role' => $role,
                'user' => $user,
                'penghuni' => $user->penghuni
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah'
        ], 401);
    }
}
