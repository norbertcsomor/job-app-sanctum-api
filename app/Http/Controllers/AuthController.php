<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * A felhasználó bejelentkeztetése a szerveren.
     * 
     * @param  \Illuminate\Http\AuthRequest  $request a bejelentkezéshez szükséges adatok.
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function login(AuthRequest $request)
    {
        $credentials = $request->all();

        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);

        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'message' => 'Helytelen adatok!'
            ], Response::HTTP_UNAUTHORIZED)
                ->header('Content-Type', 'application/json');
        }

        $user = Auth::user();
        $token = $user->createToken('key')->plainTextToken;

        return response([
            'canLogin' => true,
            'token' => $token,
            'user_id' => $user->id,
            'role' => $user->role,
            'message' => 'Sikerült a bejelentkezés!'
        ], Response::HTTP_OK)
            ->header('Content-Type', 'application/json');
    }

    /**
     * A felhasználó kijelentkeztetése a szerveren.
     * 
     * @return \Illuminate\Http\Response a szerver válasza.
     */
    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return [
            'message' => 'Sikerült a kijelentkezés.'
        ];
    }
}
