<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
//use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    use ApiResponser;
    public function register(UserRequest $request)
    {
        $validated = $request->validated();
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);
        $token = $user->createToken($user->name . 'AuthToken')->plainTextToken;
        return $this->success([$user, 'access_token' => $token], 'created');
    }

    public function login(Request $request)

    {
        $input = $request->validate([
            'email' => 'required',
            'password' => 'required|'
        ]);
        $user = User::where('email', $input['email'])->first();
        if (!$user || !Hash::check($input['password'], $user->password)) {

            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        } else {
            $token = $user->createToken($user->name . 'AuthToken')->plainTextToken;
            return $this->success([$user, 'role' => $user->role, 'access_token' => $token], '');
        }
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            "message" => "logged out"
        ]);
    }
}
