<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found']);
    }

    if (!Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Password mismatch',
            'input_password' => $request->password,
            'hashed' => $user->password
        ]);
    }

    return response()->json([
        'success' => true,
        'user' => $user
    ]);
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

