<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\EmailVerificationNotification;

class UserController extends Controller
{

    public function index()
    {
        return  DataTables::of(User::query()->orderBy('created_at', 'DESC'))
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['created_at'])
            ->make(true);
    }

    use ApiResponser;
    public function register(UserRequest $request, UserService $userService)
    {
        $validated = $request->validated();
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);
        $token = $user->createToken($user->name . 'AuthToken')->plainTextToken;
       // $user->notify(new EmailVerificationNotification());
        return $this->success([$user, 'access_token' => $token], __('main.register_customer_is_done'));
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
            return $this->success([$user, 'role' => $user->role, 'access_token' => $token], __('main.login_authenticated_is_done'));
        }
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            "message" => __('main.log_out')
        ]);
    }
}
