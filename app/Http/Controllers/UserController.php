<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Traits\GeneralTrait;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Policies\UserPolicy;
use App\Providers\AuthServiceProvider;
use Illuminate\Database;
use phpDocumentor\Reflection\Types\Null_;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    use GeneralTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user)
    {
        $userFound = User::find($user);
        try {
            $this->authorize('viewAny', $userFound);
        } catch (\Exception $e)
        {
            return $e;
        }

        if(!$userFound)
            return $this->returnError(404, $this->getErrorCode('user not found'), 'user is not found');

        return $this->returnData('user', $userFound, 200, 'user found!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create',User::class);

        $validator = Validator::make(request()->all(), [
            "first_name" => "required",
            "last_name" => "required",
            "username" => "required|unique:users",
            "email" => "required|email|unique:users",
            "password" => "required",
            "role" => ['required', Rule::in(['admin', 'customer','manager']),],
            "mobile_number" => "required|digits_between:10,11"
        ]);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $newpassword = bcrypt(request('password'));

        $user = User::create([
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'username' => request('username'),
            'email' => request('email'),
            'password' => $newpassword,
            'role' => request('role'),
            'mobile_number' => request('mobile_number'),
        ]);

        return $this->returnSuccessMessage('User Created Successfully!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
