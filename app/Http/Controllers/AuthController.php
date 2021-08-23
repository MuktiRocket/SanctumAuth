<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

class AuthController extends BaseController
{
    public function register(RegisterRequest $request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    }
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            $user = $request->user();
            $token =  $user->createToken('token')->plainTextToken;
            $data = [
                'user' => new UserResource($user),
                'access_token' => $token
            ];
            $cookie = cookie('jwt', $token, 60 * 24);
            return $this->respond('Success', Response::HTTP_OK, $data)->withCookie($cookie);
        }
        return $this->respond('Unsuccessfull', Response::HTTP_UNAUTHORIZED);
    }

    public function user()
    {
        $users = Auth::user();
        return $users;
    }
    public function logOut(Request $request)
    {
        $request->user()->tokens()->delete();
        $cookie = Cookie::forget('jwt');
        return $this->respond('Success', Response::HTTP_OK)->withCookie($cookie);
    }
}
