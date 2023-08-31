<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class AuthController extends Controller
{
    public function redirectToAuth(): JsonResponse
    {
        return response()->json([
            'url' => Socialite::driver('google')
                ->stateless()
                ->redirect()
                ->getTargetUrl(),
        ]);
    }
    public function handleAuthCallback(): JsonResponse
    {
        try {
            /** @var SocialiteUser $socialiteUser */
            $socialiteUser = Socialite::driver('google')->stateless()->user();
        } catch (ClientException $e) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }
    
        $user = User::where('email', $socialiteUser->getEmail())->first();
    
        if ($user) {
            // Update fields if they are null
            if (empty($user->firstname)) {
                $user->firstname = $socialiteUser->getName();
            }
            if (empty($user->google_id)) {
                $user->google_id = $socialiteUser->getId();
            }
            $user->email_verified_at = now();
            $user->save();
        } else {
            // Create a new user if not found
            $name =  $socialiteUser->getName();
            $delimiter = " ";
            $nameArray = explode($delimiter, $name);
            $user = User::create([
                'email' => $socialiteUser->getEmail(),
                'firstname' => $nameArray[0],
                'lastname' => $nameArray[1],
                'google_id' => $socialiteUser->getId(),
                'email_verified_at' => now(),
            ]);
        }

        $token = $user->createToken('client-web')->plainTextToken;
    
        return response()->json([
            'user' => $user,
            'access_token' => $token,
        ]);
    }
    
}
