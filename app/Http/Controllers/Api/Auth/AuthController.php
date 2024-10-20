<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function auth(AuthRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if($user->zone==null){
            return response()->json([
                "message"=>"Your user has not zone",
            ], 404);
        }
        $token = $user->createToken($user->email . '-AuthToken')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'user_id' => $user->id,
            'name' => $user->name,
            'email'=> $user->email,
            'role' => $user->getRoleNames()->first(),
            'zone' => $user->zone->name,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'logged out'], 200);
    }
}
