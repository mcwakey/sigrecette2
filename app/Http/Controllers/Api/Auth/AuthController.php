<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;
use App\Models\Commune;
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
        if ($user->zone == null) {
            return response()->json([
                "message" => "Your user has not zone",
            ], 404);
        }
        $token = $user->createToken($user->email . '-AuthToken')->plainTextToken;
        $commune = Commune::getFirstCommune();
        return response()->json([
            'access_token' => $token,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => __($user->getRoleNames()->first()),
            'zone' => $user->zone->name,
            'commune' => $commune ? [
                'title' => $commune->title,
                'phone_number' => $commune->phone_number,
                'logo_url' => $commune->getImageUrlAttributeDirect('logo'),
            ] : null,
            'sms' => [
                'enabled' => config('features.sms_notifications_feature', false),
                'default_provider' => config('mobile-payment.sms.default_provider'),
                'sender_id' => config('mobile-payment.sms.sender_id'),
                'providers' => array_keys(config('mobile-payment.sms.providers', [])),
            ],
            'mobile_payment' => [
                'enabled' => config('features.mobile_payment_feature', false),
                'default_provider' => config('mobile-payment.default_provider'),
                'transaction_expiry_minutes' => config('mobile-payment.transaction_expiry_minutes'),
                'verification' => config('mobile-payment.verification'),
                'providers' => config('mobile-payment.providers'),
            ],
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'logged out'], 200);
    }
}
