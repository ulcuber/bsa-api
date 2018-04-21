<?php

namespace App\Http\Controllers\Api\Auth;

use Hash;
use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:api')->except('logout:api');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $remember = (bool) $request->input('remember');
        $user = User::where('email', $email)->first();

        $this->validateLogin($user, $password);

        $token = $user->createToken('user_token')->accessToken;

        return response()->json(['token' => $token]);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        return response()->json([true]);
    }

    protected function validateLogin($user, $password)
    {
        if (is_null($user)) {
            throw ValidationException::withMessages([
                'email' => __('Wrong email'),
            ]);
        }

        if (! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => __('Wrong password'),
            ]);
        }
    }

    protected function guard()
    {
        return Auth::guard('api');
    }
}
