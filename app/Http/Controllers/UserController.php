<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    //

    private $relations = ['tasks', 'house'];

    public function create(Request $request)
    {
        try {
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password'))
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'User already exists']);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('token'));
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    public function search($query)
    {
        $users = User::where('email', 'LIKE', '%'.$query.'%')->where('house_id', '=', null)->get();

        return response()->json($users->toArray(), 200);
    }

    public function me()
    {
        $user = JWTAuth::parseToken()->authenticate();

        return response()->json($user->load($this->relations), 200);
    }
}
