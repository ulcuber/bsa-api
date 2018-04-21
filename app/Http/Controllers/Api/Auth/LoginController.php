<?php

namespace App\Http\Controllers\Api\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Passport\ApiTokenCookieFactory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:api')->except('logout:api');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('api')->attempt($credentials)) {
            $token = Auth::user()->createToken('user_token')->accessToken;
            return response()->json(['token' => $token]);
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return response()->json([true]);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: response()->json([false]);
    }

    protected function guard()
    {
        return Auth::guard('api');
    }
}
