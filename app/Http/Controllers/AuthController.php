<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\SocialAccount;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Support\Facades\Response;
     */
    public function getAuthUrl()
    {
        try {
            return Response::json([
                'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
            ]);
        } catch (\Throwable $err) {
            return Response::json([
                'error' => $err->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Illuminate\Support\Facades\Response;
     */
    public function handleCallbackFromGoogle()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $user = null;
    
            DB::transaction(function () use ($googleUser, &$user) {
                $socialAccount = SocialAccount::firstOrNew(
                    ['social_code' => $googleUser->getId(), 'social_provider' => 'google'],
                    ['social_name' => $googleUser->getName()]
                );
    
                if (!($user = $socialAccount->user)) {
                    $user = User::create([
                        'email' => $googleUser->getEmail(),
                        'name' => $googleUser->getName(),
                    ]);
                    $socialAccount->fill(['user_id' => $user->id])->save();
                }
            });
    
            return Response::json([
                'user' => $user,
                'google_user' => $googleUser,
            ]);
        } catch (\Throwable $err) {
            return Response::json([
                'error' => $err->getMessage(),
            ]);
        }
    }

    public function getSessionToken(Request $request)
    {
        try {
            $token = $request->session()->token();
            $token = csrf_token();
                
            return Response::json([
                'csrf_token' => $token,
            ]);
        } catch (\Throwable $th) {
            return Response::json([
                'error' => $err->getMessage(),
            ]);
        }
    }
}
