<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Customer;
use App\Http\Traits\GeneralTrait;
use Illuminate\Http\Request;

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

    use GeneralTrait;
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth:api')->only('logout');
    }

    public function login(Request $request)
    {
        $creds = request()->only(['email', 'password']);
        $token = auth('api')->attempt($creds);

        if (!$token)
            return $this->returnError($this->getErrorCode('incorrect email or password'), 401, 'incorrect email or password');

        $user =  User::where(['email' => $request['email']])->get()[0];

        $role = $user->role;
        $id = $user->id;
        $role_id = $id;

        // if($role == 'customer')
        // {
        //     return "Nice";

        //     $role_id = Customer::where('user_id', $id)->get()[0]->id;
        // }
        // else if($role == 'manager')
        //     $role_id = Manager::where('user_id', $id)->get()[0]->id;
        // else if($role == 'admin')
        //     $role_id = null;

        // return response()->json(['token' => $token]);
        return $this->returnData('data', [
            'token' => $token,
            'role' =>  $role,
            'id' => $user->id,
            'role_id' => $role_id,
        ],  200, 'logged in successfully');
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return $this->returnSuccessMessage('logged out successfully');
    }
}
